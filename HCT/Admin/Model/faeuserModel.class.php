<?php
namespace Admin\Model;

use \Common\Model\cacheModel;
/*
 * faepc
 * 
 */

Class faeuserModel extends  cacheModel{
	//定义主表名称
	protected $tableName = 'faeuser';
	protected $cacheName = 'faeuser';
	protected $_validate = array(
			array('id','number','id不正确',2),  //2: 有id就验证
			array('name','require','姓名不能为空'),
			array('email','email','email不正确',2),
			array('phone','number','电话不正确',2),
			array('company','2,255','base太少或太长',2,'length'),
	);
	
	public function flushData(){
		
		$d = $this->select();
		$data = array();
		foreach ($d as $v){
			if(!$v['pinyin'])
				$v['pinyin'] = \Common\MyClass\CUtf8_PY::encode($v['name']);
			$data[] = $v;
		}
		
		F($this->cacheName,$data);
		//parray($list);
		return $data;
	}
}
