<?php
namespace Common\Model;
use Think\Model;

/*
 * 使用F方法缓存数据,
 * 使用文件,有效期基本无限
 * 
 */

Class cacheModel extends  Model{
	//定义主表名称
	protected $tableName = '';
	protected $cacheName = ''; //缓存名字
	
	function __construct() {
		parent::__construct();
		if(!$this->tableName || !$this->cacheName){
			throw new Exception("必须有\$tableName 和 \$cacheName");
		}
	}
	public function addData($data=''){
		if(!$data) return false;
		if (!$this->create($data)){
			return false;
		}
		
		if( ! $this->add($data) ) { 
			$this->flushData();
			return false;
		}
		$this->flushData();
		return true;
	}
	
	public function updateData($data=''){
		if(!$data) return false;
		if (!$this->create($data)){
			return false;
		}
		if (!$this->save($data)) {
			$this->flushData();
			return false;
		}			
		$this->flushData();
		return true;
	}

	public function delData($data=''){
		if(!$data) return false;
		if(!$this->delete($data)){
			$this->flushData();
			return false;
		}
		$this->flushData();
		return true;
	}
	
	public function selectData(){
		if (!$data=F($this->cacheName)){
			$data = $this->flushData();
		}
		return $data;
	}
	
	public function flushData(){
		$data = $this->select();
		F($this->cacheName,$data);
		//parray($list);
		return $data;
	}
	
	public function emptyData(){
		F($this->cacheName,NULL);
	}
	/**
	 * 用于验证字符串长度
	 * @param string $str
	 * @param int $min
	 * @return boolean
	 */
	protected function checkLength($str,$min) {
		preg_match_all("/./", $str, $matches);
		$len = count($matches[0]);
		if ($len < $min) {
			return false;
		} else {
			return true;
		}
	}
	
}