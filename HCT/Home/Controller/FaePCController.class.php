<?php
namespace Home\Controller;
use Home\Controller\AuthCheckController;
use Admin\Model\userModel;

use Admin\Model\faepcModel;
class FaePCController extends AuthCheckController {
    /**
     * fae机器的增加部分
     */
    //增加FAE机器
    public function index(){
    	$this->checkAuth('',true);
    	$this->title = "FAE机器列表";
    	$this->url = U('addFaePC');
    
    	$this->data=M('faepc')->select();
    	$this->display();
    }
    public function addFaePC(){
    	$this->checkAuth('',true);
    	$this->title = "添加FAE机器";
    	$this->url = U("addFaePCHandle");
    	$this->display();
    }
    
    public function addFaePCHandle(){
    	$this->checkAuth('',true,'addFaePC');
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
    	$this->checkAuth('',true,'editFaePC');
    	$this->title = "编辑FAE机器";
    	$this->data = M('faepc')->find($id);
    	$this->display();
    }
    public function editFaePCHandle(){
    	$this->checkAuth('',true,'FaePC/editFaePC');
    	$data = I('post.');
    	$faepc = new faepcModel();
    	if($faepc->updateData($data)){
    		$this->success('保存成功',U('FaePC'));
    	}else{
    		$this->error('保存失败');
    	}
    }
    public function delFaePCHandle($id){
    	$this->checkAuth('',true,'FaePC/delFaePC');
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