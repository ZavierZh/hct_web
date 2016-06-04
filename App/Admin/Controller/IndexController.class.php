<?php
namespace Admin\Controller;
use Common\Controller\AuthController;

// use Org\Util\Rbac;

use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$this->name = $_SESSION['name'];
    	$this->display();
      //  echo __INFO__;		
    }
    public function php(){
    	echo U("",'','');
    	phpinfo();
    }
}
