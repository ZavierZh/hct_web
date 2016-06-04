<?php
namespace Admin\Controller;

use Think\Controller;


class AttributeController extends Controller{
	
	public function index(){
// 		echo "this is ".__METHOD__;
		$this->attr = M('attr')->select();
		$this->display();
	}	
	//添加属性视图
	public function addAttr(){
		
		$this->display();
	}
	
	public function runAddAttr(){
		if (M('attr')->add(I('post.'))){
			$this->success('添加成功',U('index'));
		}else{
			$this->error('失败');
		}
	//	parray($_POST);
	}
	
	public function delAttr(){
		
	}
}

