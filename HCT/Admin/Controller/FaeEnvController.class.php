<?php
namespace Admin\Controller;
use Admin\Model\userModel;

use Admin\Model\faepcModel;

use Think\Controller;
class FaeEnvController extends Controller {
    public function index(){
    	$this->title = "环境列表";
    	$this->url = U('addFaeEnv');
    	$fae_counts = M()->query('SELECT COUNT(*) FROM `hct_faeenv` WHERE `isdel` = 0;');
    	$this->total_counts =  $fae_counts[0]['count(*)']?$fae_counts[0]['count(*)']:0;
    	
    	$this->pageSize =  (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:20;
		$this->pageCurrent = (isset($_GET['pgc']) && $_GET['pgc']>=0) ? (int)$_GET['pgc']:1;
		
      	$this->display();
    }

    /*
     * 用于页面返回
     */
    public function FaeEnvlist(){
    	if ($_GET['isdel'] == '1'){ $isdel = 1;}
    	else { $isdel = 0; }
    	
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
    
    //增加FAE环境
    public function addFaeEnv(){
    	//parray( $_GET);die;
    	$this->title = "增加FAE环境";
    	$this->url = U("addFaeEnvHandle");
    	$user = new userModel();
    	$this->user = $user->selectData();
    	$faepc = new faepcModel();
    	$this->faepc = $faepc->selectData();
    	$this->display();
    }
    public function addFaeEnvHandle(){
//  		parray($_SESSION['access']);die;
    	if (!IS_POST)
    		$this->error('不存在');
 		if (!$_POST['faepc_id'] || !$_POST['path'] || !$_POST['date'] ){
 			$this->error('缺少必要的参数');
 		}
 		$data=I('post.');
 		if (M('faeenv')->add($data)){
 			$this->success('添加成功',U('index'));
 		}else{
 			$this->error('添加失败');
 		}		
    }
    
    public function editFaeEnv($id){
    	$this->title = "编辑FAE环境";
    	$this->url = U(MODULE_NAME.'/'.CONTROLLER_NAME."/editFaeEnvHandle",$_GET);
    	$user = new userModel();
    	$this->user = $user->selectData();
    	$faepc = new faepcModel();
    	$this->faepc = $faepc->selectData();
    	   	
    	$this->data = M('faeenv')->find($id);
    	//parray($this->data);

    	if ($this->data){
    		$this->display();
    	}else{
    		$this->error("出错");
    	}
    }
    public function editFaeEnvHandle(){
    	//parray($_POST);
    	if (!$_POST['faepc_id'] || !$_POST['path'] || !$_POST['date'] ){
    		$this->error('缺少必要的参数');
    	}    	
    	$data=I('post.');
     	if (M('faeenv')->save($data)){
 			$this->success('修改成功',U('index'));
 		}else{
 			$this->error('修改失败');
 		}
    }
    //FAE环境回收站
    public function FaeEnvTrach(){
    	$this->title = "FAE环境回收站";
    	$this->url = U('toFaeEnvTrach');
    	$user = new userModel();
    	$this->user = $user->selectData();
    	$faepc = new faepcModel();
    	$this->faepc = $faepc->selectData();    	
    	$this->user = turnArray($user,'id,name');    	
    	$this->faepc = turnArray($faepc,array('id','faepc'));    	
    	$data=M('faeenv')->where(array('isdel'=>1))->select();
    	//	parray($data);
    	$this->data=$data;
    	$this->display();
    }
    //将环境移动到回收站
    public function toFaeEnvTrachHandle($id){
		$data['id'] = array('IN',$id);
    	
    	if (M('faeenv')->where($data)->setField('isdel',1)){
    		$this->ajaxReturn(array("type"=>"success"));
    	}else{
    		$this->ajaxReturn(array("type"=>"failed"));
    	}
    }
    
    public function recoveryFaeEnvHandle($id){
    	$data['id'] = array('IN',$id);
    	if (M('faeenv')->where($data)->setField('isdel',0)){
    		$this->ajaxReturn(array("type"=>"success"));
    	}else{
    		$this->ajaxReturn(array("type"=>"failed"));
    	}
    }
    
    public function delFaeEnvTrachHandle($id){
    	 
    	if (M('faeenv')->delete($id)){
    		$this->ajaxReturn(array("type"=>"success"));
    	}else{
    		$this->ajaxReturn(array("type"=>"failed"));
    	}
    }
    
    /*
     * 查
     */
	public function searchFaeEnv(){
		
		$this->display();
	}
	public function searchFaeEnvHandle(){
// 		parray($_GET);
		$s = array();
		$get = I('get.');

		$data['faepc_id'] = array_filter($get['faepc_id']);
		$data['user_id'] = array_filter($get['user_id']);
		
		if ($data['faepc_id']){
			$s['faepc_id'] =array("IN", $data['faepc_id']);
		}
		if ($data['user_id']){
			$s['user_id'] =array("IN", $data['user_id']);
		}
		if ($get['s_path']){
			$s['path'] = array('LIKE', '%'.$get["s_path"].'%');
		}
		
		if ($get['date_start'] && $get['date_end']){
			$s['date'] = array('BETWEEN', array($get['date_start'],$get['date_end']));
		}else if ($get['date_start']) {
			//大于等于这个日期
			$s['date'] = array('EGT', $get['date_start']);
		}else if ($get['date_end']){
			//小于等于这个日期
			$s['date'] = array('ELT', $get['date_end']);
		}
		
		if ($get['sub']){
			$s['sub'] = array('LIKE','%'.$get['sub'].'%');
		}
		if ($get['main']){
			$s['main'] = array('LIKE','%'.$get['main'].'%');
		}
		if ($get['hct']){
			$s['hct'] = array('LIKE','%'.$get['hct'].'%'); 
		}
		if ($get['path']){
			$s['path'] = array('LIKE','%'.$get['path'].'%');
		}
		if (!$s) return;
		if ($_GET['isdel'] == '1'){	$s['isdel'] = 1;}
		else { $s['isdel'] = 0;}
		
		$total_counts = M('faeenv')->where($s)->count();		
		$this->total_counts = $total_counts;
		
		$pgt = (isset($_GET['pgt']) && $_GET['pgt']>=0) ? (int)$_GET['pgt']:0;
		$pgs = (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:20;
	
		$data = M('faeenv')->where($s)->field('hct,main,comment,pm_name',true)
		->limit($pgt,$pgs)->select();
		
		$user = new userModel();
		$this->userlist = $user->selectData();
		$this->user = turnArray($this->userlist,'id,name');
		 
		$faepc = new faepcModel();
		$this->faepclist = $faepc->selectData();
		$this->faepc = turnArray($this->faepclist,array('id','faepc'));
		$this->data=$data;

		$this->display('FaeEnvlist');
	}    
    
    /**
     * fae机器的增加部分
     */
    //增加FAE机器
    public function FaePC(){
    	$this->title = "FAE机器列表";
    	$this->url = U('addFaePC');
    	 
    	$this->data=M('faepc')->select();
    	$this->display();
    }
    
    public function addFaePC(){
    	$this->title = "添加FAE机器";
    	$this->url = U("addFaePCHandle");
    	$this->display();
    }
    
    public function addFaePCHandle(){
		$data=I('post.');
		if (!$data['faepc']){
			$this->error('没有定义机器名');
		}
		$faepc = new faepcModel();
		if ($faepc->addData($data)){
			$this->success('添加成功',U('index'));
		}else{
			$this->error('添加失败');
		}
    }
    public function editFaePC($id){
    	$this->title = "编辑FAE机器";
    	$this->data = M('faepc')->find($id);
    	$this->display();
    }
    
    public function editFaePCHandle(){
    	$data = I('post.');
    	$faepc = new faepcModel();
    	if($faepc->updateData($data)){
    		$this->success('保存成功',U('FaePC'));
    	}else{
    		$this->error('保存失败');
    	}
    } 
    public function delFaePCHandle($id){
    	$faepc = new faepcModel();
    	if($faepc->delData($id)){
    		$this->ajaxReturn(array('type'=>'success'));
    	}else{
    		$this->ajaxReturn(array('type'=>'failed'));
    	}
    }
    
    public function test(){
    	//$this->assign('closeWin',true);
    	//$this->dialog = true;
    	$this->error("sssss");
    }    
    
}