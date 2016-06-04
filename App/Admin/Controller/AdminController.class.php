<?php
namespace Admin\Controller;
use Org\Util\Rbac;

use Think\Controller;

class AdminController extends Controller{
	Public function _initialize(){
		if(0){
//			session_start();
// 			parray($_SESSION);

			if(!isset($_SESSION[C('USER_AUTH_KEY')])){
				$this->redirect('Admin/Login/index');
			}
			
			
//			echo U();
// if(!isset($_SESSION[_ACCESS_LIST][strtoupper(MODULE_NAME)][strtoupper(CONTROLLER_NAME)][strtoupper(ACTION_NAME)])) {
// 	echo 111;
// }else{
// 	echo 222;
// }		
//echo MODULE_NAME."|".CONTROLLER_NAME."|".ACTION_NAME."|";
//echo  strtoupper(MODULE_NAME)."|".strtoupper(CONTROLLER_NAME)."|".strtoupper(ACTION_NAME)."|";
			$notAuth = in_array(MODULE_NAME, explode(',',C(NOT_AUTH_MODULE)))||
			in_array(ACTION_NAME, explode(',',C('NOT_AUTH_ACTION')));
 			
 			if(C('USER_AUTH_ON') && !$notAuth){
				Rbac::AccessDecision() || $this->error('没有权限');
 			}				
// 			die;
		}
	}
}