<?php
namespace Admin\Controller;
use Admin\Model\faedebugModel;

use Think\Controller;
class DebugController extends Controller {
    public function index(){
		$this->url = U("addDebug");    	
    	$this->title = "调试列表";
    	$this->display();
    }
    public function addDebug(){
    	$this->title = "增加调试记录";
    	$this->url = U('addDebugHandle');
    	$this->display();
    }
    public function addDebugTask($id){
    	if($id <=0) {
    		$this->error('错误的任务');
    		return;
    	}
    	$this->url = U('addDebugHandle');
    	 
    	$this->task_id = (int) $id;
    	$this->display();
    }
    public function addDebugHandle(){
//     	parray($_POST);die;
    	$data = I('post.');
    	$faedebug = new faedebugModel();
    	$data['time'] = time();
    	if(!$faedebug->create($data)){
		  	$this->error($faedebug->getError());
		  	return;	
    	}
    	if ($faedebug->add($data)){
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