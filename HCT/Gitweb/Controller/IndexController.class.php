<?php
namespace Gitweb\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function index(){
    	
		$this->display();
    }

    public function seach(){
    	include 'php_array.php';
        $data = array();
    	foreach ($project_data as $ip=>$hct){
    		$data[$ip] = array_keys($hct);
    	}
    	$this->data = $data;
    	$this->display();
    }
    
    public function addGitweb(){
    	
    }
    public function getIP(){
    	include 'php_array.php';
    	$data = array();
    	foreach ($project_data as $ip=>$hct){
    		$data[$ip] = array_keys($hct);
    	}
    	parray($data);
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
    public function lcm(){
    	include 'php_array_lcm.php';
    	$this->hct = array_keys($ALL_LCM);
    	$this->display();
    }
    public function getlcm($hct){
    	include 'php_array_lcm.php';
		return $this->ajaxReturn($ALL_LCM[$hct]);
    }
    public function cam(){
    	include 'php_array_cam.php';
    	$this->hct = array_keys($ALL_CAM);
    	$this->display();
    }
    public function getcam($hct){
    	include 'php_array_cam.php';
    	return $this->ajaxReturn($ALL_CAM[$hct]);
    }
    
    public function check(){
    	$last_date = getLastDate();
    	include "data/check_list_".$last_date.".php";
	$this->last_date = $last_date;
		$this->base = array_keys($check_list);
// 		$this->check_list=$check_list;
		$this->display();
    }
    
	public function getCheckBase(){
		
		include "data/check_list_".getLastDate().".php";
// 		include "data/check_list_2016-04-20.php";
		$data=array();
		if($_GET['base']){
			if ( "all" == $_GET['base'] ){
				foreach ($check_list as $k=>$v ){
					foreach ($v as $kk=>$vv){
						foreach ($vv as $kkk=>$vvv){
							$data[$k."/".$kk."/".$kkk]=$vvv;
// 							foreach ($vvv as $vvvv){
// 								$data[]=array_merge(
// 									array('sub'=>$k."/".$kk."/".$kkk),$vvvv);
// 							}
						}
					}
				}
			}else if( !isset($check_list[$_GET['base']])){
				$this->show('<tr><td cosplan="4"><h3>查询错误</h3></td></tr>');
				return;
			}else{
				foreach ($check_list[$_GET['base']] as $kk=>$vv){
					foreach ($vv as $kkk=>$vvv){
						$data[$kk."/".$kkk]=$vvv;
					}
				}
			}
		}else{
			$this->show('<tr><td cosplan="4"><h3>查询错误</h3></td></tr>');
		}
// 		parray($data);
		$this->data = $data;
// 		parray($this->check_list);
		$this->display();
	}
	public function searchCheckBase(){
// 		parray($_GET);die;
		
		$search_type=isset($_GET['type'])?$_GET['type']:'uid';
		include "data/check_list_".getLastDate().".php";
		$search_str=$_GET[$search_type];
		$data=array();
		if($_GET['base']){
			if ( "all" == $_GET['base'] ){
				foreach ($check_list as $k=>$v ){
					foreach ($v as $kk=>$vv){
						foreach ($vv as $kkk=>$vvv){
// 							$data[$k."/".$kk."/".$kkk]=$vvv;
							foreach ($vvv as $vvvv){
								if (isset($vvvv[$search_type]) && (stristr($vvvv[$search_type],$search_str) != "")){
									if ($vvvv['type'] == 'CAM' ){
										$data[$k."/".$kk."/".$kkk]=array($vvvv,end($vvv));
									}else{
										$data[$k."/".$kk."/".$kkk]=array($vvvv);
									}
								}
							}
						}
					}
				}
			}else if( !isset($check_list[$_GET['base']])){
				$this->show('<tr><td cosplan="4"><h3>查询错误</h3></td></tr>');
				return;
			}else{
				foreach ($check_list[$_GET['base']] as $kk=>$vv){
					foreach ($vv as $kkk=>$vvv){
						foreach ($vvv as $vvvv){
							if (stristr($vvvv[$search_type],$search_str) != ""){
									if ($vvvv['type'] == 'CAM' ){
										$data[$k."/".$kk."/".$kkk]=array($vvvv,end($vvv));
									}else{
										$data[$k."/".$kk."/".$kkk]=array($vvvv);
									}
							}
						}
					}
				}
			}
		}else{
			$this->show('<tr><td cosplan="4"><h3>查询错误</h3></td></tr>');
		}
// 		parray($data);
// 		die;
// 		$id=0;
// 		$base=array();
// 		$prjt=array();
// 		$check=$check_list[$_GET['base']];

// 		foreach ($check as $kk => $vv) {
// 			$id++;
// 			$cid=$id;
// 			$base[]=array(
// 				'id'=>$id,'name'=>$kk,'pid'=>0,'level'=>1,
// 				);
// 			foreach ($vv as $kkk => $vvv) {
// 				$id++;
// 				$base[]=array(
// 						'id'=>$id, 'name'=>$kkk, 'pid'=>$cid, 'level'=>2,
// 					);
// 				foreach ($vvv as $kkkk => $vvvv) {
// 					$prjt[]=array_merge($vvvv,array('prjt_id'=>$id));
// 				}
// 			}
// 		}
// 		$rslt=array();
// 		foreach ($prjt as $v){
// 			if (stristr($v[$search_type],$_GET[$search_type]) != ""){
// 				$index=$v['prjt_id']-1;
// 				$v['sub']=$base[$index]['name'];
// 				$v['main']=$base[$base[$index]['pid']-1]['name'];
// 				$rslt[]=$v;
// 			}
// 		}
// 		$this->rslt=$rslt;
		$this->data=$data;
		
		$this->display('getCheckBase');
	}
	
	public function checkError(){
		//date("Y-m-d")
    	$last_date = getLastDate();
	$this->last_date = $last_date;
		include "data/check_error_".$last_date.".php";
		$i=0;$j=0;
		$data=array();
		$list=array();
		if($error != ""){
			foreach ($error as $k=>$v ){
				// k 为 base
				if(is_string($v)){
					$data[]=array($k,$v,$i,0);
					$list[] = array($k,$i);
					$i++;
						
				}else if(is_array($v)){
					foreach ($v as $kk=>$vv){
					// vv 为 main
						if(is_string($vv)){
							$data[]=array($k."/".$kk,$vv,$i,1);
							$list[] = array($k."/".$kk,$i);
							$i++;
								
						}else if (is_array($vv)){
							foreach ($vv as $kkk=>$vvv){
							// vvv 为 sub
								if(is_string($vvv)){
									$data[]=array($k."/".$kk."/".$kkk,$vvv,$i,2);
									$list[] = array($k."/".$kk."/".$kkk,$i);
									$i++;
										
								}else{
									foreach ($vvv as $vvvv){
										$data[]=array($k."/".$kk."/".$kkk,$vvvv,$i,3);
										$list[] = array($k."/".$kk."/".$kkk,$i);
										$i++;
									}
								}
							}
						}
					}
				}
			}
		}
// 		parray($error);
// 		parray($data);
		$this->data=$data;
		$this->list=$list;
		$this->display();
	}
	public function test(){
		include "data/check_list_".getLastDate().".php";
		
		$id=0;
		$data=array();
		$prjt=array();
		foreach ($check_list as $k => $v) {
			# code...
			$id++;
			$data[]=array(
					'id'=>$id,
					'name'=>$k,
					'pid'=>0,
					'level'=>1,
			);
			$pid=$id;
			foreach ($v as $kk => $vv) {
				# code...
				$id++;
				$cid=$id;
				$data[]=array(
						'id'=>$id,
						'name'=>$kk,
						'pid'=>$pid,
						'level'=>2,
				);
				foreach ($vv as $kkk => $vvv) {
					# code...
					$id++;
					$data[]=array(
							'id'=>$id,
							'name'=>$kkk,
							'pid'=>$cid,
							'level'=>3,
					);
					foreach ($vvv as $kkkk => $vvvv) {
						# code...
						$prjt[]=array_merge($vvvv,array('prjt_id'=>$id));
					}
				}
			}
		}
		
		parray($data);
		parray($prjt);
		
	}
	
	
}