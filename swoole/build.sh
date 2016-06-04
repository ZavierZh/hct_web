#!/bin/bash

function get_ip(){
	local ip temp
	IP=
	for ip in $REPO_IP;do
		temp=`curl http://${ip}/repo/gitweb/ 2>/dev/null | grep $BASE`
		if [ ! -z "$temp" ];then
			IP=$ip
			break
		fi
	done
	if [ -z "$IP" ];then
		echo "build_stat:从服务器获得 $BASE 失败" >$FIFO_FILE
		exit 1		
	fi
}

function file_time(){
	var=`stat -c "%y" $1`
	var=${var%:*}
	var=${var#*-}
	var=${var/ /_}
	var=${var/:/}
	echo $var
}

function failed_cp(){
	if [ -f "$BUILD_LOG" ];then
		target_file_log=${TODAY_DOWNLOAD_DIR}/${SUB}_$(file_time ${BUILD_LOG}).log
		cp $BUILD_LOG $target_file_log
		echo "build_stat:$* @ ${SMB_DIR}/${TODAY_DATE}/${SUB}_$(file_time ${BUILD_LOG}).log" >$FIFO_FILE
	else
		echo "build_stat:$* @ not found error log" >$FIFO_FILE
	fi
	exit 1
}

function repo_init(){
	curl http://${IP}/repo/repo > ./repo && chmod a+x repo && ./repo init -u http://${IP}/repo/hct-mtk/${BASE}/manifests.git/ 2>&1 >$BUILD_LOG 
	return $?
}

function mul_repo_init(){
	echo "build_stat:init repo" >$FIFO_FILE
	repo_init
	if [ $? != 0 ];then
		rm -rf .* *
		repo_init || failed_cp "init repo failed"
	fi
}

function check_main_code(){
	local dirs ret
	dirs=`cat .repo/manifests/default.xml | awk 'match($0,"path=\"(.*)\".*name=",a){print a[1]}'`
	for dir in $dirs;do
		[ ! -d $dir ] && return 1
	done
	return 0
}
# ==============================
# start script
# ==============================

# 加载配置

#sleep 5;exit 1
. ./build_client.config
export IP BASE MAIN SUB HCT
path=`echo $1| tr '/' ' '`
path=($path)
if [ ${#path[@]} == 3 ];then
	BASE=${path[0]}
	MAIN=${path[1]}
	SUB=${path[2]}
elif [ ${#path[@]} == 5 ];then
	if [ ${path[1]} == "dists" ] && [ ${path[2]} == "targets" ];then
		BASE=${path[0]}
		MAIN=${path[3]}
		SUB=${path[4]}
	else
		echo "build_stat:项目设置错误" >$FIFO_FILE
		exit 1
	fi
else
	echo "build_stat:项目设置错误" >$FIFO_FILE
	exit 1
fi
get_ip

LOCAL_IP=`ifconfig | head -n2 |tail -n1 |awk '{split($2,a,":");print a[2];}'`
export SMB_DIR="smb://${LOCAL_IP}/$(basename ${SHARE_DIR})"

cd $BUILD_DIR
BASE_DIR=$BUILD_DIR/$BASE
TODAY_DATE=`date '+%F'`
TODAY_DOWNLOAD_DIR="${SHARE_DIR}/${TODAY_DATE}"
[ ! -d "${TODAY_DOWNLOAD_DIR}" ] && mkdir -p ${TODAY_DOWNLOAD_DIR}

if [ ! -d "$BASE_DIR" ];then
	mkdir $BASE_DIR
	cd $BASE_DIR
	[ -f "$BUILD_LOG" ] && rm $BUILD_LOG
	mul_repo_init
	echo "build_stat:./repo sync" >$FIFO_FILE
	(./repo sync) 2>&1 >$BUILD_LOG || failed_cp "./repo sync failed"
else
	cd $BASE_DIR
	[ -f "$BUILD_LOG" ];rm $BUILD_LOG
	check_main_code
	if [ $? != 0 ] || [ ! -f "./distribute" ];then
		rm -rf * .*
		mul_repo_init
		echo "build_stat:./repo sync" >$FIFO_FILE
		(./repo sync) 2>&1 >$BUILD_LOG || failed_cp "./repo sync failed"
	fi
fi
./distribute --clean
echo "build_stat:./distribute --update --all" >$FIFO_FILE
./distribute --update --all 2>&1 >$BUILD_LOG 
if [ $? != 0 ];then
	rm -rf * .*
	mul_repo_init
	echo "build_stat:./repo sync" >$FIFO_FILE
	(./repo sync) 2>&1 >$BUILD_LOG || failed_cp "./repo sync failed"
	echo "build_stat:./distribute --update --all" >$FIFO_FILE
	./distribute --update --all 2>&1 >$BUILD_LOG || failed_cp "./distribute --update --all failed"
fi

echo "build_stat:./distribute --init,-p" >$FIFO_FILE
./distribute --init dists/targets/${MAIN} 2>&1 >$BUILD_LOG && ./distribute -p dists/targets/${MAIN}/${SUB} 2>&1 >$BUILD_LOG || failed_cp "./distribute --init or -p failed"

## 让./distribute -b 能用
echo "ENV_HCT_BUILD_PROJECT_ACTION=debug" >> dists.log
HCT=`cat dists.log | grep "^ENV_HCT_BUILD_PLATFORM_NAME" | cut -d '=' -f 2`
[ -z "$HCT" ] && echo "build_stat:not found ENV_HCT_BUILD_PLATFORM_NAME in dists.log" >$FIFO_FILE && exit 1

if [ -f ./mk_aliphone.sh ];then
	echo "build_stat:./mk_aliphone.sh ${HCT} eng adb new true" >$FIFO_FILE
	./mk_aliphone.sh ${HCT} eng adb new true || failed_cp "./mk_aliphone.sh ${HCT} eng adb new true failed"
	echo "build_stat:./imgout" >$FIFO_FILE \
	./imgout 2>&1 >$BUILD_LOG || failed_cp "./imgout failed"

	DOWNLOAD_DIR=release-${HCT}
	#tar -zcvf ${SUB}_`file_time release-${HCT}`.tar.gz release-${HCT} 
else
	source build/envsetup.sh
	lunch full_${HCT}-eng
	if [ $(set | grep hct_new_modem | wc -l) -ge 1 ];then
		echo "build_stat:hct_new_modem" >$FIFO_FILE
		hct_new_modem  2>&1 >$BUILD_LOG || failed_cp "hct_new_modem failed"
	fi
	echo "build_stat:make -j16" >$FIFO_FILE
	make -j16 2>&1 >$BUILD_LOG || failed_cp "make -j16 failed"
	echo "build_stat:./distribute -b" >$FIFO_FILE
	./distribute -b || failed_cp "./distribute -b failed"
	
	DOWNLOAD_DIR=${SUB}
fi

mv $DOWNLOAD_DIR  ${TODAY_DOWNLOAD_DIR}/${SUB}_$(file_time $DOWNLOAD_DIR)
echo "build_stat:success @ ${SMB_DIR}/${TODAY_DATE}/${SUB}_$(file_time $DOWNLOAD_DIR)" >$FIFO_FILE
exit 0
