<?php
namespace Home\Controller;
use Think\Controller;
/*
 * 默认情况下，初始化之后系统会自动启动session，如果不希望系统自动启动session的话，可以设置SESSION_AUTO_START为false，例如：
*		'SESSION_AUTO_START' =>false
* 也就是说不用手动的去		session_start();
*/
/**
 * 权限检查,给某些需要的方法加入验证
 * @author zhouwei
 * 
 */

class AuthCheckController extends Controller {
	public function __construct(){
		parent::__construct();
		if(isset($_SESSION['time']) ){
			$time = time();
			if($time - $_SESSION['time'] >= 20*60){
				session_destroy();
			}else{
				$_SESSION['time'] = $time;
			}
		}
	}
	
	protected function checkAuth($url='',$verify=false,$str=null){
		if (!isset($_SESSION['id'])){
			$_SESSION['url'] = $url != '' ? $url : __SELF__;
			$this->redirect('Home/Index/login');
			exit;
		}else if( time() - $_SESSION['time'] >= 20*60){
			$_SESSION['url'] = $url  ? $url : __SELF__;
			$this->redirect('Home/Index/login');
			exit;
		}else if($verify && !isAuth($str)){
			if(IS_AJAX){
				$this->ajaxReturn(array("type"=>"failed","context"=>"没有权限"));
				die;
			}else{
				$this->error("没有权限");
			}
		}
	}

}