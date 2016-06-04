<?php
namespace Admin\Controller;
use Think\Controller;
class FaeUserController extends Controller {
    public function index(){
    	$this->title = "FAE成员列表";
    	$this->display();
    }
    public function addFaeUser(){
    	$this->title = "添加FAE成员";
    	$this->display();
    }
    public function addFaeUserHandle(){
    	parray($_POST);die;
    	$method = I('get.');
    	$data = I('post.');
    	if (M('task')->add($data)){
    		if ($method['method'] == 'dialog'){
    			$this->success('添加成功',U('Other/Index/flush'));
    		}else{
    			$this->success('添加成功',U('index'));
    		}
    	}else{
    		$this->error('添加失败');
    	}
    }
    
    public function editFaeUser($id){
    	$this->title = "编辑FAE成员";
    	if( ! ctype_digit($id) ){
    		$this->error('不存在的FAE成员');
    		return;
    	}
    	$this->data = M('faeuser')->find($id);
    	if( ! $this->data){
    		$this->error('不存在的FAE成员');
    		return;
    	}
    	$this->display();
    }
    
    public function editFaeUserHandle(){
    	$data = I('post.');
    	
    	if(M('faeuser')->save($data)){
    		$this->success('编辑成功',U('index'));
    	}else{
    		$this->error('编辑失败');
    	}
    }
    public function delFaeUserHandle($id){
    	
    	if(M('faeuser')->delete($id)){
    		$this->ajaxReturn(array('type'=>'success'));
    	}else{
    		$this->ajaxReturn(array('type'=>'error'));
    	}
    }
    
    public function searchFaeUserHandle(){
    	$get = I('get.');
    }
    
    /*
     * 
     */
    public function FaeUserRecord(){
    	 $this->display();
    }   
    public function addFaeUserRecord(){
    	$this->title = "增加FAE来访记录";
    	
    	$this->display();
    }
    
    public function editFaeUserRecord($id){
    	$this->title = "编辑FAE来访记录";
    	if( ! ctype_digit($id) ){
    		$this->error('不存在的FAE来访记录');
    		return;
    	}
    	$this->data = M('faerecord')->find($id);
    	if( ! $this->data){
    		$this->error('不存在的FAE成员');
    		return;
    	}
    	$this->display();
    }
    
    public function editFaeUserRecordHandle(){
    	$data = I('post.');
    	 
    	if(M('faerecord')->save($data)){
    		$this->success('编辑成功',U('index'));
    	}else{
    		$this->error('编辑失败');
    	}
    }
    public function delFaeUserRecordHandle($id){
    	 
    	if(M('faerecord')->delete($id)){
    		$this->ajaxReturn(array('type'=>'success'));
    	}else{
    		$this->ajaxReturn(array('type'=>'error'));
    	}
    }
    
    public function searchFaeUserRecordHandle(){
    	$get = I('get.');
    }
}