<?php
namespace Admin\Model;
use Think\Model;
/*
 * faepc
 * 
 */

Class faedebugModel extends  Model{
	//定义主表名称
	protected $tableName = 'faedebug';
	protected $_validate = array(
			array('id','number','id不正确',2),  //2: 有id就验证
			array('task_id','number','任务选择不正确',2),
			array('faeuser_id','number','FAE 信息不正确',2),
			array('time','number','没有回复时间不正确'),
			array('path','8,255','路径太少或太长',2,'length'),
			array('comment','4,255','备注太少或太长',2,'length'),
			array('faeenv_id','number','faeenv环境不正确'),
		);
}