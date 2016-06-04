<?php
namespace Home\Controller;

use Admin\Model\confirmTaskModel;

use Common\MyClass\SendMail;

use Home\Controller\AuthCheckController;

use Admin\Model\sectorModel;

use Admin\Model\faepcModel;

use Admin\Model\taskModel;

use Admin\Model\taskTypeModel;

use Admin\Model\userModel;

// use Think\Dispatcher;
class TaskController extends AuthCheckController{
    public function index(){
    	$this->title = "任务列表";
    	$this->url = U("addTask");
    	$this->pageSize =  (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:20;
    	$this->pageCurrent = (isset($_GET['pgc']) && $_GET['pgc']>=0) ? (int)$_GET['pgc']:1;
    	
    	$task = M('task');
    	$this->total_counts = $task->where('isdel=0')->count();
    	 
    	$args = array('id','level','type','color');
    	$tasktype = new taskTypeModel();
    	$tasktypedata = $tasktype->getType();
    	$this->status = $tasktypedata[0];
    	$this->type = $tasktypedata;
		$this->display();
    }
    public function TaskList(){
    	$pgt = (isset($_GET['pgt']) && $_GET['pgt']>=0) ? (int)$_GET['pgt']:0;
    	$pgs = (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:40;
    	
    	$task = M('task');
    	$this->total_counts = $task->where('`isdel`=0')->count();
    	 
    	$this->data = $task->field('base,bugfree_id,comment',true)
    	->where('isdel=0')->order("find_in_set(`status`,'14,16,15')>0 DESC,find_in_set(`status`,'14,16,15') ASC,`reply_time` DESC,`time` DESC")
    	->limit($pgt,$pgs)->select();
    	// 这个函数必须是5.5或大于这个版本的才能用
    	$id = array_column($this->data,'id');
    	 
    	$task_tasktype = M('task_tasktype')->where(array('id'=>array('IN',$id)))->select();
    	$args = array('id','level','type','color');
    	$tasktype = new taskTypeModel();
    	$tasktypedata = $tasktype->getType();
    	$this->status = $tasktypedata[0];
    	
    	$this->type1 = turnMulArray($tasktypedata[0],$args);
    	$type = turnMulArray($tasktype->selectData(),$args);
    	 
    	$type_arr= array();
    	foreach ($task_tasktype as $v){
    		$type_arr[$v['task_id']][$v['tasktype_id']] = $type[$v['tasktype_id']];
    	}
    	 
    	$user = new userModel();
    	$this->user = turnArray($user->selectData(),'id,name');
    	 
    	$this->type_arr = $type_arr;
    	$this->display();
    }
    
    public function showTask(){
//     	parray($_SESSION['access']);
//     	if(isAuth('editTask')){
//     		echo "true";
//     	}else echo "false";
//     	die;
    	
    	if (!isset($_GET['id']) || $_GET['id'] <=0 ){
    		$this->error('页面不存在');
    		return;
    	}
    	if(!$task=M('task')->find((int)$_GET['id'])){
    		$this->error('任务不存在');
    		return;
    	}
    	$this->user = M('user')->find($task['user_id']);
    	$sector = new sectorModel();
    	$this->sector = turnArray($sector->selectData(),'id,sector');
    	if($task['faeenv_id']){
    		$faeenv = M('faeenv')->find($task['faeenv_id']);
    		$faepc = new faepcModel();
    		$faepc_name = turnArray($faepc->selectData(),'id,faepc');
    		$this->faeenv = $faepc_name[$faeenv['faepc_id']].":&nbsp;&nbsp;".$faeenv['path'];
    	}else{
    		$this->faeenv = "无";
    	}
    		
    	$this->tasktype = M('task_tasktype')->where('`task_id`='.$task['id'])->select();
    	 
    	$tasktypedata = new taskTypeModel();
    	$type = $tasktypedata->selectData();
    
    	$this->type = turnMulArray($type,'id,type');
    	$this->faedebug = M('faedebug')->where('`task_id`='.$task['id'])->select();
    	$this->task = $task;
    	$this->task_list_url=U('index');
    	$this->display();
    	 
    }
    
    public function addTask(){
    	$this->checkAuth(U('index'),true);
    	$this->title = "发布任务";
    	$this->url = U("addTaskHandle");
    
    	$tasktype = new taskTypeModel();
    	$tasktypedata = $tasktype->getType();
    
    	$this->type1=$tasktypedata[0];
    	$this->type2=$tasktypedata[1];
    	$this->type3=$tasktypedata[2];
    	$this->type4=$tasktypedata[3];
    	$user = new userModel();
    	$this->display();
    }
    
    public function addTaskHandle(){
    	    	parray($_POST);die;
    	$this->checkAuth(U('index'),true,'addTask');
    	 
    	// 去掉 script ,防止注入
    	$content = preg_replace("/<\s*[\/]?\s*s[\n\r]*c[\n\r]*r[\n\r]*i[\n\r]*p[\n\r]*t[\n\r]*[^>]*>/", '',$_POST['content']);
    	unset( $_POST['content']);
    	$data = I('post.');
    	$data['content'] = $content;
    	$data['time'] = time();
    	$data['user_id'] = $_SESSION['id'];
    	$data['status'] = 14;
//     	parray($data);die;
    	$task = new taskModel();
    	if(!$task->create($data)){
    		$this->error($task->getError());
    		return;
    	}
    	//     	'INSERT INTO hct_task_tasktype (task_id,tasktype_id) SELECT a.id,b.id FROM hct_task AS a ,hct_tasktype AS b where a.id =8 AND b.id IN (4,1,1,6)'
    	if ($id=$task->add($data)){
    		$task_tasktype =  array();
    		foreach ($data['tasktype'] as $v){
    			$task_tasktype[] = array(
    					'task_id' => $id,
    					'tasktype_id' => $v,
    			);
    		}
    		M('task_tasktype')->addAll($task_tasktype);
    		$this->success('添加成功',U('index'));
    	}else{
    		$this->error('添加失败');
    	}
    }    

    public function editTask($id){
    	$this->checkAuth('',true);
    
    	$this->title = "编辑任务";
    	$this->url = U("editTaskHandle");
    
    	$tasktype = new taskTypeModel();
    	$tasktypedata = $tasktype->getType();
    
    	$this->type1=$tasktypedata[0];
    	$this->type2=$tasktypedata[1];
    	$this->type3=$tasktypedata[2];
    	$this->type4=$tasktypedata[3];
    	$user = new userModel();
    	$this->user = $user->selectData();
    	$this->data = M('task')->where('isdel=0')->find($id);
    	if($this->data['faeenv_id']){
    		$faeenv=M()->query("select env.id,pc.faepc,env.path from hct_faeenv as env join hct_faepc as pc on env.faepc_id = pc.id where env.id = 9 limit 1");
    		$this->faeenv = $faeenv[0];
    	}
    	if(!$this->data){
    		$this->error('没有找到');
    	}
    	$this->display();
    }
    public function editTaskHandle($id){
    	$this->checkAuth('',true,'editTask');
    	$content = preg_replace("/<\s*[\/]?\s*s[\n\r]*c[\n\r]*r[\n\r]*i[\n\r]*p[\n\r]*t[\n\r]*[^>]*>/", '',$_POST['content']);
    	unset( $_POST['content']);
    	$data = I('post.');
    	$data['content'] = $content;
    	$data['time'] = time();
    	$data['user_id'] = $_SESSION['id'];
    	$data['status'] = 14;
    	//     	parray($data);die;
		parray($data);die;
    	$task = new taskModel();
    	if(!$task->create($data)){
    		$this->error($task->getError());
    		return;
    	}    	
    }
    public function confirmTask($id){
//     	$this->checkAuth('',true,'confirmTask');
    	$this->checkAuth('',true,'confirmTask');
    	$this->url = U('confirmTaskHandle');
		$this->title="确认效果";
		$faedebug=M('faedebug')->find((int)$id);
		if(!$faedebug){
			$this->error("没有发现调试记录");
		}
		$user=new userModel();
		$this->user_data=$user->selectData();
// 		$task=M('task')->find($faedebug['task_id']);
// 		if (! $task){
// 			$this->error("没有该任务");
// 		}
// 		if($task['user_id'] != $_SESSION['id'] ){
// 			$this->error("用户不匹配");
// 		}
		$this->faedebug_id = (int) $id;
    	$this->display();
    }
    public function confirmTaskHandle($id){
    	$this->checkAuth('',true,'confirmTask');
    	$post=I('post.');
    	if( ! is_positive_int($id)){
    		$this->error("错误");
    	}
    	$faedebug=M('faedebug');
    	if(!($faedebug_data=$faedebug->find((int)$id))){
    		$this->error("没有发现调试记录");
    	}
    	$task=M('task')->find($faedebug_data['task_id']);
    	if (! $task){
    		$this->error("没有该任务");
    	}
    	if($task['user_id'] != $_SESSION['id'] ){
    		$this->error("用户不匹配");
    	}
    	$data=array();
    	$user = M('user');
    	$data['task_id']=$task['id'];
    	$data['faedebug_id']=(int)$id;
    	$data['user_id']=$task['user_id'];
    	if(!ctype_digit($post['to']) || $post['to'] <= 0 ){
    		$this->error('提交人错误');
    	}
    	if(!$to = $user->field('name,email')->find($data['to'])){
    		$this->error('提交人不存在');
    	}
    	
    	$data['to']=$post['to'];
    	if($post['cc']){
	    	$data['cc']=implode(',',$post['cc']);
	    	if(!preg_match('/^([0-9]+[,]?)+/', $data['cc'])){
	    		$this->error('抄送人错误');
	    	}
	    	if(!$cc = $user->field('name,email')->where(array(
	    			'id'=>array('IN',$data['cc'])
	    		))->select()){
	    		$this->error('抄送人不存在');
	    	}
    	}
    	$data['comment']=$post['comment'];
    	$data['subs']=$post['subs'];
    	$data['time']=time();
//     	parray($data);die;
    	
    	$confirm_task = new confirmTaskModel();
    	if (!$confirm_task->create($data)){
    		$this->error($confirm_task->getError());
    	}
    	if (!$confirm_task->add($data)){
    		$this->error('确认失败');
    	}
    	M('faedebug')->where('`id`='.$id)->limit(1)->setField('isok',1);
    	if(0){
			$mail = new \Common\MyClass\SendMail();
			
			if($data['cc']){
				$this->cc = $user->field('name,email')->where(array(
						'id'=>array('IN',$data['cc'])
				))->select();
			}			
			$mail->setReceiver($to['email']);
			foreach ($cc as $v){
				$mail->setCc($v['email']);
			}
			$content=sprintf(
"<div><table style='width:50%;' cellspacing='1' cellpadding='2' rules='all' border='0'>
<tbody><tr><th colspan='2'>详细信息</th></tr>
<tr><th>临时版本路径</th><td>%s</td></tr>
<tr><th>备注</th><td><pre>%s</pre></td></tr>
<tr><th>提交分支</th><td><pre>%s</pre></td></tr>
</tbody></div>",$faedebug_data['path'],$data['comment'],$data['subs']);
			$mail->setMail("[确认效果]","");
			$mail->sendMail();
					
    	}  		
    	$this->success('成功');
    	
    }
    
    public function showConfirmTask($id){
      	if(!ctype_digit($id) || $id <= 0 ){
    		$this->error('提交人错误');
    	}
    	if(!$data=M('confirm_task')->where("`faedebug_id`=".$id)->find()){
    		$this->error('没有发现确认信息');
    	}
    	$this->title = "详细信息";
    	$this->data = $data;
    	$user = M('user');
    	$this->to = $user->field('name,email')->find($data['to']);
    	if($data['cc']){
    		$this->cc = $user->field('name,email')->where(array(
    				'id'=>array('IN',$data['cc'])
    				))->select();
    	}
    	$this->display();
    }

    public function test(){
    	$mail = new \Common\MyClass\SendMail();
    	// $mail->setServer("XXXXX", "XXXXX@XXXXX", "XXXXX");
    	// $mail->setFrom("XXXXX@XXXXX");
    	// $mail->setReceiver("XXXXX@XXXXX");
    	//$mail->setReceiver("XXXXX@XXXXX");
    	// $mail->setCc("XXXXX@XXXXX");
    	// $mail->setCc("XXXXX@XXXXX");
    	// $mail->setBcc("XXXXX@XXXXX");
    	// $mail->setBcc("XXXXX@XXXXX");
    	// $mail->setBcc("XXXXX@XXXXX");
    	// $mail->setMailInfo("test", "<b>test</b>", "sms.zip");
    	// $mail->sendMail();
    	$mail->setReceiver("7316sdfasfdasd85324@qq.com");
    	$mail->setMail("test","<b>test</b>");
    	if($mail->sendMail()){
    		echo "ss";
    	}else{
    		echo $mail->error();
    	}
    }
}