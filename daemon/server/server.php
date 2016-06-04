<?php
$serv = new swoole_server("127.0.0.1", 9501);

$all_cli=array();
//监听连接进入事件
$serv->on('connect', function ($serv, $fd) {
	echo "Client: Connect:$fd.\n";
	print_r( $serv->connection_info($fd));
	$all_cli[$fd] = $serv->connection_info($fd);
// 	print_r($serv);
});
$serv->on('onConnect', function ($serv, $fd) {
	echo "Client: Connect:$fd.\n";
	print_r( $serv->connection_info($fd));
	$all_cli[$fd] = $serv->connection_info($fd);
	// 	print_r($serv);
});

//监听数据发送事件
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
	echo "rece:$fd  rom_id:".$from_id."\n";
	if ($data == "exit\n"){
		echo "Bye";
		$serv->shutdown();
		return;
	}else if($data == "list\n"){
		$serv->send($fd,print_r($serv->connection_list(0,100),true));
	}else if($data == "stats\n"){
		$serv->send($fd,print_r($serv->stats(),true));
	}else
		$serv->send($fd, "Server: ".$data);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
	echo "Client: Close:$fd.\n";
	
});

//启动服务器
$serv->start();
