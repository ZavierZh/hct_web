<?php
namespace Admin\Controller;
use Admin\Model\sectorModel;

use Admin\Model\faepcModel;

use Admin\Model\taskModel;

use Admin\Model\taskTypeModel;

use Admin\Model\userModel;

use Think\Controller;
/**
 * 任务管理 
 */
class TaskController extends Controller {
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
    		$this->faepc = turnArray($faepc->selectData(),'id,faepc');
    	}
    	$this->tasktype = M('task_tasktype')->where('`task_id`='.$task['id'])->select();
    	
    	$tasktypedata = new taskTypeModel();
    	$type = $tasktypedata->selectData();
    	 
    	$this->type = turnMulArray($type,'id,type');
    	$this->faedebug = M('faedebug')->where('`task_id`='.$task['id'])->select();
    	$this->task = $task;
    	$this->display();
    	
    }
    
    public function addTask(){
    	
    	$this->title = "增加任务";
    	$this->url = U("addTaskHandle");

    	$tasktype = new taskTypeModel();
    	$tasktypedata = $tasktype->getType();

    	$this->type1=$tasktypedata[0];
    	$this->type2=$tasktypedata[1];
    	$this->type3=$tasktypedata[2];
    	$this->type4=$tasktypedata[3];
    	$user = new userModel();
		$this->user = $user->selectData(); 	
    	$this->display();
    }
    
    public function addTaskHandle(){
//     	parray($_POST);die;
    	 
    	 // 去掉 script ,防止注入
    	$content = preg_replace("/<\s*[\/]?\s*s[\n\r]*c[\n\r]*r[\n\r]*i[\n\r]*p[\n\r]*t[\n\r]*[^>]*>/", '',$_POST['content']);
    	unset( $_POST['content']);
    	$data = I('post.');
    	$data['content'] = $content;
    	$data['time'] = time();
    	
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
    	$this->title = "编辑任务";
    	$this->url = U("editTaskHandle");
    	 
    	if( ! ctype_digit($id) ){
    		$this->error('不存在的任务');
    		return;
    	}
		$this->data = M('task')->find($id);
		if( ! $this->data){
			$this->error('不存在的任务');
			return;
		}
    	$this->display();
    }
    
    public function editTaskHandle(){
    	$data = I('post.');
    	if(M('task')->save($data)){
    		$this->success('编辑成功',U('index'));
    	}else{
    		$this->error('编辑失败');
    	}
    }
    
    
    
    public function toTaskTrachHandle($id){
    	$data['id'] = array('IN',$id);
    	 
    	if (M('task')->where($data)->setField('isdel',1)){
    		$this->ajaxReturn(array("type"=>"success"));
    	}else{
    		$this->ajaxReturn(array("type"=>"failed"));
    	}
    }
    
    
    
    public function delTaskHandle($id){
    	if(M('task')->delete($id)){
    		$this->ajaxReturn(array('type'=>'success'));
    	}else{
    		$this->ajaxReturn(array('type'=>'error'));
    	}
    }
    
	public function searchTaskHandle(){
		$get = I('get.');
	}
    
}