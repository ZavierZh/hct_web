<?php
namespace Blog\Controller;
use Think\Page;

use Think\Controller;

class ListController extends Controller{
	
	public function index(){
		
		$id=I('get.id');
		
		$cate = M('cate')->order('sort')->select();
		$cids = \Common\Api\CategoryApi::getChildsID($cate, $id);
		$cids[] = $id;
// 		parray($cids);die;
		
		$where = array('cid'=>array('IN',$cids));
		
		$count = M('blog')->where($where)->count();
// 		echo $count;die;
		$page = new Page($count,5);
		$limit = $page->firstRow.','.$page->listRows;
		$this->blog = D('BlogView')->getBlogs($where,$limit);
// 		echo $page->show();die;
		$page->setConfig('header', '共 %TOTAL_ROW% 条记录');
		$page->setConfig('first', '首页');
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$page->setConfig('last', '末页');
//		$page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
//array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%'),		
//'theme'  => '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',		
		$this->page = $page->show();
// 		echo $page->xxx;die;
// 		echo $page->the_first;die;
// 		echo $page->the_end;die;
		// 		parray($blog);die;
		$this->display();
	}	
	
	
	
	
}