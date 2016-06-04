<?php
namespace Home\Widget;

use Admin\Model\faepcModel;

use Admin\Model\sectorModel;

use Admin\Model\userModel;

use Think\Controller;

class CommonWidget extends Controller{
	
	/**
	 * user列表
	 * @param $width 宽
	 * @param $mul 是否多选
	 * @param $str 默认显示的
	 * 
	 */
	function user($width=150,$mul=false,$str="",$id="user_id"){
		if (!$width) $width = 150;
		$this->width = $width;
		if ($mul)
			$this->class=" multiple class='js-example-basic-multiple' ";
		else
			$this->class=" class='js-example-placeholder-single' ";
		if($str) $this->str = " data-placeholder='".$str."' ";
		else $this->str = "";
		$this->id = $id;
		$sector = new sectorModel();
		$this->sector = turnArray($sector->selectData(),array('id','sector'));
		$user = new userModel();
		$this->data = $user->selectData();
		
		$this->display('Widget/user');
	}
	
	function faepc($width=150,$mul=false,$str="",$id="faepc_id"){
		if (!$width) $width = 150;
		$this->width = $width;
		if ($mul)
			$this->class=" multiple class='js-example-basic-multiple' ";
		else
			$this->class=" class='js-example-placeholder-single' ";
		if($str) $this->str = " data-placeholder='".$str."' ";
		else $this->str = "";
		$this->id = $id;
		$faepc = new faepcModel();
		$this->data = $faepc->selectData();
	
		$this->display('Widget/faepc');
	}
	// 导航栏的个人信息及消息的插件
	function nav_user(){
		
		
	}
		
}




