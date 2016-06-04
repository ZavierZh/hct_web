<?php 
namespace Admin\Controller;

use Think\Controller;

class CategoryController extends Controller{
	
	public function index(){
// 		$a=array();
// 		for ($i=0;$i<5;$i++){
// 			$a[]=array(
// 					'id'=>$i,
// 			);
// 		}
// 		foreach ($a as $k=>$v){
// 			echo $k;
// 		}
// 	//	unset($a['a']);
// 		parray($a);die;
//		echo "this is category index";
		$cate = M('cate')->order('sort ASC')->select();
		$cate=\Common\Api\CategoryApi::unlimitedForLevel($cate);
		$this->cate = $cate;
		$this->display();
	}
	//添加分类视图
	public function addCate(){
		$this->pid = I('get.pid',0,'intval');
		$this->display();
		
	}
	//添加分类表视图
	public function runAddCate(){
	//	parray(I('post.'));die;
		if(M('cate')->add(I("post."))){
			$this->success('添加成功',U("index"));
		}else{
			$this->error('添加失败');
		}
	}
	
	public function sortCate(){
// 		parray($_POST);
		$data=I('post.');
// 		$sort=array();
		$db = M('cate');
		foreach ($data as $id=>$sort ){
			$db->where(array('id'=>$id))->setField('sort',$sort);	
// 			$sort[]=array(
// 					"id"=>$k,
// 					"sort"=>$v,
// 					);
		}
	//	parray($sort);
	$this->redirect("index");
// 		M('cate')->where()	
	}
}



?>

