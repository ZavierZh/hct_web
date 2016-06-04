<?php
namespace Blog\Model;

use Think\Model\ViewModel;


class BlogViewModel extends ViewModel{
	
	protected $viewFields = array(
			'blog' => array(
					'id','title','time','click','summary',
					'_type'=>'LEFT', //别名,用于数据库关联用
					),
			'cate' => array(
					'name',
					'_on'=>'blog.cid = cate.id',
					),
			);
	public function getBlogs($where,$limit=30){
		
		return $this->where($where)->limit($limit)->select();
	
	}
	
}