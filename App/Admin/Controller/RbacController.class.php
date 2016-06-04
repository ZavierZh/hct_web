<?php
namespace Admin\Controller;


//use Admin\Model\UserRelationModel;

use Think\Controller;
class RbacController extends Controller {
	/*
	 CREATE TABLE IF NOT EXISTS `think_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `think_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `think_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `think_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	  
	 * */

	public function index(){
	//	$this->display();
		$this->userlist();
	}
	public  function login(){
		$this->display();
	}
	//用户列表
	public function userlist(){
//		$m = new UserRelationModel();
//		$result = $m->field('passwd',true)->relation(true)->select();	
		$result = D('UserRelation')->field('passwd',true)->relation(true)->select();
		//echo 'this is user list';
//		parray($result);die;
//		$this->user = M('user')->select();
//		parray($this->user);die;
//		$this->role = M('role_user')->select();
		$this->user = $result;
		$this->display('userlist');
	}
	//角色列表
	public function rolelist(){
		$this->role = M("role")->select();
		//dump($role);
		$this->display('rolelist');
	}
	//节点列表
	public function nodelist(){
//		echo 'this is node list';
		$node = M("node")->order("sort")->select();
		$node = node_merge($node);
	//	dump($node);die;
		$this->node = $node;
		$this->display();
	}
	//添加用户
	public function addUser(){
		//echo 'this is addUser';
		$this->role = M('role')->select();
		$this->display();
	}
	public function addUserHandle(){		
		$post=I('post.');
		$role_arr=$post['role_id'];

		$i=0; // 检查是否选择角色
		foreach ($role_arr as $v){
			$i++;
			if (empty($v)){
				$this->error("第".$i."角色选择为空");	
				die;
			}
		}
		$i = RepeatMemberInArray ( $role_arr );
		if($i){
			$this->error("第".($i+1)."角色重复");
			die;
		}
			
		if($uid = M('user')->add($post)){
			echo $uid;
			foreach ($role_arr as $v){
				$role[] = array(
						'role_id' => $v,
						'user_id' => $uid,
						);
			}
			if(M('role_user')->addAll($role)){
				$this->success("成功",U("userlist"));
			}else{
				$this->error("失败");
			}
		}else{
			$this->error("失败");
		}
	}
	//添加角色
	public function addRole(){
	//	echo 'this is addRole';
		$this->display();	
	}
	public function addRoleHandle(){
		dump(I('post.'));
		if (M('role')->add(I('post.'))){
			$this->success("添加成功",U('rolelist'));
		}else{
			$this->error("添加失败");
		}
	}
	//添加节点
	public function addNode(){
		//echo 'this is addNode';
		//dump($_GET);die;
	//	echo I("get.level",1,"intval");die;
		$this->pid = I("get.pid",0,"intval");
		$this->level=I("get.level",1,"intval");
	//	echo $this->level;die;
		switch($this->level){
			case '1':
				$this->type = "应用";
				break;
			case '2':
				$this->type = "控制器	";
				break;
			case '3':
				$this->type = "动作方法";
				break;
		}
	//	echo $this->type;die;
		$this->display();
	}
	public function addNodeHandle(){
		dump(I('post.'));
		if (M('node')->add(I('post.'))){
			$this->success("添加成功",U('nodelist'));
		}else{
			$this->error("添加失败");
		}		
	}
	//配置权限
	public function access(){
		$this->rid = I('rid',0,'intval');
		$field = array('id','name','title','pid');
		$node = M("node")->order("sort")->field($field)->select();
		$access = M('access')->where(array('role_id'=>$this->rid))->getField('node_id',true);

		$this->node = node_merge($node,$access);
//		parray($this->node);die;
		$this->display();
	}
	public function setAccess(){
	//	dump($_POST);
		$rid = I('rid',0,'intval');
		$data = array();
		$access=I('post.access');
		foreach($access as $v){
			$tmp = explode('_', $v);
			$data[] = array(
					'role_id' => $rid,
					'node_id' => $tmp[0],
					'level' => $tmp[1],
					);
		}
//		dump($data);		
		$db = M('access');
		//清空原权限
		$db->where(array('role_id' => $rid))->delete();
		//插入新权限
		if ($db->addAll($data)){
			$this->success('修改成功',U('Admin/Rbac/rolelist'));		
		}else{
			$this->error('修改失败');
		}
	}
}

