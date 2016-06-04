<?php
namespace Home\Controller;
use Home\Controller\AuthCheckController;

use Admin\Model\faedebugModel;

use Think\Controller;
class DebugController extends AuthCheckController{
    public function index(){
		$this->url = U("addDebug");    	
    	$this->title = "调试列表";
    	$this->display();
    }

    public function addDebug($id){
//     	if(!isset($_SESSION['id'])){
//     		$this->error("请登陆",U('Index/login'));
//     	}else if(!isAuth()){
//     		$this->error("没有权限");
//     	}
    	$this->checkAuth('',true);
    	
    	if($id <=0) {
    		$this->error('错误的任务');
    		return;
    	}
    	$this->url = U('addDebugHandle');
    	 
    	$this->task_id = (int) $id;
    
    	$this->display();
    }
    public function addDebugHandle(){
    	$this->checkAuth('index',true,'addDebug');
    	$data = I('post.');
    	$data['task_id']=(int)$data['task_id'];
    	$faedebug = new faedebugModel();
    	$data['time'] = time();
    	$task = M('task');
    	$task_data = M('task')->field('user_id,faeenv_id')->find($data['task_id']);
    	$data['faeenv_id'] = $task_data['faeenv_id'];
    	if(!$data['faeenv_id']){
    		$this->error("任务没有指明FAE区环境");
    	}
//     	parray($data);die;
    	if(!$faedebug->create($data)){
		  	$this->error($faedebug->getError());
		  	return;
    	}
    	
    	if ($faedebug->add($data)){
    		// 添加消息给任务发布人
    		$msg_data=array();
    		$msg_data['user_id']=$task_data['user_id'];
    		// 临时版本路径
    		$msg_data['msg']=$data['path'];
    		// 任务的id号
    		$msg_data['task_id']=$data['task_id'];
    		$msg_data['type'] = "临时版本";
    		// 改变任务状态为已回复
    		M('msg')->add($msg_data);
    		$task->where('`id`='.$data['task_id'])->setInc('reply_count');
    		$task->where('`id`='.$data['task_id'])->setField('status',16);
    		$this->success('添加成功',U('index'));
    	}else{
    		$this->error('添加失败');
    	}
    }
    
    public function editDebug($id){
    	$this->title = "编辑调试记录";
		$this->url = U('editDebugHandle');
    	if( ! ctype_digit($id) ){
    		$this->error('不存在的调试记录');
    		return;
    	}
    	$this->data = M('deubg')->find($id);
    	if( ! $this->data){
    		$this->error('不存在的调试记录');
    		return;
    	}
    	$this->display();
    }
    public function editDebugHandle(){
    	$data = I("post.");
    	if(M('debug')->save($data)){
    		$this->success('编辑成功',U('index'));
    	}else{
    		$this->error('编辑失败');
    	}
    }
    public function delDebugHandle($id){
    	if(M('debug')->delete($id)){
    		$this->ajaxReturn(array('type'=>'success'));
    	}else{
    		$this->ajaxReturn(array('type'=>'error'));
    	}
    }
    
    public function searchDebugHandle(){
    	$get = I('get.');
    	
    }
}