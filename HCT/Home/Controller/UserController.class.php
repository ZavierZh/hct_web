<?php
namespace Home\Controller;

use Home\Controller\AuthCheckController;
use Admin\Model\sectorModel;

use Admin\Model\faepcModel;

use Admin\Model\taskModel;

use Admin\Model\taskTypeModel;

use Admin\Model\userModel;
class UserController extends AuthCheckController {
    public function index(){
    	$this->checkAuth();
    	
		$this->display();
    }
	public function myInfo(){
		$this->checkAuth(U('User/index'));
		$data = M()->query('select hct_user.*,hct_sector.sector from hct_user JOIN hct_sector ON hct_user.sector_id = hct_sector.id where hct_user.id='.$_SESSION['id']." limit 1");
		$this->data=$data[0];
		$this->display();
		
	}
	public function editMyInfo(){
		$this->checkAuth();
		$this->url = U("editMyInfoHandle");
		$this->title = "修改个人信息";
		$this->data = M('user')->find($_SESSION['id']);
		$sector = new sectorModel();
		$this->sector = $sector->selectData();
		if( ! $this->data) {
			$this->error("没有信息");
			return ;
		}
		$this->display();
	}
	public function editMyInfoHandle(){
		$this->checkAuth(U('editMyInfo'));
		$data = I("post.");
		$user = new userModel();
		$data['id'] = $_SESSION['id'];
// 		parray($data);
		if($user->updateData($data)){
			$this->success('修改成功');
		}else{
// 			return;
			$this->error('修改失败');
		}
	}
    public function manage(){
    	
    }
	
    public function myTask(){
		$this->checkAuth(U('User/index'));
    	    	 
    	$this->title = "任务列表";
    	$this->url = U("addTask");
    	$this->pageSize =  (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:20;
    	$this->pageCurrent = (isset($_GET['pgc']) && $_GET['pgc']>=0) ? (int)$_GET['pgc']:1;
    	
    	$task = M('task');
    	$this->total_counts = $task->where(array('isdel'=>0,'user_id'=>$_SESSION['id']))->count();
    	    	 
    	$args = array('id','level','type','color');
    	$tasktype = new taskTypeModel();
    	$tasktypedata = $tasktype->getType();
    	$this->status = $tasktypedata[0];
    	$this->type = $tasktypedata;
		$this->display();
    }
    
    public function TaskList(){
		$this->checkAuth(U('User/index'));
    	    	 
    	$pgt = (isset($_GET['pgt']) && $_GET['pgt']>=0) ? (int)$_GET['pgt']:0;
    	$pgs = (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:40;
    	 
    	$task = M('task');
    	$this->total_counts = $task->where(array('isdel'=>0,'user_id'=>$_SESSION['id']))->count();
    	
    	$this->data = $task->field('base,bugfree_id,comment',true)
    	->where(array('isdel'=>0,'user_id'=>$_SESSION['id']))
    			->order("find_in_set(`status`,'14,16,15')>0 DESC,find_in_set(`status`,'14,16,15') ASC,`reply_time` DESC,`time` DESC")
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
    public function changeEnv(){
    	 
    	$this->title = "环境列表";
    	$fae_counts = M()->query('SELECT COUNT(*) FROM `hct_faeenv` WHERE `isdel` = 0;');
    	$this->total_counts =  $fae_counts[0]['count(*)']?$fae_counts[0]['count(*)']:0;
    	 
    	$this->pageSize =  (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:20;
    	$this->pageCurrent = (isset($_GET['pgc']) && $_GET['pgc']>=0) ? (int)$_GET['pgc']:1;
    	
    	$this->display();
    }
    public function EnvList(){
    	if ($_GET['isdel'] == '1'){
    		$isdel = 1;
    	}
    	else { $isdel = 0;
    	}
    	 
    	$pgt = (isset($_GET['pgt']) && $_GET['pgt']>=0) ? (int)$_GET['pgt']:0;
    	$pgs = (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:20;
    	
    	$fae_counts = M()->query('SELECT COUNT(*) FROM `hct_faeenv` WHERE `isdel` = '.$isdel.';');
    	//parray( $fae_counts[0]);
    	//$fae_counts=array_values($fae_counts[0]);
    	$this->total_counts =  $fae_counts[0]['count(*)'];
    	$data=M('faeenv')->field('hct,main,comment,pm_name',true)
    	->where(array('isdel'=>0))->order('date desc')
    	->limit($pgt,$pgs)->select();
    	 
    	$user = new userModel();
    	$this->userlist = $user->selectData();
    	$this->user = turnArray($this->userlist,'id,name');
    	 
    	$faepc = new faepcModel();
    	$this->faepclist = $faepc->selectData();
    	$this->faepc = turnArray($this->faepclist,array('id','faepc'));
    	$this->data=$data;
    	
    	$this->display();
    }
    public function myMsg(){
		$this->checkAuth(U('User/index'));
    	//     	$data = M('msg')->where('`user_id`='.$_SESSION['id'])->select();
		$this->data = M()->query("SELECT msg.*,task.title FROM hct_msg AS msg JOIN hct_task AS task ON ( msg.task_id = task.id ) WHERE msg.user_id=".$_SESSION['id']);
//     	parray($data);
		$this->display();
    }

    public function addUserSimple(){
    	$this->title="增加用户";
    	$this->url = U("addUserSimpleHandle");
    	$this->display();
    }
    public function addUserSimpleHandle(){
//     	parray($_POST);die;
    	$data = I('post.');
    	$data['register_time'] = time();
    	$data['pinyin'] = \Common\MyClass\CUtf8_PY::encode($data['name']);
    	$user = new userModel();
    	if ($user->addData($data)){
    		$this->success("添加成功",U('index'));
    	}else{
    		$this->error('添加失败:'.$user->getError());
    	}
    }
    
    public function editUserSimple($id){
    	$this->url = U("editUserSimpleHandle");
    	$this->title = "编辑用户";
    	$id = (int)$id;
    	$this->data = M('user')->find($id);
    	if( ! $this->data) {
    		$this->error("没有信息");
    		return ;
    	}
    	$this->display('addUserSimple');
    }
    public function editUserSimpleHandle(){
    	$data = I("post.");
    	$user = new userModel();
    	if($user->updateData($data)){
    		$this->success('添加成功');
    	}else{
    		$this->error('添加失败');
    	}
    }
    public function sendMail(){
//     	$this->success('发送邮件功能正在建设中...');
//     	$mail = new \Common\MyClass\SendMail();
    	$user=new userModel();
    	$this->user_data=$user->selectData();
    	$this->display('User/sendMail');
    	
    }
    public function sendMailHandle(){
    }
}