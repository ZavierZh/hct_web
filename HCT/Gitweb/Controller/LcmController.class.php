<?php
namespace Other\Controller;
use Think\Controller;
class GitwebController extends Controller {
    public function index(){
    	
		$this->display();
    }

    public function seach(){
    	include 'php_array.php';
    	$this->data = $project_data;
    	$ip=array();
    	foreach ($project_data as $k => $v){
    		$ip[] = $k;
    	}
    	$this->ip = $ip;
    	$this->display();
    }
    public function addGitweb(){
    	
    }
    public function getIP(){
    	
    }
    public function getHCT(){
    	include 'php_array.php';
    	$data = I('get.');
    	if (array_key_exists($data['ip'], $project_data)){
    		$hct = array_keys($project_data[$data['ip']]);
//    		$hct=array();
//     		foreach ($project_data[$data['ip']] as $k=>$v){
//     			$hct[] = $k;
//     		}
    		$this->ajaxReturn($hct);
    	}else{
    		$this->ajaxReturn(array('error'=>'Not exists'));
    	}
    }
    public function getMain(){
    	include 'php_array.php';
    	$data = I('get.');
    	if (array_key_exists($data['hct'], $project_data[$data['ip']])){
    		$main = array_keys($project_data[$data['ip']][$data['hct']]);
    		$this->ajaxReturn($main);
    	}else{
    		$this->ajaxReturn(array('error'=>'Not exists'));
    	}
    }
    public function getSub(){
    	include 'php_array.php';
    	$data = I('get.');
    	if (array_key_exists($data['main'], $project_data[$data['ip']][$data['hct']])){
    		$sub = $project_data[$data['ip']][$data['hct']][$data['main']];
    		$this->ajaxReturn($sub);
    	}else{
    		$this->ajaxReturn(array('error'=>'Not exists'));
    	}
    }
}