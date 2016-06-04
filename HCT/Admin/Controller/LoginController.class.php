<?php
namespace Admin\Controller;
use Org\Util\Rbac;

use Think\Controller;
class LoginController extends Controller {
    public function index(){
    	$this->display('Index/login');		
    }

    public function logincheck(){

    }
    public function loginout(){
    	session_start();
    	session_unset();
    	session_destroy();  //结束后,所有在后面的$_SESSION 都本次有效,不能被存储
    	$this->success("注销成功");
    	 
    }
}
