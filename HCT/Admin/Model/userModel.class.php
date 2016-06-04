<?php
namespace Admin\Model;

use \Common\Model\cacheModel;
/*
 * faepc
 * 
 */

Class userModel extends  cacheModel{
	//定义主表名称
	protected $tableName = 'user';
	protected $cacheName = 'user';
	protected $_validate = array(
			array('id','number','id不正确',2),  //2: 有id就验证
			array('name','require','姓名不能为空'),
// 			array('login_name','require','登陆名不能为空',2),
			array('login_name','3,20','账号不得小于3位,不得大于20位',0,'length'),
			array('login_passwd','6,20','密码不得小于6位,不得大于20位',0,'length'),
			array('email','email','email不正确'),
// 			array('email_passwd','6,20','email密码不得小于6位,不得大于20位',2,'length'),
			array('phone','number','电话不能为空',2),
			array('sector_id','number','部门不正确'),
			
	);
	
	public function flushData(){
		
		$d = $this->where("status=0")->select();
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
