<?php
namespace Admin\Model;

use Think\Model;
/*
 * faepc
 * 
 */

Class taskModel extends Model{
	//定义主表名称
	protected $tableName = 'task';
	
	protected $_validate = array(
			array('title','4,255','标题太少或太长',0,'length'),  // 都有时间都验证
			array('id','number','id不正确',2),  //2: 有id就验证
			array('faeenv_id','number','FAE环境不正确',2),  //2: 有id就验证
			array('sub','4,255','项目名太少或太长',0,'length'),
			array('base','4,255','base太少或太长',2,'length'),
			array('user_id','number','发布人不正确'),
			array('status','number','任务状态不正确'),
// 			array('status',array(1,6),'任务状态不正确',0,'between'),
			array('content','checkLength','内容太少',0,'callback',3,array(10)),
			array('time','number','时间不正确'),
	);
	//定义关联关系 !!!!bug太严重,不用了
// 	protected $_link = array(
// 			'tasktype' => array(
// 					'mapping_type' => self::MANY_TO_MANY,
// 					'mapping_name' => 'tasktype',
// 					'foreign_key' => 'task_id',
// 					'relation_foreign_key' => 'tasktype_id',
// 					'relation_table' => 'hct_task_tasktype',
// 					//			'mapping_fields' => 'id,name',
// 			),
// 	);	


}