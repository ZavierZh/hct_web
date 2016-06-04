<?php
namespace Admin\Model;
use Think\Model\RelationModel;

/*
 * 模型关联
 * 
 */

Class UserRelationModel extends RelationModel{
	//定义主表名称
	protected $tableName = 'user';
	
	//定义关联关系
	protected $_link = array(
			'role' => array(
					'mapping_type' => self::MANY_TO_MANY,
					'foreign_key' => 'user_id',
					'relation_foreign_key' => 'role_id',
					'relation_table' => 'think_role_user',
					'mapping_fields' => 'id,name,remark',
					)
			);
	
}