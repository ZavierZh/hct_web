<?php
namespace Admin\Controller;
use Think\Dispatcher;

use Admin\Model\taskModel;

use Admin\Model\taskTypeModel;

use Admin\Model\userModel;

use Think\Controller;
/**
 *  任务类别管理
 */
class TaskTypeController extends Controller {
   
    //任务种类的处理
    public function index(){
    	$this->title = "任务种类列表";
    	$this->url = U('addTaskType');
    	$tasktype = new taskTypeModel();
//     	$tasktype->flushData();
    	$this->data = $tasktype->selectData();
    	$this->type = array(
    			'1'=>'状态',
    			'2'=>'类别',
    			'3'=>'模块',
    			'4'=>'标签',
    			);
    	$this->display();
    }
    
    public function addTaskType(){
    	$this->title = "添加任务类别";
    	$this->url = U('addTaskTypeHandle');
    	$this->display();
    }
    
    public function addTaskTypeHandle(){
    	$data = I('post.');
    	if ($data['color'] == "#ffffff"){
    		unset($data['color']);
    	}
//     	parray($data);die;
   
    	$tasktype = new taskTypeModel();
    	if ($tasktype->addData($data)){
   			$this->success('添加成功',U('TaskType'));
    	}else{
    		$this->error('添加失败');
    	}   	
    }
    
    public function editTaskType($id){
    	$this->url = U('editTaskTypeHandle');
    	$this->title = "编辑任务类别";
    	if( ! ctype_digit($id) ){
    		$this->error('不存在的任务类别');
    		return;
    	}
    	$this->data = M('tasktype')->find($id);
    	if( ! $this->data){
    		$this->error('不存在的任务');
    		return;
    	}
    	$this->display();
    }
    public function editTaskTypeHandle(){
    	$data = I("post.");
    	$tasktype = new taskTypeModel();
    	
    	if ($tasktype->updateData($data)){
    		$this->success('编辑成功',U('index'));
    	}else{
    		$this->error('编辑失败');
    	}
    }
    public function delTaskTypeHandle($id){
    	$data = I('post.');
//     	parray($data);
    	$tasktype = new taskTypeModel();
    	if($tasktype->delData($id)){
    	    $this->ajaxReturn(array('type'=>'success'));
    	}else{
    		$this->ajaxReturn(array('type'=>'error','context'=>'failed'));
    	}
    }
    
    // 对种类进行排序
    public function sortTaskTypeHandle(){
    	$post = I('post.');
    	$where= '';
    	
		foreach ($post as $k=>$v){
			if( !is_int(intval($k)) || $k<=0 || !is_int(intval(v)) || $v<=0){
				$this->ajaxReturn(array('type'=>'error','context'=>'not int'));
				return;
			}
			$where .= "($k,$v),";
		}
		$where=substr($where,0,-1);
		if($where == '') $this->ajaxReturn(array('type'=>'error','context'=>'输入错误'));
		$sql = "insert into `hct_tasktype`(`id`,`sort`) values".$where." on duplicate key update `sort`=values(`sort`);";
		$tasktype = new taskTypeModel();
		// 自己写的链接数据库,原生框架的M方法sql会出异常.
		native_mysql_conn();
		if(mysql_query($sql)){
			$tasktype->flushData();
			$this->ajaxReturn(array('type'=>'success'));
		}else{
			$tasktype->flushData();
			$this->ajaxReturn(array('type'=>'error','context'=>mysql_error()));
		}
			
    }
    
}