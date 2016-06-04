<?php
namespace Gitweb\Controller;

use Think\Model;

use Home\Controller\AuthCheckController;

class BuildController extends AuthCheckController{
	public $PC_TYPE=array(
			'start' => 1, //程序准备中
			'wait'  => 2, //等待服务器发送消息
			'run'   => 3, //运行中
			'lock'  => 4, //服务器锁定
			'clilock'=>5, //客户端锁定
			'error' => 6, //
			'finish'=> 7, //编译完成
			'close' => 8, //运行被手动终止
			'failed'=> 9, //编译失败/设置路径失败/...
	);
	public function index(){
		// 莫名其妙 得用 assign 
		$this->assign('PC_TYPE',$this->PC_TYPE);
		$this->build = M('build')
			->field('hct_build.*,hct_user.name')
			->join('LEFT JOIN hct_user ON hct_user.id = hct_build.user_id')
			->order("hct_build.id DESC")->limit(15)->select();
		
		$this->wait_build = M("wait_build")
			->field('hct_wait_build.*,hct_user.name')
			->where("`stat` in (1,3)")
			->join('LEFT JOIN hct_user ON hct_user.id = hct_wait_build.user_id')
			->order("hct_wait_build.sort DESC,hct_wait_build.id")->limit(15)->select();
		$this->login_id = $_SESSION['id'];
		$this->url = U("addBuild");
		
		$this->display();
		
	}
	
	public function addBuild(){
		$this->checkAuth(U('index'),true,'addBuild');
		$this->url = U('addBuildHandle');
		$this->title = "增加编译项目";
		$user = M('user')->field('email_passwd')->find($_SESSION['id']);
		if($user['email_passwd'] == ''){
			$this->noemail = "没有登记email密码,在编译完成时,将不能发邮件提醒!";
		}
		$this->display();
	}
	
	public function addBuildHandle($path){
		$this->checkAuth(U('index'),true,'addBuild');
		$data = I('post.');
		if(strlen($data['path']) < 15){
			$this->error("项目长度少于15个");
		}
		$path = explode('/', $data['path']);
		if(count($path) == 5){
			if($path[1] != "dists" || $path[2] != "targets"){
				$this->error("项目设置不正确");
			}
			$data['path'] = $path[0]."/".$path[3]."/".$path[4];
		}else if(count($path) != 3){
			$this->error("项目设置不正确");
		}	
		$data['time'] = time();
		$data['user_id'] = $_SESSION['id'];
		$data['stat'] = 1;
		$data['sort'] = isset($data['urgent'])? 1 : 0;
// 		parray($data);die;
		if(M("wait_build")->add($data)){
			$this->success("增加成功");
		}
// 		return;
		$client = new \swoole_client(SWOOLE_SOCK_TCP);
		if (!$client->connect('127.0.0.1', 9102, -1)){
			$this->error("connect failed. Error: {$client->errCode}\n");
		}
		$client->send("add_build\n");
		//parray( $client->recv());
		$client->close();
	}

	public function editBuild($id){
		// 		$this->checkAuth(U('index'),true,'addBuild');
		if(!ctype_digit($id)){
			$this->error('非法!');
		}
		$this->url = U('editBuildHandle');
		$this->title = "编辑等待的编译项目";
		//@TODO
		# 数据库中如果stat 还是1 就锁定改为2,并返回 true, 否则不改变并就返回 false
		$mysqli =new \mysqli(C('DB_HOST'),C('DB_USER'),C("DB_PWD"),"hct_manage");
		if (mysqli_connect_errno()) {
			$this->error("连接失败: ".mysqli_connect_error()."\n");
		}
		$mysqli->query("set names 'utf8'");
		$sql = "set @stat:=0;
		update `hct_wait_build` set
		`stat` = (if(stat=1,(select @stat:=3),(select @stat:=stat)))
		where `id` = ".$id." limit 1;
		select id,user_id,stat,path,sort from hct_wait_build where id = ".$id." ;";
		if ($mysqli->multi_query($sql)) {
			do {
		//while ($row = $result->fetch_row()) {  }
				if ($result = $mysqli->store_result()) {
					$data = $result->fetch_row() ;
					$result->close();
				}
			} while ($mysqli->next_result());
		}
		$mysqli->close();
// 		parray($data);
// 		die;
		if ($data[1] != $_SESSION['id']){
			$this->error("非法 !!");
		}
		if ($data[2] != 3){
			$this->error('锁定项目失败,可能正在编译或将要编译');
		}
		$this->data = $data;
		$user = M('user')->field('email_passwd')->find($_SESSION['id']);
		if($user['email_passwd'] == ''){
			$this->noemail = "没有登记email密码,在编译完成时,将不能发邮件提醒!";
		}
		$this->display('addBuild');
	}
	
	public function editBuildHandle($id){
		$this->checkAuth(U('index'),true,'editBuild');
		if(!ctype_digit($id)){
			$this->error('非法!');
		}
		$data = I('post.');
		//获得状态;
		$wait_build = new Model('wait_build');
		$waitdata = $wait_build->where('stat=3')->find($id);
		if ( !$waitdata || $waitdata['user_id'] != $_SESSION['id']){
			$this->error('非法!');
		}
		// 用于直接解锁项目
		if(isset($data['unlock'])){
			$wait_build->where(array('stat'=>3,'id'=>$id))->setField('stat',1);
			$this->ajaxReturn(array('type'=>'success'));
			return;
		}
		// 修改数据库
		$path = explode('/', $data['path']);
		if(count($path) == 5){
			if($path[1] != "dists" || $path[2] != "targets"){
				$this->error("项目设置不正确");
			}
			$data['path'] = $path[0]."/".$path[3]."/".$path[4];
		}else if(count($path) != 3){
			$this->error("项目设置不正确");
		}

		$data['time'] = time();
		$data['stat'] = 1;
		if (!isset($data['urgent'])){
			$data['sort'] = 0;
		}else{
			if($waitdata['sort'] == 0){
				$data['sort'] = 1;
			}
		}
		if($wait_build->save($data)){
			$this->success('修改成功');
		}else{
			$this->error('修改失败');
		}
	}
	
	public function delBuildHandle($id){
		$this->checkAuth(U('index'),true,'delBuild');
		if(!ctype_digit($id)){
			$this->error('非法!');
		}
		$wait_build = new Model('wait_build');
		$waitdata = $wait_build->where('stat=3')->find($id);
		if ( !$waitdata || $waitdata['user_id'] != $_SESSION['id']){
			$this->ajaxReturn(array('type'=>'failed','context'=>'非法!'));
			return;
		}
		if($wait_build->where(array('stat'=>array('IN','1,3'),'id'=>$id))->delete()){
			$this->ajaxReturn(array('type'=>'success'));
		}else{
			$this->ajaxReturn(array('type'=>'failed','context'=>'删除失败,可能已在编译或编译完'));
		}
	}
	public function showMoreBuild($id){
// 		parray($this->PC_TYPE);
		$this->assign('PC_TYPE',$this->PC_TYPE);
		
		if(!ctype_digit($id)){
			$this->error('非法!');
		}
		$this->data = M('build')->find($id);
		if ( ! $this->data){
			$this->error('没有找到');
		}
// 		parray($this->data);
		$this->title = "项目编译详情";
		$this->display();
	}
	
}