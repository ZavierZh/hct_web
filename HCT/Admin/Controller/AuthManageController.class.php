<?php
namespace Admin\Controller;
use Admin\Model\nodeModel;

use Admin\Model\faepcModel;

use Think\Controller;

class AuthManageController extends Controller {
    public function index(){
    	$node = new nodeModel();
    	$node->flushData();		    	
    	$node = $node->selectData();
    	$node = node_merge($node);
    	//	dump($node);die;
    	$this->node = $node;
		$this->display();
    }
    public function addNode(){
    	$this->pid = I("get.pid",0,"intval");
    	$this->level=I("get.level",1,"intval");
    	//	echo $this->level;die;
    	$this->url = U('addNodeHandle');
    	
    	switch($this->level){
    		case '1':
    			$this->type = "应用";
    			break;
    		case '2':
    			$this->type = "控制器	";
    			break;
    		case '3':
    			$this->type = "动作方法";
    			break;
    	}
    	$this->display();
    	 
    }
	public function addNodeHandle(){
		$data = I('post.');
// 		parray($data);die;
		$node = new nodeModel();
		if($node->create($data)){
			$node->addData($data);
			$this->success('添加成功',U('index'));
		}else{
			$this->error($node->getError());
		}
	}
	public function editNode($id){
		if ($id < 1) $this->error('错误的输入');
		$id = (int)$id;
		$this->url = U('editNodeHandle');
		$this->data = M('node')->find($id);
		if (!$this->data) $this->error('错误的输入');
		
		switch($this->data['level']){
			case '1':
				$this->type = "应用";
				break;
			case '2':
				$this->type = "控制器	";
				break;
			case '3':
				$this->type = "动作方法";
				break;
		}
		$this->display();
	
	}
	public function editNodeHandle(){
		$data = I('post.');
		// 		parray($data);die;
		$node = new nodeModel();
		if($node->create($data)){
			$node->updateData($data);
			$this->success('修改成功',U('index'));
		}else{
			$this->error($node->getError());
		}
	}
	public function delNodeHandle($id){
		//暂未写好
// 		die;
		if ($id < 1) $this->ajaxReturn(array('type'=>'failed'));
		$id = (int)$id;
		$node = new nodeModel();
		$data = $node->where('id='.$id)->find();
		if (!$data) $this->ajaxReturn(array('type'=>'failed'));
		
		$access =  M('access');
		
// 		$node->delete($data['id']);
		$access->where('`node_id`='.$data['id'])->delete();
		if ($data['level'] = 2 ){
			$node->where('`level`=3 AND `pid`='.$data['id'])->delete();
// 			$access->where('`node_id`='.)->delete();
		}else if($data['level'] = 1){
			$level1 = $node->where('`level` =2 AND `pid`='.$data['id'])->select();
			foreach ($level1 as $v){
				$node->delete($v['id']);
				$node->where('`level`=3 AND `pid`='.$v['id'])->delete();
			}
		}
		$node->flushData();
		//@ 还有中间表
		$this->ajaxReturn(array('type'=>'success'));
		
	}
	
	//配置权限
	public function access(){
		$this->rid = (int)I('id',0,'intval');
		$field = array('id','name','title','pid');
		$node = M("node")->order("sort,id")->field($field)->select();
		$access = M('access')->where(array('sector_id'=>$this->rid))->getField('node_id',true);
	
		$this->node = node_merge($node,$access);
		//		parray($this->node);die;
		$this->display();
	}
	public function editAccessHandle(){
		//	dump($_POST);
		$rid = I('rid',0,'intval');
		$data = array();
		$access=I('post.access');
		foreach($access as $v){
			$tmp = explode('_', $v);
			$data[] = array(
					'sector_id' => $rid,
					'node_id' => $tmp[0],
					'level' => $tmp[1],
			);
		}
		//		dump($data);
		$db = M('access');
		//清空原权限
		$db->where(array('sector_id' => $rid))->delete();
		//插入新权限
		if ($db->addAll($data)){
			$this->success('修改成功',U('User/Sector'));
		}else{
			$this->error('修改失败');
		}
	}
		
}