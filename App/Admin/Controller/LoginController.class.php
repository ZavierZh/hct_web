<?php
namespace Admin\Controller;
use Org\Util\Rbac;

use Think\Controller;
class LoginController extends Controller {
    public function index(){
    	$this->display('Index/login');		
    }
    public function php(){
    	echo U("",'','');
    	phpinfo();
    }
 
    public function logincheck(){
//    	session_start();
    	if(!IS_POST) E('页面不存在');
    	$data=I('post.');
    	//select()返回一个数组,每个数组对应数据库记录,find()只返回一条记录
    	//$user[0]['id']  和 $user['id'] 这种区别
    	if($data['name'] == null || $data['passwd'] == null){
    		$this->error('账号和密码错误');
    		die;    		
    	}
    	$user = M('user')->where($data)->find();
    	if(!$user){
    		$this->error('账号和密码错误');
    		die;    		
    	}
 //   	$_SESSION=array();
	   	session_unset();  //重要,重新登陆,重置所有登陆数据,
//     	echo 'success';
//		parray($user);
		session(C('USER_AUTH_KEY'),$user['id']);
		session('name',$user['name']);
		session('logintime',time());
	//超级管理员识别		
		if($user['name'] == C('RBAC_SUPERADMIN')){
			session("ADMIN_AUTH_KEY",true);
		}else{
			Rbac::saveAccessList();
		}
		parray($_SESSION);
		
// 		die;
    	$this->redirect('Admin/Index/index');
    }
    public function loginout(){
    	session_start();
    	session_unset();
    	session_destroy();  //结束后,所有在后面的$_SESSION 都本次有效,不能被存储
    	$this->success("注销成功");
    	 
    }
}
