<?php
namespace Admin\Model;

use \Common\Model\cacheModel;
/*
 * faepc
 * 
 */

Class nodeModel extends  cacheModel{
	//定义主表名称
	protected $tableName = 'node';
	protected $cacheName = 'node';
	
	protected $_validate = array(
			array('name','require','种类名不能为空'), 
			array('title','require','种类名不能为空'), 
			array('remark','require','种类名不能为空'), 
			array('status',array(0,1),'状态不正确',0,'between'),
			array('level',array(1,3),'等级不正确',0,'between'),
			array('show',array(0,1),'显示项不正确',0,'between'),
			array('id','number','id不正确',2),  //2: 有id就验证
			array('pid','number','pid不正确'),
			array('sort','number','排序不正确'),
	);
	
	public function flushData(){
		$data = $this->order('sort,id')->select();
		F($this->cacheName,$data);
		//parray($list);
		return $data;
	}
}