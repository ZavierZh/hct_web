#!/bin/bash

RUN_DIR=`cd $(dirname $0);pwd`
cd $RUN_DIR
export FIFO_FILE=$RUN_DIR/data
[ "$1" == "-t" ] && export t=y
if [ ! -f build_client.config ];then
	echo not found build_client.config
	exit 1
fi
if [ ! -f build.sh ];then
	echo not found build.sh
	exit 1
fi
if [ ! -f middleware ];then
	echo not found file middleware
	exit 1
fi

DAEMON="y"
source ./build_client.config

if [ "$BUILD_LOG" == '' ];then
	echo BUILD_LOG is not set
	exit 1
fi
if [ "$SHARE_DIR" == '' ];then
	echo SHARE_DIR is not set
	exit 1
fi
if [ "$SERVER_PORT" == '' ];then
	echo SERVER_PORT is not set
	exit 1
fi
if [ "$SERVER_IP" == '' ];then
	echo SERVER_IP is not set
	exit 1
fi
if [ "$BUILD_PC_NAME" == '' ];then
	echo BUILD_PC_NAME is not set
	exit 1
fi
if [ "$REPO_IP" == '' ];then
	echo REPO_IP is not set
	exit 1
fi

if [ ! -d "$BUILD_DIR" ];then
	echo not found dir: $BUILD_DIR
	exit 1
fi
if [ ! -d "$SHARE_DIR" ];then
	echo not found dir: $SHARE_DIR
	exit 1
fi

if [ ! -p "$FIFO_FILE" ] ;then
	mkfifo $FIFO_FILE
	if [ $? != 0 ];then
		echo "error:Cannot create fifo '$FIFO_FILE'"
		exit 1
	fi
fi

if [ "$SERVER_IP" == "" ] || [ "$SERVER_PORT" == "" ] || [ "$BUILD_PC_NAME" == "" ];then
	echo "error:./build_client.config  error"
	exit 1
fi


if [ ! -p "$FIFO_FILE" ] ;then
	mkfifo $FIFO_FILE
	if [ $? != 0 ];then
		echo "error:Cannot create fifo '$FIFO_FILE'"
		exit 1
	fi
fi


if [ "$SERVER_IP" == "" ] || [ "$SERVER_PORT" == "" ] || [ "$BUILD_PC_NAME" == "" ];then
	echo "error:./build_client.config  error"
	exit 1
fi
while true;do
 ./middleware | \
(
function build(){
	path="$1"
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
			return 1
		fi
	else
		echo "build_stat:项目设置错误" >$FIFO_FILE
		return 1
	fi
	run=1
	local i=0
	while ((i++ <= 10));do
		echo "i:$i" 
		case $i in
			0)
				echo "build_stat:update $SUB" >$FIFO_FILE
				;;
			3)
				echo "build_stat:build $SUB" >$FIFO_FILE
				;;
			9)
				echo "build_stat:tar $SUB" >$FIFO_FILE
				;;
		esac
		sleep 2
	done

	LOCAL_IP=`ifconfig | head -n2 |tail -n1 |awk '{split($2,a,":");print a[2];}'`
	echo "build_stat:success @ smb://$LOCAL_IP/download/$(date +%F)/${SUB}_$(date +%Y%m%d_%H%M)" >$FIFO_FILE
	run=0
	return 0
}

function recv_build(){
	wait $run_pid # 短暂的阻塞
	ret=$?
	run_pid=0
	cur_path=
	if [ "$BUILD_OVER_WAIT" != y ];then
		echo "set_build:wait"  >$FIFO_FILE
	else
		echo "set_build:clilock"  >$FIFO_FILE
	fi
}

trap "recv_build"  SIGUSR1

run_pid=0
run_uid=$BASHPID
cur_path=

echo "run_uid=$run_uid" > run.log
echo ">>> $BASHPID"

DAEMON="n"
# source ./build_client.config
#read str
#echo "****"  $str
echo ">>> $$" 

while read str ;do
	arv0=${str%%:*}
	arv1=${str#*:}
	echo "sh_recv:$str" >&2
	case "$arv0" in
		"manage")
			case $arv1 in
				"lock")
					BUILD_OVER_WAIT=y
					[ "$run_pid" == 0 ] && echo "set_build:clilock"
					;;
				"unlock")
					## 没有编译就发送给服务器,可以接受编译
					BUILD_OVER_WAIT=n
					[ "$run_pid" == 0 ] && echo "set_build:wait"
					;;
			esac
			;;
		"exit")
			[ "$run_pid" != 0 ] && kill -9 $run_pid
			echo "exit" 
			break
			;;
		"start_build")
			wait_id=${arv1%%;*}
			path=${arv1#*;}
			if [ "$run_pid" != 0 ] ;then
				echo "re_start_build:failed;${wait_id};run;PC is compiling" 
				echo "set_build:run"
				continue
			elif [ "$BUILD_OVER_WAIT" == y ];then
				echo "re_start_build:failed;${wait_id};clilock;PC is locked by the user" 
				echo  "set_build:clilock"
				continue
			else
				(
					# function e(){
					# 	echo xxx;
					# }
					# trap "e"  SIGUSR1
					echo "start_time:`date +%s`" > $FIFO_FILE
					# 编译脚本运行的地方
					if [ "$t" == y ];then
						build "$path"
					else
						./build.sh "$path"
					fi
					ret=$?
					kill -10 $run_uid
					echo "end_time:$(date +%s);$ret" >$FIFO_FILE
					exit $?
				) >&2  &
				run_pid=$!
				cur_path="$arv1"
				echo "re_start_build:success;${arv1}"
				echo "pid:$run_pid" >&2
			fi
			;;
		"stop")
			if [ "$run_pid" != 0 ];then 
				kill -9 $run_pid
				run_pid=0
				cur_path=
				echo "stop:success" >&2
			else
				echo "stop:no run" >&2
			fi
			;;
		"path")
			echo "ptah:$cur_path" >&2
			;;
		"start-run")
			echo "$SERVER_IP" >$FIFO_FILE 
			sleep 0.1  # 不延迟有几率会多个echo 一次性发出去,造成配置失败
			echo "$SERVER_PORT" >$FIFO_FILE 
			sleep 0.1 # 特别 是名字时,相当大几率会碰到一起和port 一起发出去
			echo "name:$BUILD_PC_NAME"
			if [ "$BUILD_OVER_WAIT" != y ];then
				echo  "set_build:wait"
			else
				echo "set_build:clilock"
			fi
			;;
		"run-kill")
			if [ "$run_pid" != 0 ];then
				kill -10 $run_pid
			fi
			;;
		"pid")
			echo "pid:$run_pid" >&2
			;;
		"uid")
			echo "uid:$run_uid" >&2
			;;
		*)
			echo "$str" >&2
			;;
	esac

	# echo -n ">:" >&2
done > $FIFO_FILE 
# kill时应该考虑子进程的循环杀死
[ "$run_pid" != 0  ] &&  kill -9 $run_pid
)
exit_no=$?
echo "exit:$?"
if [ "$exit_no" != 0 ];then
	sleep 5
else
	break
fi
wait

done
