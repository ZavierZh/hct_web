<?php
namespace Other\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$this->display();
    }
    public function flush(){
    	$this->show('<script type="text/javascript">parent.location.reload();</script>');
    }
    
    public function success(){
    	$this->display('info');
    }
    public function pro(){
//     	$path=__ROOT__.'/../../php_array.php';
//     	include $path;
    	include 'php_array.php';
    	//echo $path;
    	print_r($project_data);
    	
    }
}