<?php

$table =new swoole_table(1024*1024);
$table->column('fd',swoole_table::TYPE_INT,4);
$table->column('stat',swoole_table::TYPE_INT,2);
$table->column('name',swoole_table::TYPE_STRING,32);
$table->column('more',swoole_table::TYPE_STRING,256);
$table->create();

$serv = new swoole_websocket_server("0.0.0.0", 9501);  
$port1 = $serv->listen("127.0.0.1", 9502, SWOOLE_SOCK_TCP); 
$port1->set(array(
	'open_eof_check'           => false,
	));
$lock = new swoole_lock(SWOOLE_MUTEX);  
$serv->lock = $lock;
$serv->tab = $table;

$serv->on('request', function ($request, $response) { 
	$path = $request->server['path_info'];
    if ($path == "/build.js"){
    	$response->header("Pragma","no-cache");
    	$response->header('Content-Type', 'application/x-javascript');
    	$response->end("var hello='world';"); 
    }else if($path == "/"){
    	$response->header("Content-Type", "text/html; charset=utf-8"); 
    	$response->end("<pre>"
    		.print_r($request->get,true)."\n"
    		.print_r($request->header,true)."\n"
    		.print_r($request->server,true)."</pre>"); 
    }else{
    	$response->status(404);
    	$response->header("Content-Type", "text/html; charset=utf-8"); 
    	$response->end("not found"); 
    }

}); 
  
$serv->on('open', function (swoole_websocket_server $serv, $request) { 
    echo "server: handshake success with fd:{$request->fd}\n"; 
    $serv->push($request->fd, "welcome to server"); 
	$serv->tab->set($request->fd,array('fd'=> $request->fd));  
}); 
  
$serv->on('message', function (swoole_websocket_server $serv, $frame) { 
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n"; 
    $serv->push($frame->fd, "<b>".date("h:i:sa").":</b>server recv\n"); 
}); 
  
$serv->on('close', function ($serv, $fd) { 
    echo "client {$fd} closed\n"; 
    $serv->tab->del($fd);
}); 

$port1->on('connect', function ($serv, $fd){ 
    echo "Client:Connect.\n"; 
}); 
  
$port1->on('receive', function ($serv, $fd, $from_id, $data) {
	$arv = explode(':',$data);
	if(count($arv) > 1){
		$arv[1] = str_replace(array("\n","\r"),"",$arv[1]);
	} 
	switch($arv[0]){
		case "send":
			foreach ($serv->tab as $v){
				$serv->push($v['fd'],$arv[1]);
			}
			break;
		case "allpc\n":
			$data = array();
			foreach ($serv->tab as $v){
				$data[] = $v;
			}
			$serv->send($fd,print_r($data,true)."\n");
			break;
		case "close":
			$serv->close((int)$arv[1]);
			break;
	}
}); 
  
$port1->on('close', function ($serv, $fd) { 
    echo "Client: Close.\n"; 
}); 



$serv->start();  
