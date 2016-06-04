<?php
namespace Blog\Widget;

use Think\Controller;

class CommonWidget extends Controller{
	
	//热门博客
	function HotBlog(){
	//	parray($data);
	//	echo "hotblog";
	
		if ( ! $hotblog=S('hotblog')){
			$field = array('id','title','click');
			//最热博客
			$hotblog = M('blog')->field($field)->order('click DESC')->limit(5)->select();
			S('hotblog',$hotblog,3600);
		}
		
		if ( ! $newblog=S('newblog')){
			$field = array('id','title','click');
			//最新发布
			$newblog = M('blog')->field($field)->order('time DESC')->limit(5)->select();
			S('newblog',$newblog,900);
		}
		$this->hotblog = $hotblog;
		$this->newblog = $newblog;
		$this->display('Widget/HotBlog');
	}
	
	//导航栏
	function navigation(){
		if (! $nav_cate=S('navdata')){
	//		echo "1111111111111111111";
			$nav_cate = M('cate')->order('sort')->select();
			$nav_cate=node_merge($nav_cate);
			S('navdata',$nav_cate,3600*24);
		}
		$this->nav_cate=$nav_cate;
		$this->display('Widget/navigation');
	}
	
}




