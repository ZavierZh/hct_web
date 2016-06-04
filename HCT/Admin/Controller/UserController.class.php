<?php
namespace Admin\Controller;
use Admin\Model\sectorModel;

use Admin\Model\userModel;

use Think\Controller;
/*
 * 用户管理和部门管理
 */
class UserController extends Controller {
    public function index(){
    	$this->url = U('addUser');
    	$this->title = "用户列表";
    	$user = new userModel();
//     	$user->flushData();
    	$this->data = $user->select();
//     	parray($this->data);die;
    	$sector = new sectorModel();
    	$this->status = array('正常','注册','关闭','离职');
    	$this->sector = turnArray($sector->selectData(),array('id','sector'));
		$this->display();
    }

    public function addUser(){
   		$this->url = U("addUserHandle");
   		$this->title = "增加用户";
    	$sector = new sectorModel();
    	$this->sector = $sector->selectData();
    	$this->display();
    }
    
    public function addUserHandle(){
//     	$this->success('sss',U('index'));die;
    	$data = I('post.');
    	$data['register_time'] = time();
    	$data['pinyin'] = \Common\MyClass\CUtf8_PY::encode($data['name']);
        $data['register_time'] = time();
    	if($data["relogin_passwd"] != $data["login_passwd"]){
    		$this->error("密码不一致");
    	}
    	$user = new userModel();
    	
    	if(!$user->create()){
    		$this->error($user->getError());
    	}
    	$data['pinyin'] = \Common\MyClass\CUtf8_PY::encode($data['name']);
    	if (M('user')->where("login_name='".$data["login_name"]."'")->find()){
    		$this->error('账号已经存在');
    	}
//     	parray($data);die;
    	if ($user->addData($data)){
    		$this->success("添加成功",U('index'));
    	}else{
    		$this->error('添加失败:'.$user->getError());
    	}
    }

    public function editUser($id){
    	$this->url = U("editUserHandle");
    	$this->title = "编辑用户";
    	$this->data = M('user')->find($id);
    	$sector = new sectorModel();
    	$this->sector = $sector->selectData();
    	if( ! $this->data) {
    		$this->error("没有信息");
    		return ;
    	}
    	$this->display("addUser");
    }
    public function editUserHandle(){
    	$data = I("post.");
    	$user = new userModel();
    	if($user->updateData($data)){
    		$this->success('添加成功');
    	}else{
    		$this->error('添加失败');
    	}  	
    }
    
    public function delUserHandle($id){
    	$user = new userModel();
    	if($user->delData($id)){
			$this->ajaxReturn(array(
    				'type'=>"success",
    				'context'=>"修改成功"
    		));
    	}else{
   			$this->ajaxReturn(array(
    				'type'=>"failed",
    				'context'=>"删除失败"		
    		));
    	}
    }
    public function searchUserHandle(){
    	
    }
	// 审核 注册的用户    
    public function auditUser(){
    	$this->url = U('auditUserHandle');
    	$this->title = "用户列表";
    	$user = new userModel();
    	//     	$user->flushData();
    	$this->data = $user->where("status=1")->select();
    	//     	parray($this->data);die;
    	$sector = new sectorModel();
    	$this->sector = turnArray($sector->selectData(),array('id','sector'));
    	$this->display();
    }
    public function auditUserHandle($id){
    	if(!ctype_digit($id)){
    		$this->ajaxReturn(array(
    				'type'=>"failed",
    				'context'=>"错误的方式"		
    		));
    		die;
    	}
    	$user = new userModel();
    	$data = array('id'=>$id,'status'=>'0');
    	if($user->updateData($data)){
    		$this->ajaxReturn(array(
    				'type'=>"success",
    				'context'=>"修改成功"
    		));
    	}else{
    		$this->ajaxReturn(array(
    				'type'=>"failed",
    				'context'=>"修改失败"
    		));
    	}
    }
    //部门
    
    public function Sector(){
    	$this->title = "部门列表";
    	$this->url = U('addSector');
    	$sector = new sectorModel();
    	$this->data = $sector->selectData();
    	$this->display();
    }
    public function addSector(){
    	$this->url = U('addSectorHandle');
    	$this->title = "增加部门";
    	$this->display();
    	 
    }
    public function addSectorHandle(){
    	
    	$data = I('post.');
    	$sector = new sectorModel();
    	if ($sector->addData($data)){
    		$this->success('添加成功',U('Sector'));
    	}else{
    		$this->error('添加失败');
    	}   	
    }
    
	public function editSector($id){
		$this->url = U('edtiSectorHandle');
		$this->title = "修改部门信息";
		$this->data = M('sector')->find($id);
		if( ! $this->data) {
			$this->error("没有信息");
			return ;
		}
		$this->display();
	}
	public function edtiSectorHandle(){
		$data = I('post.');
		$sector = new sectorModel();
		if($sector->updateData($data)){
			$this->success('添加成功',U('Sector'));
		}else{
			$this->error('添加失败');
		}
	}
	public function delSectorHandle($id){
		$sector = new sectorModel();
		if($sector->delData($id)){
		    $this->ajaxReturn(array('type'=>'success'));
    	}else{
    		$this->ajaxReturn(array('type'=>'error','context'=>$sector->getError()));
    	}
	}
}