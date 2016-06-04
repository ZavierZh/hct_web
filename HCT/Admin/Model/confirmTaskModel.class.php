<?php
namespace Admin\Model;

use Think\Model;
/*
 * faepc
 * 
 */

Class confirmTaskModel extends Model{
	//定义主表名称
	protected $tableName = 'confirm_task';
	protected $_validate = array(
			
			array('to','number','提交人错误'),  //没有效果?
			//array('cc','regex','抄送错误',0,'^([0-9]+[,]?)+'), //没有效果
			array('id','number','id不正确',2),  //2: 有id就验证
			array('task_id','number','任务错误'),
			array('faedebug_id','number','调试记录错误'),
			array('user_id','number','用户错误'),
			array('subs','checkLength','分支信息太少',0,'callback',3,array(10)),

	);
	
	protected function checkLength($str,$min) {
		preg_match_all("/./", $str, $matches);
		$len = count($matches[0]);
		if ($len < $min) {
			return false;
		} else {
			return true;
		}
	}
// 	protected function checkCC($data){
		
// 	}
}