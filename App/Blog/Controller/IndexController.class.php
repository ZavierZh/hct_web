<?php
namespace Blog\Controller;


use Think\Controller;

class IndexController extends Controller {

    public function index(){
    	
//     	$cate = M('cate')->order('sort ASC')->select();
//     	$this->cate=node_merge($cate);
//    	parray($cate);die;
//		parray(\Common\Api\CategoryApi::getParentsID($cate, '22'));die;
// 		parray(\Common\Api\CategoryApi::getChildsID($cate, '7'));die;
	//缓存   S
		
    	$topCateCachce=false;
    	
    	if ((!$topCate = S('top_list')) || $topCateCachce){
	 		$topCate = M("cate")->where(array('pid'=>0))->order('sort')->select();
	    	$cate = M("cate")->order('sort')->select();
	    	$field = array('id','title','time','click');
			$db = M('blog');
			foreach ($topCate as $k=>$v){
				$cids=\Common\Api\CategoryApi::getChildsID($cate, $v['id']);
				$cids[]=$v['id'];
				$where = array('cid'=>array('IN',$cids));
				$topCate[$k]['blog'] = $db->field($field)->where($where)->order('time DESC')->limit(5)->select();
			}
			//shi
			S('top_list',$topCate,300);
// 			S('top_list',null); //删除缓存
    	}
    	//快速缓存
    	/*
    	if (!$list=F('list')){
    		$list = M('attr')->select();
    		F('list',$list);
    	}
    	*/
    	parray($list);
		$this->cate = $topCate;
    	
    	$this->display();
    	
     }
     public function show(){
     	$this->display();
     	
     }
     public function bloglist(){
     	$this->display('');
     	
     }

}