<?php
namespace Swoole\Controller;
use Think\Model;

use Think\Controller;

class somefunc{
	protected $serv;
}
// 守护 的php进程,禁止从web打开
class IndexController extends Controller {
	
	public function index(){
		if(!IS_CLI){
			die("404\n");
		}
		global $PC_TYPE;
		$PC_TYPE=array(
			'start' => 1, //程序准备中
			'wait'  => 2, //等待服务器发送消息
			'run'   => 3, //运行中
			'lock'  => 4, //服务器锁定
	 		'clilock'=>5, //客户端锁定
	 		'error' => 6, //未知错误
			'finish'=> 7, //编译完成
			'close' => 8, //运行被手动终止
			'failed'=> 9, //编译失败/设置路径失败/...
		);

		// 编译服务器的在线记录
		$table =new \swoole_table(1024);
		$table->column('ip',\swoole_table::TYPE_INT,4);
		$table->column('name',\swoole_table::TYPE_STRING,32); // 编译服务器 别名
		$table->column('stat',\swoole_table::TYPE_INT,2);
		$table->column('wait_id',\swoole_table::TYPE_INT,4);
		$table->column('wait_id_lock',\swoole_table::TYPE_INT,4); // 临时锁定的id
		$table->column('wait_id_time',\swoole_table::TYPE_INT,4); // 开始时间,需要检查wait_id 锁定是否超时异常
		$table->column('start_time',\swoole_table::TYPE_INT,4);
		$table->column('path',\swoole_table::TYPE_STRING,256);
		$table->column('more',\swoole_table::TYPE_STRING,256);
		$table->create();
		
		// 用户登陆列表
		$user =new \swoole_table(1024);
		$user->column('fd',\swoole_table::TYPE_INT,4);
		$user->column('user_id',\swoole_table::TYPE_INT,4);
		$user->column('name',\swoole_table::TYPE_STRING,32);
		$user->column('more',\swoole_table::TYPE_STRING,256);
		$user->create();
		// $lock = new swoole_lock(SWOOLE_MUTEX);
		// $serv->lock = $lock;
		$serv = new \swoole_websocket_server("0.0.0.0",9100);
// 		$serv = new \swoole_server("0.0.0.0",9100);
		$serv->set(array(
				'task_worker_num' => 5,
				
			));
		$serv->tab = $table;
		$serv->user = $user;
		$lock = new \swoole_lock(SWOOLE_MUTEX);
		$serv->lock = $lock;
		$serv->index = $this;
		$build_port = $serv->listen("0.0.0.0",9101,SWOOLE_SOCK_TCP);
		$build_port->set(array(
				'open_eof_check' => true,
				'open_eof_split' => true,
				'package_eof'    => "\n",
				));
		$web_port = $serv->listen("127.0.0.1",9102,SWOOLE_SOCK_TCP);
		$web_port->set(array('open_eof_check' => false, ));
		
		/* ====================================================
		 * 用于ajax 返回数据 
		 * ====================================================
		 */
		// http
		$serv->on('request', function ($request, $response) use ($serv){ 
			$path = $request->server['path_info'];
		    if ($path == "/build.js"){
		    	$response->header("Pragma","no-cache");
		    	$response->header('Content-Type', 'application/x-javascript');
		 		$data = array();
		 		$wait_ids = array();
		 		foreach ($serv->tab as $v){
		 			$v['ip'] = long2ip($v['ip']);
		 			$v['username'] = '';
		 			$data[] = $v;
		 			if($v['wait_id'] != 0) $wait_ids[] = $v['wait_id'];
		 		}
				if($wait_ids){
					$user = M('user')->field('hct_wait_build.id,hct_user.name')
					->join('hct_wait_build ON hct_user.id = hct_wait_build.user_id')
					->where('hct_wait_build.id IN ('.implode(',', $wait_ids).')')
					->select();
					$username =  turnArray($user,'id,name');
					foreach ($data as $k => $v){
						if($v['wait_id'] && isset($username[$v['wait_id']]))
							$data[$k]['username'] = $username[$v['wait_id']];
					}
				}
		    	$response->end("var build=".json_encode($data).";");
		    }else{
		    	$response->status(404);
		    	$response->header("Content-Type", "text/html; charset=utf-8");
		    	$response->end("not found");
		    }
		});
		$serv->on('open', function ($serv, $request) {
			echo "server: handshake success with fd:{$request->fd}\n";
			$serv->push($request->fd, "welcome to server");
			$serv->user->set($request->fd,array('fd'=> $request->fd));
		});
		// websocket
		$serv->on('message', function ($serv, $frame) {
			echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
			$serv->push($frame->fd, "<b>".date("h:i:s").":</b>server recv\n");
		});
		
		$serv->on('close', function ($serv, $fd) {
			echo "client {$fd} closed\n";
			$serv->user->del($fd);
		});
		
		$serv->on('start', function($serv) {
			echo "swoole is start\n";
			
		});
		/* ====================================================
		 * 死锁检查进程,暂未开启
		 * ====================================================
		 */
		$check_process = new \swoole_process(function ($worker) use ($serv) {
		// 创建一个定时器, 检查 在线编译服务 lock 锁定的时间是否超时
			swoole_set_process_name("php build check deadlock");
			//@TODO 检查编译服务器连接
			swoole_timer_tick(20000, function ($interval) use ($worker, $serv) {
				echo "#{$worker->pid} child process timer $interval\n"; // 如果worker中没有定时器，则会输出 process timer xxx
				
				foreach ($serv->tab as $k => $v) {
					$serv->send($k, "heartbeat\n");
				}
			});
			//@TODO 检查 死锁
			swoole_timer_tick(5000, function () use ($serv){
				foreach ($serv->tab as $k => $v) {
					if ($v['start_time'] != 0){
						// lock 锁定超过 3 秒,
						if (($v['start_time'] - time()) > 3 ){
							echo "[timer] fd:{$k} lock is timeout";
						}
					}
				}
				// 查询 数据库中lock超时的 项目
				$wait_build = M('wait_build')->where('stat = 2')->select();
				
			});
		}, false);
		//@TODO 检查进程 ,待验证
		//$serv->addprocess($check_process);
		/* 任务进程
		 *    1.邮件发送
		 * 
		 */
		$serv->on('task',function(\swoole_server $serv, $task_id, $from_id, $data){
			$json = json_decode($data,true);
			switch (key($json)){
				case "sendMail":
					//sendMail(\swoole_server $serv, $wait_id,$more,$stat)
					$serv->index->sendMail($serv,$json['sendMail']);
					break;
			}
		});
		
		$serv->on('finish',function (\swoole_server $serv, $task_id, $data){
			// 待写
		});
		
		$build_port->on('connect',function($serv,$fd){
			global $PC_TYPE;
		//	$serv->send($fd,"0.hello:$fd\n");
			$fdinfo = $serv->connection_info($fd);
			$ip = ip2long($fdinfo['remote_ip']);
			$serv->lock->lock();
			foreach ($serv->tab as $v){
				if($v[ip] == $ip){
					$serv->lock->unlock(); //解锁 !!!!  
					// sendwait 防止close 的时候,send 还在发送数据
					echo "build_client: already connected\n";
					$serv->sendwait($fd,"error:the client has been already connected\n");
					$serv->close($fd);
					return false;
				}
			}
			$serv->tab->set($fd,array(
					'ip'=> $ip,
					'stat'=>$PC_TYPE["start"], //状态为 start,在设置名字后变为 wait ,就可以接受项目编译了
					'name'=>'',
					'more'=>''));
			echo "0.connect:".$fd."\n";
			$serv->lock->unlock(); //解锁 !!!!
			return;			
		});
		
		/**
		 * 编译服务器的链接回调
		 */
		$build_port->on('receive',function($serv,$fd,$from_id,$data){
		//	echo "0.rece\n";

			$arv = explode(':',$data);
			if(count($arv) > 1){
				$arv[1] = str_replace(array("\n","\r"),"",$arv[1]);
			}
			echo "[build_client] FROM:$fd [{$arv[0]}]\n";
			global $PC_TYPE;
			switch($arv[0]){
				case "build_stat":
					$serv->tab->set($fd,array('more'=> substr($data,strpos($data,":") + 1)));
					break;
				// 向编译服务器发送的命令,编译服务器的返回结果	
				case "re_start_build":
					$serv->lock->lock();
			/*		re_start_build: return string (success/failed) ; wait_id ; $PC_TYPE key;成功 路径/失败 原因
				*/
					$arvv = explode(';', $arv[1]);
					$arvv[1] = (int)$arvv[1];
					$pcinfo = $serv->tab->get($fd);
					// succes 说明 编译服务器 开始编译,没有运行其它项目
					$wait_build = new Model('wait_build');
					if ($arvv[0] == "success"){
						// 判断下与编译服务器的东西对的上不.
// 						if($pcinfo['wait_id_lock'] == $arvv[1] ){
							$wait_build->where("`id`=".$pcinfo['wait_id_lock'])->setField("stat",0);
							#	echo "error: [mysql][wait_build] id={$arvv[1]},set stat=0 failed\n";
							echo "[build_client] FROM:$fd [{$arv[0]}]:success;$arvv[2]\n";
							$serv->tab->set($fd,array(
									'stat'=>$PC_TYPE['run'],
									'wait_id'=>$pcinfo['wait_id_lock'],
									'start_time'=>0,
									'path'=>$arvv[2],
									'more'=>'',
									'wait_id_lock'=>0,
									'wait_id_time'=>0
								));
// 						}else{
// 							echo "[re_start_build]: failed, 服务器与编译服务器信息不匹配\n";
// 						}
					}else{
						// 设置失败将 项目重新加入等待队列
						echo "[build_client] FROM:$fd [{$arv[0]}]:failed;{$arv[1]},wait_id:{$pcinfo['wait_id_lock']}\n";
						$wait_build->execute("UPDATE `hct_wait_build` SET `stat`=1,`sort`=`sort`+ 1,`start_time`=0 WHERE `id`= {$pcinfo['wait_id_lock']};");
						$serv->tab->set($fd,array(
								'stat' =>$PC_TYPE['error'],
								'wait_id_lock'=>0,
								'wait_id_time'=>0,
								));
					}
					$serv->lock->unlock();
					break;
				case "start_time":
					$serv->tab->set($fd,array('start_time'=>(int)$arv[1]));
					break;
				case "end_time":
					// end_time 代表编译服务器已经编译结束
					// end_time:时间戳;退出码 
					$serv->lock->lock();
					$arvv = explode(';',$arv[1]);
					if($arvv[1] == "0"){
						$serv->tab->set($fd,array('stat'=>$PC_TYPE['finish']));
					}else{
						$serv->tab->set($fd,array('stat'=>$PC_TYPE['failed']));
					}
					$pcinfo = $serv->tab->get($fd);
					$serv->lock->unlock();
					$pcinfo['end_time'] = $arvv[0];
//					echo "build over:";
// 					parray($pcinfo);echo "\n";
					$build = new Model('build');
					$wait_build = M('wait_build')->field('user_id')->find($pcinfo['wait_id']);
					$pcinfo['user_id'] = $wait_build['user_id'] ;
					if(!$build_id = $build->add($pcinfo)){
						echo "[sever][msyql][build] add data failed\n";
					}
					unset($build);
// 					break;
// 					echo "调用sendMail task\n";
					// 需要 task_worker_num  设置一个数量
					$json = json_encode(array('sendMail' => $pcinfo));
// 					echo $json."\n";
					$serv->task($json."\n");
					break;
				case "error_build":
					
					break;
				case "set_build":
					if ($arv[1] == "wait"){
						$serv->lock->lock();
						// 先保存服务器的编译服务器状态,
						// 清空编译服务器状态
						$serv->tab->set($fd,array(
								'stat' => $PC_TYPE['wait'],
								'start_time' => 0,
								'wait_id' => 0,
								'path' => '',
								'more' => '',
								'wait_id_lock'=>0,
								'wait_id_time'=>0,
							));
						// 编译完成后尝试下一次分配
						$serv->index->check_and_distribute_path($serv);
						$serv->lock->unlock();
					}else if ($arv[1] == "clilock"){
						$serv->tab->set($fd,array(
							'stat' => $PC_TYPE['clilock'],
							'start_time' => 0,
							'wait_id' => 0,
							'path' => '',
							'more' => '',
							'wait_id_lock'=>0,
							'wait_id_time'=>0,
						));
					}else if ($arv[1] == "run"){
						$serv->tab->set($fd,array('stat' => $PC_TYPE['run'],));
					}else if ($arv[1] == "close"){
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
								return; //跳出循环 和 switch
							}
						}
						$serv->tab->set($fd,array('name'=>$arv[1]));
						$serv->send($fd,"success\n");
						// 编译服务器命名成功,尝试分配项目 
// 						$serv->lock->lock();
// 						$serv->tab->set($fd,array("name"=>$arv[1],"stat"=>$PC_TYPE['wait']));
// 						$serv->index->check_and_distribute_path($serv);
// 						$serv->lock->unlock();
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
					echo "other:$data";
					break;
			}
		});
		/* ===============================================================
		 * 对 编译服务器 连接断开的处理
		 * ===============================================================
		 */
		// 需要对正在编译中的退出做处理
		$build_port->on('close',function($serv,$fd){
			global $PC_TYPE;
			$serv->lock->lock();
			$pcinfo = $serv->tab->get($fd);
			// 如果在链接时,验证失败,则没有加入tab 中.直接退出
			if(!$pcinfo){
				$serv->lock->unlock();				
				return;
			}
			/* 加锁,在 服务器接受到数据时向编译服务器发送数据,编译服务器反馈的过程中
			 * 1.发送数据成功,但连接断开, tab ,fd 处于 $PC_TYPE['lock']
			 *   wait_id 保存在$pcinfo['wait_id_lock'];
			 * 2.反馈成功,但在设置状态时,fd close,如果不加锁删掉 tab 中的 fd,
			 *   会出现什么情况? 数据库设置成功,set 但失败了,
			 *   所以 加锁,等待 设置成功,再删除 tab fd,还原 数据库设置
			 */ 
			$ip = long2ip($pcinfo['ip']);
			if ($pcinfo['stat'] == $PC_TYPE['run']){
				echo "[build_client] exit error, it's running,wait_id:{$pcinfo['wait_id']},ip:{$ip},name:{$pcinfo['name']}\n";
				$ret = true;
				$wait_id = $pcinfo['wait_id'];
			}else if($pcinfo['stat'] == $PC_TYPE['lock']){
				echo "[build_client] exit error, it's lock,wait_id:{$pcinfo['wait_id_lock']},ip:{$ip},name:{$pcinfo['name']}\n";
				$ret = true;
				$wait_id = $pcinfo['wait_id_lock'];
			}else{
				$ret =false;
			}
			if($serv->tab->del($fd)){
				echo "[build_client] {$pcinfo['name']} exit success;fd:$fd";
			}else{
				echo "[build_client] {$pcinfo['name']} exit failed;fd:$fd";
			}
			$serv->lock->unlock();
			// 如果断开的时候,有项目在编译,尝试重新分配
			if($ret){
				$serv->lock->lock();
				M()->execute("UPDATE `hct_wait_build` SET `stat`=1,`sort`=`sort`+ 1,`start_time`=0 WHERE `id`= $wait_id");
				$serv->index->check_and_distribute_path($serv);
				$serv->lock->unlock();
			}
		});

		/*
		 * 与web服务器通讯
		 */
		$web_port->on('receive',function($serv,$fd,$from_id,$data){
			$arv = explode(':',$data);
			if(count($arv) > 1){
				$arv[1] = str_replace(array("\n","\r"),"",$arv[1]);
			}
			global $PC_TYPE;
			switch($arv[0]){
				case "add_build\n":
					$serv->lock->lock();
					$serv->index->check_and_distribute_path($serv);
					$serv->lock->unlock();
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
					$serv->send($fd,json_encode($data)."\n");
					break;
				case "count\n":
					$serv->send($fd,$serv->tab->count()."<<<\n");
					break;
				default:
				//	$serv->send($fd,"0.server:".$data);
			} 
		});
		$serv->start();
		echo "ok";
    }
  	// 分配项目到编译机器 
    public function check_and_distribute_path (\swoole_server $serv ){
    	global $PC_TYPE;
    	$wait_pc = array();
    	foreach($serv->tab as $k=>$v){
    		switch ($v['stat']){
    			case $PC_TYPE['wait']:  // 暂时只将wait 状态的加入等待编译
//     			case $PC_TYPE['close']:
//     			case $PC_TYPE['failed']:
    				$wait_pc[] = $k;
    				break;
    		}
    	}
    	//     	parray($wait_pc);echo "\n";
    	switch (count($wait_pc)){
    		case 0:
    			echo "[server] build_client:not free\n";
    			return;  // 没有 空闲机器直接返回 !!!
    		case 1:
    			// 只有一台直接 返回
    			$cur_pc = $wait_pc[0];
    			break;
    		default:
    			// 多台 随机选择一台
    			$cur_pc = array_rand($wait_pc);
    			break;
    	}
    	// 有空余机器则向机器发送编译
    	echo "[server] build_client:$cur_pc\n";
    
    	// 已加锁,待验证
    	// 验证ok, connect,receive.close 都在同一进程,线程不同,lock一定是有效,也验证了
    	$time = time();
    	$mysqli =new \mysqli(C('DB_HOST'),C('DB_USER'),C("DB_PWD"),"hct_manage");
    	if (mysqli_connect_errno()) {
    		//检查连接错误
	    	printf("[server] [mysqli] error: %s \n", mysqli_connect_error());
	    	return;
    	}
    	$mysqli->query("set names 'utf8'");
    	$sql = "set @update_id:=0;
    		update `hct_wait_build` set
	    	`stat` = 2,
	    	`start_time` = $time,
	  	  	`id` = (select @update_id:=`id`)
    		where `stat` = 1 order by `sort` desc,`id` limit 1;
    		select `id`,`path` from `hct_wait_build` where `id` = @update_id ;";
    	if ($mysqli->multi_query($sql)) {
	    	do {
	    	//while ($row = $result->fetch_row()) {  }
		    	if ($result = $mysqli->store_result()) {
		    		$data = $result->fetch_row() ;
		    		$result->close();
		    	}
	    	} while ($mysqli->next_result());
    	}
//     	parray($data);echo "\n";
    	if($data[0] == 0){
    	// 说明没有排队的数据
	    	echo "[server] hct_wait_build no data\n";
	    	return;
    	}
    	// 使等待 队列中的项目 锁定
    	// echo "web>>:".$str;
    	$str = "start_build:{$data['0']};{$data['1']}\n";
    	if( $serv->send((int)$cur_pc,$str)){
    		echo "[server] send:{$cur_pc} [start_build]  OK. {$data['0']};{$data['1']}\n";
    		$serv->tab->set($cur_pc,['stat'=>$PC_TYPE['lock'],'wait_id_lock'=>$data[0],'wait_id_time'=>$time]);
    	}else{
    		echo "FATAL: [server] send:{$cur_pc} [start_build] send failed !!!\n";
    		$mysqli->query("update `hct_wait_build` set
	    	`stat` = 1,
	    	`start_time` = 0,
	    	`sort` = `sort` + 1
    		where `id` = ".$data[0]." limit 1;");
    	}
    	$mysqli->close();
    	unset($mysqli);
    	unset($data);
    }
    
    public function sendMail(\swoole_server $serv,$data){
    	global $PC_TYPE;
    	$userdata = M('user')->field('name,email,email_passwd')->find($data['user_id']);
    	if($userdata){
    		$mail = new \Common\MyClass\SendMail();
    		if(!$mail->setUser($userdata['email'],$userdata['email_passwd'],$userdata['name'])){
    			echo "[sendMail]账号email信息不完整";
    			return;
    		}
    		$mail->setReceiver($userdata['email']);
    		$getstat = $data['stat'] == $PC_TYPE['finish'] ? '编译完成' :'<font color="red">编译失败</font>' ;
    		$gettitle = $data['stat'] == $PC_TYPE['finish'] ? '路径' : 'LOG';
 
    		$body = "<table cellspacing='0' cols='2' border='0'>
	<colgroup width='108'></colgroup>
	<colgroup width='814'></colgroup>
	<tbody><tr>
		<td style='border:1px solid #000000;' colspan='2' height='66' align='CENTER' valign='MIDDLE'><b><font size='4'>项目编译详情</font></b></td>
		</tr>
	<tr>
		<td style='border:1px solid #000000;' height='76' align='LEFT' valign='MIDDLE'><font size='3'>项目</font></td>
		<td style='border:1px solid #000000;' align='LEFT' valign='MIDDLE'><font size='3'>{$data['path']}</font></td>
	</tr>
	<tr>
		<td style='border:1px solid #000000;' height='28' align='LEFT' valign='MIDDLE'><font size='3'>状态</font></td>
		<td style='border:1px solid #000000;' align='LEFT' valign='MIDDLE'><font size='3'>{$getstat}</font></td>
	</tr>
	<tr>
		<td style='border:1px solid #000000;' height='28' align='LEFT' valign='MIDDLE'><font size='3'>编译服务器</font></td>
		<td style='border:1px solid #000000;' align='LEFT' valign='MIDDLE'><font size='3'>{$data['name']}</font></td>
	</tr>
	<tr>
		<td style='border:1px solid #000000;' height='28' align='LEFT' valign='MIDDLE'><font size='3'>编译服务器 IP</font></td>
		<td style='border:1px solid #000000;' align='LEFT' valign='MIDDLE'><font size='3'>".long2ip($data['ip'])."</font></td>
	</tr>
	<tr>
		<td style='border:1px solid #000000;' height='28' align='LEFT' valign='MIDDLE'><font size='3'>开始时间</font></td>
		<td style='border:1px solid #000000;' align='LEFT' valign='MIDDLE'><font size='3'>".date('Y-m-d H:i:s',$data['start_time'])."</font></td>
	</tr>
	<tr>
		<td style='border:1px solid #000000;' height='28' align='LEFT' valign='MIDDLE'><font size='3'>结束时间</font></td>
		<td style='border:1px solid #000000;' align='LEFT' valign='MIDDLE'><font size='3'>".date('Y-m-d H:i:s',$data['end_time'])."</font></td>
	</tr>
	<tr>
		<td style='border:1px solid #000000;' height='76' align='LEFT' valign='MIDDLE'><font size='3'>$gettitle</font></td>
		<td style='border:1px solid #000000;' align='LEFT' valign='MIDDLE'><font size='3'>{$data['more']}</font></td>
	</tr>
</tbody></table>";
    		//     	parray($body);
    		$mail->setMail("[编译完成] {$data['path']}", $body);
    		if($mail->sendMail()){
    			echo "[sendMail] OK. user:{$userdata['name']}\n";
    		}else{
    			echo "[sendMail] failed. user:{$userdata['name']} ".$mail->getError()."\n";
    		}
    	}else{
    		echo "[sendMail][mysql] not found user from hct_wait_build (id = {$wait_id})\n";
    		return;
    	}
    }
}
