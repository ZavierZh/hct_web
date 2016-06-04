<?php
namespace Common\Controller;
use Think\Auth;
use Think\Controller;

class AuthController extends Controller{
	protected function _initialize(){
//		echo 111111;
		$auth = new Auth();
		if (!$auth->check()){
			$this->error('没有权限');
		}
	}
	
	
}