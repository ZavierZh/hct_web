<?php
namespace Admin\Model;

use \Common\Model\cacheModel;
/*
 * faepc
 * 
 */

Class taskTypeModel extends  cacheModel{
	//定义主表名称
	protected $tableName = 'tasktype';
	protected $cacheName = 'tasktype';
	protected $cacheSubName = 'tasktypetype';
	
	protected $_validate = array(
			array('type','require','种类名不能为空'),  // 都有时间都验证
			array('level',array(1,4),'种类等级不正确',0,'between'),  // 都有时间都验证
			array('id','number','id不正确',2),  //2: 有id就验证
			array('color','/^#[0-9a-fA-F]{6}$/','颜色不正确',2,'regex'),
	);
	
	public function flushData(){
		$data = $this->order('level,sort,id')->select();
		F($this->cacheName,$data);
		
		$type1 = array(); $type2 = array(); $type3 = array(); $type4 = array();
		foreach ($data as $v){
			switch ($v['level']){
				case 1: $type1[] = $v; break;
				case 2: $type2[] = $v; break;
				case 3: $type3[] = $v; break;
				case 4: $type4[] = $v; break;
			}
		}
		$type = array($type1,$type2,$type3,$type4);
		F($this->$cacheSubName,$type);
		return $data;
	}
	/**
	 * [0] 为状态, [1] 为类型,
	 * [2] 为模块, [3] 为标签
	 * @return Ambigous <mixed, void, boolean, NULL>
	 */
	public function getType(){
		if ($data = F($this->$cacheSubName)){
// 			echo "xxxxx";
			return $data;
		}else{
// 			echo "yyyyyyyy";
			$this->flushData();
			return F($this->$cacheSubName);
		}
	}
}