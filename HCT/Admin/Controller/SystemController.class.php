<?php
namespace Admin\Controller;
use Admin\Model\faepcModel;

use Think\Controller;

class SystemController extends Controller {
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
    
}