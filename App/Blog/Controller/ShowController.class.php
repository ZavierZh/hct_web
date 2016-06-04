<?php
namespace Blog\Controller;
use Think\Controller;

class ShowController extends Controller{
	
	public function index(){
		$id=I('get.id');
	//	$field = array('title', 'time', 'click', 'content', 'cid');
		$this->blog = M('blog')->find($id);
		
		
		$cate = M('cate')->order('sort')->select();
		$this->parent = \Common\Api\CategoryApi::getParentsID($cate, $this->blog['cid']);
 //		parray($parent);die;
 		
		M('blog')->where(array('id'=>$id))->setInc('click');//自增1
										//setDec递减
		
		$this->display();
	}	
		
	
	public function clickNum(){
		$id=(int) $_GET['id'];
		M('blog')->where(array('id'=>$id))->setInc('click');
		$click = M('blog')->where(array('id'=>$id))->getField('click');
		
		echo 'document.write('.$click.')';
	}
	
	
	
}