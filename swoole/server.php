<?php

$PC_TYPE=array(
	'start' => 1, //程序准备中
	'wait'  => 2, //等待服务器发送消息
	'run'   => 3, //运行中
	'pull'  => 4, //拉代码中
	'build' => 5, //编译中
	'copy'  => 6, //打包复制中
	'finish'=> 7, //任务完成中
	'close' => 8, //运行被手动终止
	'failed'=> 9, //编译失败/设置路径失败/...
);

//print_r($PC_TYPE);
$table =new swoole_table(1024*1024*2);
$table->column('fd',swoole_table::TYPE_INT,4);
$table->column('stat',swoole_table::TYPE_INT,2);
$table->column('name',swoole_table::TYPE_STRING,32);
$table->column('path',swoole_table::TYPE_STRING,256);
$table->column('more',swoole_table::TYPE_STRING,256);
$table->create();
// $lock = new swoole_lock(SWOOLE_MUTEX);
// $serv->lock = $lock;
//$serv = new swoole_http_server("0.0.0.0",9000);


$serv = new swoole_server("0.0.0.0",9100);
$serv->tab = $table;

//$serv->allpc = array();
$port1 = $serv->listen("0.0.0.0",9101,SWOOLE_SOCK_TCP);
$port2 = $serv->listen("127.0.0.1",9102,SWOOLE_SOCK_TCP);

// $serv->on('request', function ($request, $response) { 
//     var_dump($request->get, $request->post); 
//     $response->header("Content-Type", "text/html; charset=utf-8"); 
//     global $serv;
// 	$data = array();
// 	foreach ($serv->tab as $v){
// 		$data[] = $v;
// 	}
//     $response->end(print_r($cli,true)); 
// }); 

$serv->on('receive',function($serv,$fd,$from_id,$data){
	$serv->send($fd,"2.server:".$data); 
});


$port1->on('connect',function($serv,$fd){
	global $PC_TYPE;
//	$serv->send($fd,"0.hello:$fd\n");
	$serv->tab->set($fd,array('fd'=>$fd,'stat'=>$PC_TYPE["start"],'name'=>'','more'=>''));
	echo "0.connect:".$fd."\n";
});
/**
 * 编译服务器的链接回调
 */
$port1->on('receive',function($serv,$fd,$from_id,$data){
//	echo "0.rece\n";

	$arv = explode(':',$data);
	if(count($arv) > 1){
		$arv[1] = str_replace(array("\n","\r"),"",$arv[1]);
	}
	//**** 加锁
// 	$serv->lock->lock();	
	$pcinfo = $serv->tab->get($fd);
	echo "recv:arv[0]:{$arv[0]}\n";
	global $PC_TYPE;
	switch($arv[0]){
		case "re_start_build":
	/*		re_start_build: return string (success/failed) ; fd ; 成功 路径/失败 原因
		*/
			$arvv = explode(';', $arv[1]);
			$arvv[1] = (int)$arvv[1];
// 			if($serv->exist($arvv[0])){
// 				echo "error:{$pcinfo['name']}({$fd}) reply web({$arvv[0]}) failed,web not exist\n";
// 				break;
// 			}
			if ($arvv[0] == "success"){
				$serv->tab->set($fd,array('stat'=>$PC_TYPE['run'],'path'=>$arvv[2]));
				$ret = $serv->send($arvv[1],"success:{$arvv[2]}\n");
			}else{
				$serv->tab->set($fd,array('stat'=>$PC_TYPE['failed'],'path'=>$arvv[2]));
				$ret = $serv->send($arvv[1],"failed:{$arvv[2]}\n");
			}
			if(!$ret){
				echo "error:{$pcinfo['name']}({$fd}) reply web({$arvv[0]}) failed\n";
			}
			break;
		case "name":
			echo "name:{$arv[1]}\n";
			if(strlen($arv[1])<3){
				$serv->send($fd,"failed:name is too short, at least 3 letters.\n");
			}else{
				foreach ($serv->tab as $v){
					if ($v['name'] == $arv[1] ){
						$serv->send($fd,"failed:name already exists, please change the name.\n");
						break 2; //跳出循环 和 switch
					}
				}
				$serv->tab->set($fd,array("name"=>$arv[1],"stat"=>$PC_TYPE['wait']));
				$serv->send($fd,"success\n");
			}
			break;
		case "allpc\n":
			//	global $allpc;
			$data = array();
			foreach ($serv->tab as $v){
				$data[] = $v;
			}
			$serv->send($fd,print_r($data,true)."\n");
			break;
		case "info\n":
			$cli = $serv->tab->get($fd);
			$serv->send($fd,print_r($cli,true)."\n");
			break;
		case "cli\n":
			$serv->send($fd,print_r($serv->connection_info($fd),true)."\n");
			break;
		case "list\n":
			$serv->send($fd,print_r($serv->connection_list(0,100),true)."\n");
			break;
		case "count\n":
			$serv->send($fd,$serv->tab->count()."<<<\n");
			break;
		case "close\n":
			$serv->close($fd);
			break;
		default:
			//$serv->send($fd,"0.server:".$data);
			break;
	}
	//**** 解锁
// 	$serv->lock->unlock();
});

$port1->on('close',function($serv,$fd){
	$serv->tab->del($fd);
	echo "0.close:".$fd."\n";	
});


$port2->on('connect',function($serv,$fd){
//	$serv->send($fd,"2.hello:$fd\n");
	echo "2.connect:".$fd."\n";	
});
/**
 * 与web服务器通讯
 */
$port2->on('receive',function($serv,$fd,$from_id,$data){
	$arv = explode(':',$data);
	if(count($arv) > 1){
		$arv[1] = str_replace(array("\n","\r"),"",$arv[1]);
	}
	switch($arv[0]){
		case "start_build":
			$arvv = explode(';', $arv[1]);
			if(!($pcinfo = $serv->tab->get($arvv[0]))){
				$serv->send($fd,"error:not exist");
				break;
			}
			$serv->send($pcinfo['fd'],"start_build:{$fd};{$arvv[1]}\n");
			$pcinfomore = $serv->connection_info($pcinfo['fd']);
//			echo  "server_fd:".$pcinfomore['server_fd'];
//			$str = socket_read((int)$pcinfomore['server_fd'],256);
//			$serv->send($fd,"serv:{$str}\n");
			break;
		case "pcinfo":
			if(!($pcinfo = $serv->tab->get($arv[1]))){
				$serv->send($fd,"error:not exist\n");
				break;
			}
			$serv->send($fd,print_r($serv->connection_info($pcinfo['fd']),true)."\n");
			break;
		case "allpc\n":
			//	global $allpc;
			$data = array();
			foreach ($serv->tab as $v){
				$data[] = $v;
			}
			$serv->send($fd,print_r($data,true)."\n");
			break;
		case "info\n":
			$cli = $serv->tab->get($fd);
			$serv->send($fd,print_r($cli,true)."\n");
			break;
		case "cli\n":
			$serv->send($fd,print_r($serv->connection_info($fd),true)."\n");
			break;
		case "list\n":
			$serv->send($fd,print_r($serv->connection_list(0,100),true)."\n");
			break;
		case "count\n":
			$serv->send($fd,$serv->tab->count()."<<<\n");
			break;
		default:
		//	$serv->send($fd,"0.server:".$data);
	} 
});

$port2->on('close',function($serv,$fd){
	echo "2.close:".$fd."\n";
});
 
$serv->start();
unset($lock);
echo "ok";
