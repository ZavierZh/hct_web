<?php
namespace Home\Controller;

use Home\Controller\AuthCheckController;
use Admin\Model\sectorModel;

use Admin\Model\faepcModel;

use Admin\Model\taskModel;

use Admin\Model\taskTypeModel;

use Admin\Model\faeuserModel;
class FaeUserController extends AuthCheckController {
    public function index(){
    	$this->checkAuth();
    	
		$this->display();
    }

    public function addFaeUser(){
    	$this->checkAuth('',true);
    	$this->title="增加用户";
    	$this->url = U("addFaeUserHandle");
    	$this->display();
    }
    public function addFaeUserHandle(){
    	$this->checkAuth(U('addFaeUser'),true,'addFaeUser');
//     	parray($_POST);die;
    	$data = I('post.');
//     	$data['register_time'] = time();
    	$data['pinyin'] = \Common\MyClass\CUtf8_PY::encode($data['name']);
    	$faeuser = new faeuserModel();
    	if ($faeuser->addData($data)){
    		$this->success("添加成功",U('index'));
    	}else{
    		$this->error('添加失败:'.$faeuser->getError());
    	}
    }
    
}