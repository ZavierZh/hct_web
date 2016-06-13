<?php
namespace Home\Controller;
use Home\Controller\AuthCheckController;
use Admin\Model\userModel;
use Admin\Model\sectorModel;

class UserManageController extends AuthCheckController {
  public function index(){
    	$this->url = U('addUser');
    	$this->title = "用户列表";
    	$user = new userModel();
//     	$user->flushData();
    	$this->data = $user->selectData();
//     	parray($this->data);die;
    	$sector = new sectorModel();
    	$this->sector = turnArray($sector->selectData(),array('id','sector'));
		$this->display();
    }

    public function addUser(){
    	$this->checkAuth('',true);
    	 
   		$this->url = U("addUserHandle");
   		$this->title = "增加用户";
    	$sector = new sectorModel();
    	$this->sector = $sector->selectData();
    	$this->display();
    }
    
    public function addUserHandle(){
    	$this->checkAuth('',true,'addUser');
//     	$this->success('sss',U('index'));die;
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

    public function editUser($id){
    	$this->checkAuth('',true);
    	 
    	$this->url = U("editUserHandle");
    	$this->title = "编辑用户";
    	$this->data = M('user')->find($id);
    	$sector = new sectorModel();
    	$this->sector = $sector->selectData();
    	if( ! $this->data) {
    		$this->error("没有信息");
    		return ;
    	}
    	$this->display('addUser');
    }
    public function editUserHandle(){
    	$this->checkAuth('',true,'editUser');
    	 
    	$data = I("post.");
    	$user = new userModel();
    	if($user->save($data)){
    		$this->success('修改成功');
    	}else{
    		$this->error('修改失败,'.$user->getError());
    	}
    	$user->flushData(); 	
    }
    
    public function delUserHandle($id){
    	$this->checkAuth('',true,'delUserHandle');
    	 
    	$user = new userModel();
    	if($user->delData($data)){
    		$this->success('添加成功',U('index'));
    	}else{
    		$this->error('添加失败');
    	}
    }
}
