<?php
namespace Admin\Controller;
use Admin\Model\faepcModel;

use Think\Controller;

class IndexController extends Controller {
    public function index(){
    	$data = I('get.');
    	if ($data['target']){
    		$this->url = $data['target'];
    	}else{
    		$this->url = U(MODULE_NAME.'/Task/index');
    	}
	//	parray($_GET);
    //	echo MODULE_NAME;die;
		$this->display();
    }
    

    
    
    public function Summary(){
    	$this->display();
    }
    
    public function php(){
//    	echo U("",'','');
    	phpinfo();
    }
    public function test(){
		echo U('index?dialog=true');
    }
    public function main(){
    	$this->display();
    }
    public function menu(){
    	$this->display();
    }
}