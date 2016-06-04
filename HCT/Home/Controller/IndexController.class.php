<?php
namespace Home\Controller;
use Home\Controller\AuthCheckController;
use Admin\Model\sectorModel;
use Admin\Model\userModel;
class IndexController extends AuthCheckController {
    public function index(){
		$this->display();
    }
    public function login(){
    	// 		$_SESSION['url'] = __SELF__;
//     	parray($_GET);
    	if( !isset($_SESSION['url']) && isset($_GET['url'])){
    		$_SESSION['url'] = I('get.url');
    	}else if( !isset($_SESSION['url']) ){
    		$_SESSION['url'] = U('index');
    	}
    	$this->display('login');
    }
    
    public function loginHandle(){
    	$data = I('post.');
    	if(strlen($data['login_name']) <4 || strlen($data['login_passwd']) <6 ){
    		$this->error('用户名或密码过短');
    	}
    	if (!$user = M('user')->where($data)->find()){
    		
    		if(isset($_SESSION['url'])) unset ($_SESSION['url']);
    		$this->error('登陆失败');
    	}else{
    		if($user['status'] != 0 ){
    			if(isset($_SESSION['url'])) unset ($_SESSION['url']);
    			$this->error('账号待审核');
    		}
    		$_SESSION['time']= time();
    		$_SESSION['user']=$user['name'];
    		$_SESSION['id']=$user['id'];
			$_SESSION['sector']=$user['sector_id'];
// 			$access = M('access')->where('`sector_id`='.$user['sector_id'])->select();
// 			$node_id = array_column($access,'node_id');
			$args = 'id,name,hct_node.level,pid,status';
			$node = M('node')->field($args)->join('`hct_access` ON `hct_node`.`id` = `hct_access`.`node_id`')
				->where('`hct_access`.`sector_id`='.$user['sector_id'])->select();
			
			$data= array();
			foreach ($node as $k => $i){
				if (1 == $i['level'] && $i['status']){
					$data[$i['name']] = array();
					foreach ($node as $kk => $j){
						if($j['pid'] == $i['id'] && $j['status']){
							$data[$i['name']][$j['name']] = array();
							foreach ($node as $kkk => $n){
								if($n['pid'] == $j['id']){
									$data[$i['name']][$j['name']][$n['name']]=$n['status'];
									unset ($node[$kkk]);
								}
							}
							unset ($node[$kk]);
						}
					}
					unset ($node[$k]);
				}
			}
			$_SESSION['access'] = $data;
// 			parray($_SESSION['access']);$this->display('Index/null');return;
    		
			
    		if (isset($_SESSION['url']) && $_SESSION['url'] != U('Index/login')){
    			$url = $_SESSION['url'];
    			unset ($_SESSION['url']);
    		}else{
    			$url = U("Index/index");
    		}
    		if(isset($_SESSION['url'])) unset ($_SESSION['url']);
    		// 			parray($_SESSION);
    		$this->success('登陆成功',$url);
    	}
    }
    public function loginoutHandle(){
//     	session_start();
    	session_destroy();
    	$this->success('退出成功');
    }
    
    public function register(){
    	session_destroy();
    	$this->url = U('registerHandle');
    	$this->title = "用户注册";
    	$sector = new sectorModel();
    	$this->sector = $sector->selectData();    	
    	$this->display();
    }
    
    public function registerHandle(){
    	$data = I("post.");
    	$data['status'] = 1; #注册的初始状态是带审核的
    	$data['register_time'] = time();
    	if($data["relogin_passwd"] != $data["login_passwd"]){
    		$this->error("密码不一致");
    	}
    	$user = new userModel();
    	
    	if(!$user->create()){
    		$this->error($user->getError());
    	}
    	$data['pinyin'] = \Common\MyClass\CUtf8_PY::encode($data['name']);
    	if (M('user')->where("login_name='".$data["login_name"]."'")->find()){
    		$this->error('账号已经存在');
    	}
//     	parray($data);die;
    	if ($user->addData($data)){
    		$this->success("注册成功,待审核",U('index'));
    	}else{
    		$this->error('注册失败:'.$user->getError());
    	}
    }
   

    public function clean(){
    	echo getcwd() . "<br/>";
    	echo APP_PATH."Runtime";
    	echo '<br/>';
     	delDirAndFile( APP_PATH."Runtime");
    }
    
}