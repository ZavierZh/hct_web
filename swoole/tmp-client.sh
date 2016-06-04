#!/bin/bash

function recv_build(){
	echo ">> recv pid:$run_pid"
	wait $run_pid
	ret=$?
	echo ">> father ret: $ret"
	run_pid=0
	cur_path=
	if [ "$BUILD_OVER_WAIT" != y ];then
		echo "set_build:wait" # >$FIFO_FILE
	else
		echo "set_build:clilock" # >$FIFO_FILE
	fi
}

trap "recv_build"  SIGUSR1

run_pid=0
run_uid=$$
cur_path=

echo ">>> $run_uid"
while read str ;do
	arv0=${str%%:*}
	arv1=${str#*:}
	echo "sh_recv:$str" >&2
	case "$arv0" in
		"exit")
			[ $run_pid != 0 ] && kill -9 $run_pid
			echo "exit" 
			break
			;;
		"s")
			fd=${arv1%%;*}
			path=${arv1#*;}
			if [ $run_pid != 0 ] ;then
				echo "re_start_build:failed;${fd};PC is compiling" 
				continue
			else
				(
					echo "<< child pid:$$"
					echo "<< start_time:`date +%s`"
					# 编译脚本运行的地方
					./echo.sh 1
					ret=$?
					echo "<< child ret: $ret"
					kill -10 $run_uid
					echo "<< end_time:`date +%s`" 
					exit $ret
				) >&2  &
				run_pid=$!
				cur_path="$arv1"

				echo "pid:$run_pid" >&2
			fi
			;;
		"stop")
			if [ $run_pid != 0 ];then 
				kill -9 $run_pid
				run_pid=0
				cur_path=
				echo "stop:success" >&2
			else
				echo "stop:no run" >&2
			fi
			;;
		"pid")
			echo "pid:$run_pid" >&2
			;;
		"uid")
			echo "uid:$run_uid" >&2
			;;
		*)
			echo "$str"
			;;
	esac

done 
# kill时应该考虑子进程的循环杀死
[ $run_pid != 0  ] &&  kill -9 $run_pid

