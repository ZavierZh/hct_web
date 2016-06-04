<?php

$client = new swoole_client(SWOOLE_SOCK_TCP); 
  
//连接到服务器 
if (!$client->connect('127.0.0.1', 9000, 0.5)){ 
    die("connect failed."); 
} 
//向服务器发送数据 
if (!$client->send("hello world")){ 
    die("send failed."); 
} 
//从服务器接收数据 
$data = $client->recv(); 
if (!$data) {
    die("recv failed."); 
}
$sock = fopen("php://fd/".$client->sock,'r');


$fp = fopen("php://stdin", "r"); 

$fw = fopen("/home/linux/project/server/swoole/data","w");
echo "----\n";
$input = array($fp,$sock);

while (count($input)) {  
    $read=$input;  
    stream_select($read, $w=null, $e=null,0);  
    foreach ($read as $r) {  
        $id=array_search($r, $input);
	if ($fp == $input[$id]) {
		$str = fgets($fp);
	 	if ($str == "exit\n"){
			fclose($fp);
			$client->close();
			die;
		}
		$client->send($str);  
	}else{
		$str = $client->recv();
		echo $str;
		fwrite($fw,$str);
	}
    } 
}
echo "xxx";
while ($str = fgets($fp)){
	if ($str == "exit\n"){
		fclose($fp);
		$client->close();
		die;
	}
	$client->send($str);
}

