<?php
namespace Admin\Model;

use \Common\Model\cacheModel;
/*
 * faepc
 * 
 */

Class sectorModel extends  cacheModel{
	//定义主表名称
	protected $tableName = 'sector';
	protected $cacheName = 'sector';
	protected $_validate = array(
			array('sector','require','机器名不能为空'),  // 都有时间都验证
			array('id','number','id不正确',2),  //2: 有id就验证
	);
}