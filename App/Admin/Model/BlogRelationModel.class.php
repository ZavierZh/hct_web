<?php
namespace Admin\Model;
use Think\Model\RelationModel;

/*
 * 模型关联
 * 
 */

Class BlogRelationModel extends RelationModel{
	//定义主表名称
	protected $tableName = 'blog';
	
	//定义关联关系
	protected $_link = array(
			'attr' => array(
					'mapping_type' => self::MANY_TO_MANY,
					'mapping_name' => 'attr',
					'foreign_key' => 'bid',
					'relation_foreign_key' => 'aid',
					'relation_table' => 'think_blog_attr',
		//			'mapping_fields' => 'id,name',
					),
			'cate' => array(
					'mapping_type' => self::BELONGS_TO,
					'foreign_key' => 'cid',
					'mapping_fields' => 'name',
					'as_fields' => 'name:cate', //将字段从数组中提取
					),
			);
	//0为除回收站的所有博文,1为在回收站的博文
	public function getBlogs($type = 0){
		$field = array('del');
		//读取没有被标记的博文
		$where = array('del'=>$type);
		return $this->field($field,true)->where($where)->relation(true)->select();
	}
	
}