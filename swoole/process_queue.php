<?php
    $process = new swoole_process(function (){echo "";} ,false, false);
    //使用消息队列
//     $process->useQueue();
//     $pid = $process->start();
//     $workers[$pid] = $process;
    //echo "Master: new worker, PID=".$pid."\n";
