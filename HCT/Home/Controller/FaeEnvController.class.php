<?php
namespace Home\Controller;
use Admin\Model\faeuserModel;

use Home\Controller\AuthCheckController;
use Admin\Model\userModel;
use Admin\Model\faedebugModel;

use Admin\Model\faepcModel;
class FaeEnvController extends AuthCheckController {
    
    public function index(){
    	$this->title = "环境列表";
    	$this->url = U('addFaeEnv');
    	$fae_counts = M()->query('SELECT COUNT(*) FROM `hct_faeenv` WHERE `isdel` = 0;');
    	$this->total_counts =  $fae_counts[0]['count(*)']?$fae_counts[0]['count(*)']:0;
    	 
    	$this->pageSize =  (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:20;
    	$this->pageCurrent = (isset($_GET['pgc']) && $_GET['pgc']>=0) ? (int)$_GET['pgc']:1;
    	$this->display();
    }
    /*
     * 用于页面返回
    */
    public function FaeEnvlist(){
    	if ($_GET['isdel'] == '1'){
    		$isdel = 1;
    	} else { $isdel = 0;
    	}
    	$this->showurl=U('showFaeEnv');
    	$pgt = (isset($_GET['pgt']) && $_GET['pgt']>=0) ? (int)$_GET['pgt']:0;
    	$pgs = (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:20;
    
    	$fae_counts = M()->query('SELECT COUNT(*) FROM `hct_faeenv` WHERE `isdel` = '.$isdel.';');
    	//parray( $fae_counts[0]);
    	//$fae_counts=array_values($fae_counts[0]);
    	$this->total_counts =  $fae_counts[0]['count(*)'];
    	$data=M('faeenv')
    	->where(array('isdel'=>0))->order('`sort` DESC,`reply_time` DESC,`date` DESC,`id` DESC')
    	->limit($pgt,$pgs)->select();
    	 
    	$user = new userModel();
    	$this->userlist = $user->selectData();
    	$this->user = turnArray($this->userlist,'id,name');
    	 
    	$faepc = new faepcModel();
    	$this->faepclist = $faepc->selectData();
    	$this->faepc = turnArray($this->faepclist,array('id','faepc'));
    	$this->data=$data;
    
    	$this->display();
    }
    
    //增加FAE环境
    public function addFaeEnv(){
    	//parray( $_GET);die;
    	$this->checkAuth('',true);
    	$this->title = "增加FAE环境";
    	$this->url = U("addFaeEnvHandle",$_GET);
    	$user = new userModel();
    	$this->user = $user->selectData();
    	$faepc = new faepcModel();
    	$this->faepc = $faepc->selectData();
    	$this->display();
    }
    public function addFaeEnvHandle(){
    	//parray($_SESSION['access']);die;
    	$this->checkAuth(U('index'),true,'addFaeEnv');
    	if (!IS_POST)
    		$this->error('不存在');
    	if (!$_POST['faepc_id'] || !$_POST['path'] || !$_POST['date'] ){
    		$this->error('缺少必要的参数');
    	}
    	$data=I('post.');
    	$data['reply_time'] = time();
    	if (M('faeenv')->add($data)){
   		$this->success('添加成功',U('index'));
    	}else{
    		$this->error('添加失败');
    	}
    }
    
    public function editFaeEnv($id){
    	$this->checkAuth('',true);
    	$this->title = "编辑FAE环境";
    	$this->url = U("editFaeEnvHandle",$_GET);
    	$user = new userModel();
    	$this->user = $user->selectData();
    	$faepc = new faepcModel();
    	$this->faepc = $faepc->selectData();
    		
    	$this->data = M('faeenv')->find($id);
    	//parray($this->data);
    
    	if ($this->data){
    		$this->display('addFaeEnv');
    	}else{
    		$this->error("出错");
    	}
    }
    public function editFaeEnvHandle(){
    	$this->checkAuth('',true,'editFaeEnv');
    	if (!$_POST['faepc_id'] || !$_POST['path'] || !$_POST['date'] ){
    		$this->error('缺少必要的参数');
    	}
    	$data=I('post.');
    	if (M('faeenv')->save($data)){
    		$this->success('修改成功',U('index'));
    	}else{
    		$this->error('修改失败');
    	}
    }
    //FAE环境回收站
    public function FaeEnvTrach(){
//     	$this->checkAuth('',true);
    	$this->title = "FAE环境回收站";
    	$this->url = U('toFaeEnvTrach');
    	$user = new userModel();
    	$this->user = $user->selectData();
    	$faepc = new faepcModel();
    	$this->faepc = $faepc->selectData();
    	$this->user = turnArray($user,'id,name');
    	$this->faepc = turnArray($faepc,array('id','faepc'));
    	$data=M('faeenv')->where(array('isdel'=>1))->select();
    	//	parray($data);
    	$this->data=$data;
    	$this->display();
    }
    //将环境移动到回收站
    public function toFaeEnvTrachHandle($id){
    	$this->checkAuth('',true,'delFaeEnv');
    	 
    	$data['id'] = array('IN',$id);
    	 
    	if (M('faeenv')->where($data)->setField('isdel',1)){
    		$this->ajaxReturn(array("type"=>"success"));
    	}else{
    		$this->ajaxReturn(array("type"=>"failed"));
    	}
    }
    // 恢复到环境
    public function recoveryFaeEnvHandle($id){
    	$this->checkAuth('',true,'addFaeEnv');
    	$data['id'] = array('IN',$id);
    	if (M('faeenv')->where($data)->setField('isdel',0)){
    		$this->ajaxReturn(array("type"=>"success"));
    	}else{
    		$this->ajaxReturn(array("type"=>"failed"));
    	}
    }
    //@TODO 环境记录条数 是否考虑删除,或者 单删将 task 表faeenv_id 置 0
    public function delFaeEnvTrachHandle($id){
    	$this->checkAuth('',true,'delFaeEnv');
    	if (M('faeenv')->delete($id)){
    		$this->ajaxReturn(array("type"=>"success"));
    	}else{
    		$this->ajaxReturn(array("type"=>"failed"));
    	}
    }
    
    /*
     * 查
    */
    public function searchFaeEnv(){
    	$this->display();
    }
    
    public function searchFaeEnvHandle(){
    	// 		parray($_GET);
    	$s = array();
    	$get = I('get.');
    
    	$data['faepc_id'] = array_filter($get['faepc_id']);
    	$data['user_id'] = array_filter($get['user_id']);
    
    	if ($data['faepc_id']){
    		$s['faepc_id'] =array("IN", $data['faepc_id']);
    	}
    	if ($data['user_id']){
    		$s['user_id'] =array("IN", $data['user_id']);
    	}
    	if ($get['s_path']){
    		$s['path'] = array('LIKE', '%'.$get["s_path"].'%');
    	}
    
    	if ($get['date_start'] && $get['date_end']){
    		$s['date'] = array('BETWEEN', array($get['date_start'],$get['date_end']));
    	}else if ($get['date_start']) {
    		//大于等于这个日期
    		$s['date'] = array('EGT', $get['date_start']);
    	}else if ($get['date_end']){
    		//小于等于这个日期
    		$s['date'] = array('ELT', $get['date_end']);
    	}
    
    	if ($get['sub']){
    		$s['sub'] = array('LIKE','%'.$get['sub'].'%');
    	}
    	if ($get['main']){
    		$s['main'] = array('LIKE','%'.$get['main'].'%');
    	}
    	if ($get['hct']){
    		$s['hct'] = array('LIKE','%'.$get['hct'].'%');
    	}
    	if ($get['path']){
    		$s['path'] = array('LIKE','%'.$get['path'].'%');
    	}
    	if (!$s) return;
    	if ($_GET['isdel'] == '1'){
    		$s['isdel'] = 1;
    	}
    	else { $s['isdel'] = 0;
    	}
    
    	$total_counts = M('faeenv')->where($s)->count();
    	$this->total_counts = $total_counts;
    
    	$pgt = (isset($_GET['pgt']) && $_GET['pgt']>=0) ? (int)$_GET['pgt']:0;
    	$pgs = (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:20;
    
    	$data = M('faeenv')->where($s)->field('hct,main,comment,pm_name',true)
    	->limit($pgt,$pgs)->select();
    
    	$user = new userModel();
    	$this->userlist = $user->selectData();
    	$this->user = turnArray($this->userlist,'id,name');
    		
    	$faepc = new faepcModel();
    	$this->faepclist = $faepc->selectData();
    	$this->faepc = turnArray($this->faepclist,array('id','faepc'));
    	$this->data=$data;
    	$this->showurl=U('showFaeEnv');
    	 
    	$this->display('FaeEnvlist');
    }
    
    public function changeEnv(){
    	$this->title = "环境列表";
    	$fae_counts = M()->query('SELECT COUNT(*) FROM `hct_faeenv` WHERE `isdel` = 0;');
    	$this->total_counts =  $fae_counts[0]['count(*)']?$fae_counts[0]['count(*)']:0;
    
    	$this->pageSize =  (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:20;
    	$this->pageCurrent = (isset($_GET['pgc']) && $_GET['pgc']>=0) ? (int)$_GET['pgc']:1;
    	 
    	$this->display();
    }
    public function changeEnvHandle(){
    	if(!isset($_POST['task_id']) || !isset($_POST['faeenv_id'])){
    		$this->ajaxReturn(array("type"=>"failed","context"=>"unkown error"));
    	}
    	if(M('task')->where("`id`=".(int)$_POST['task_id'])->limit(1)->setField('faeenv_id',(int)$_POST['faeenv_id'])){
    		$this->ajaxReturn(array("type"=>"success"));
    	}else{
    		$this->ajaxReturn(array("type"=>"error","context"=>"修改失败"));
    	}
    }
    /**
     * 删除用的?
     */
    public function EnvList(){
    	if ($_GET['isdel'] == '1'){
    		$isdel = 1;
    	}else { $isdel = 0;
    	}
    	$pgt = (isset($_GET['pgt']) && $_GET['pgt']>=0) ? (int)$_GET['pgt']:0;
    	$pgs = (isset($_GET['pgs']) && $_GET['pgs']>=0) ? (int)$_GET['pgs']:20;
    	 
    	$fae_counts = M()->query('SELECT COUNT(*) FROM `hct_faeenv` WHERE `isdel` = '.$isdel.';');
    	//parray( $fae_counts[0]);
    	//$fae_counts=array_values($fae_counts[0]);
    	$this->total_counts =  $fae_counts[0]['count(*)'];
    	$data=M('faeenv')->field('hct,main,comment,pm_name',true)
    	->where(array('isdel'=>0))->order('date desc,id desc')
    	->limit($pgt,$pgs)->select();
    
    	$user = new userModel();
    	$this->userlist = $user->selectData();
    	$this->user = turnArray($this->userlist,'id,name');
    
    	$faepc = new faepcModel();
    	$this->faepclist = $faepc->selectData();
    	$this->faepc = turnArray($this->faepclist,array('id','faepc'));
    	$this->data=$data;
    	 
    	$this->display();
    }
    
    public function showFaeEnv($id){
    	if(!ctype_digit($id)){
    		$this->error('非法!');
    	}
    	$id = (int)$id;
    	if(!$env = M('faeenv')->where('isdel=0')->find($id)){
			$this->error('环境不存在,或已被删除');
    	}
    	if($env['user_id']){
    		$this->user = M('user')->find($env['user_id']);
    	}
    	$this->title = "环境详情";
    	$faepc = M('faepc')->field('faepc')->find($env['faepc_id']);
    	$this->faepc = $faepc['faepc'];
    	$this->env=$env;
    	$this->faedebug = M('faedebug')
    		->field('`hct_faedebug`.`id`,`user_id`,`faeuser_id`,`hct_faedebug`.`time`,path,`hct_faedebug`.`comment`,`hct_user`.`name`,`hct_faeuser`.`name` as `faename`,`hct_faeuser`.`email`,`hct_faeuser`.`phone`,company,isok')
    		->where('`faeenv_id`='.$id)
    		->order('`hct_faedebug`.`time` DESC,`hct_faedebug`.`id` DESC')
    		->join('LEFT JOIN `hct_user` ON `hct_faedebug`.`user_id` = `hct_user`.`id`')
    		->join('LEFT JOIN `hct_faeuser` ON `hct_faedebug`.`faeuser_id` =  `hct_faeuser`.`id`')
    		->select();
//     	parray($this->faedebug);die;
    	$this->display();
    }
    
    public function addFaeEnvRecord($id){
    	$this->checkAuth(U('index'),true);
    	$this->url = U('addFaeEnvReacordHandle');
    	$this->title = "增加环境调试记录";
    	$id = (int)$id;
    	if($id <=0) {
    		$this->error('错误的任务');
    		return;
    	}
    	$faeuser = new faeuserModel();
    	$this->faeuser = $faeuser->selectData();
    	$this->faeenv_id = (int) $id;
    	
    	$this->display();
    }
    
    public function addFaeEnvReacordHandle(){
    	
//     	parray($_POST);die;
    	$this->checkAuth(U('index'),true,'addFaeEnvRecord');
    	$data = I('post.');
    	if(!$data['path'] && !$data['comment']){
    		$this->error('临时版本路径或说明必须有一项');
    	}
    	if($data['issendemail']){
    		if(strlen($data['path'])<10){
    			$this->error('路径太少');
    		}
    	}
    	$faedebug = new faedebugModel();
    	$data['time'] = time();
    	$data['faeenv_id'] = (int)$data['faeenv_id'];
    	if(!$data['faeenv_id']){
    		$this->error("任务没有指明FAE区环境");
    	}
    	$faeenv = M('faeenv');
    	if(!$faeenv->where('`isdel`=0')->find($data['faeenv_id'])){
    		$this->error('没有发现环境');
    	}
    	
    	if(!$faedebug->create($data)){
    		$this->error($faedebug->getError());
    	}
    	$data['user_id']=$_SESSION['id'];
    	 
    	if ($id=$faedebug->add($data)){
    		$faeenv->execute("update __FAEENV__ set `reply_count`=`reply_count`+1,`reply_time`=".time()." where `id`=".$data['faeenv_id']);
    		// 任务的id号
    		if($data['issendemail']){
    			$this->redirect('sendMail',array('id'=>$id));
    		}else{
    			$this->success('添加成功',U('index'));
    		}
    	}else{
    		$this->error('添加失败');	
    	}
    	
    }
    public function editFaeEnvRecord($id){
    	$this->checkAuth(U('index'),true);
    	if( ! ctype_digit($id)){
	     	$this->error('错误的调试记录');
	    }
    	$this->url = U('editFaeEnvRecordHandle');
    	$this->title = "增加环境调试记录";
    	$id = (int)$id;
    	$faeuser = new faeuserModel();
    	$this->faeuser = $faeuser->selectData();
		$this->data = M('faedebug')->find($id);
		if(!$this->data){
			$this->error("不存在的调试记录");
		}
    	$this->display('addFaeEnvRecord');
    }
    public function editFaeEnvRecordHandle($id){
    	$this->checkAuth(U('index'),true,'editFaeEnvRecord');
    	
	     if( ! ctype_digit($id)){
	     	$this->error('错误的调试记录');
	     }
	     $faedebug = new faedebugModel();
	     $faedebugdata = $faedebug->find($id);
	     if(!$faedebugdata){
	     	$this->error("不存在的调试记录");
	     }
	     //@TODO 这种考虑加入 非法修改log
	     if($faedebugdata['user_id'] != $_SESSION['id']){
	     	$this->error("非法的修改!!!");
	     }
	     $data = I('post.');
	     if(isset($data['faeenv_id'])) unset($data['faeenv_id']);
	     
	     if(!$data['path'] && !$data['comment']){
	     	$this->error('临时版本路径或说明必须有一项');
	     }
	     if($data['issendemail']){
	     	if(strlen($data['path'])<10){
	     		$this->error('路径太少');
	     	}
	     }
	     //@TODO 到底修改的时候要不要把时间提前
// 	     $data['time'] = time();
	      
	     if(!$faedebug->create($data)){
	     	$this->error($faedebug->getError());
	     }
	     if ($faedebug->save($data)){
	     	//@TODO 不修改回复时间faeenv?
// 	     	$faeenv->execute("update __FAEENV__ set `reply_count`=`reply_count`+1,`reply_time`=".time()." where `id`=".$data['faeenv_id']);
	     	// 任务的id号
	     	if($data['issendemail']){
	     		$this->redirect('sendMail',array('id'=>$id));
	     	}else{
	     		$this->success('修改成功',U('index'));
	     	}
	     }else{
	     	$this->error('修改失败,或没有修改');
	     }
	}
	public function delFaeEnvRecordHandle($id){
		$this->checkAuth('',true,'delFaeEnvRecord');
		$faedebug =M('faedebug') ; 
		if ($faedebug->delete($id)){
			$this->ajaxReturn(array("type"=>"success"));
		}else{
			$this->ajaxReturn(array("type"=>"failed",'context'=>$faedebug->getError()));
		}
	}
    public function sendMail($id){
    	$this->checkAuth();
    	$id = (int)$id;
    	$user=new userModel();
    	$faedebug = M('faedebug')
    		->field('hct_faedebug.*,hct_faeenv.user_id')
    		->join('LEFT JOIN hct_faeenv ON hct_faedebug.faeenv_id = hct_faeenv.id')
    		->limit(1)
    		->where("`hct_faedebug`.`id`=".$id)
    		->find();
//     	parray($faedebug);die;
		if($faedebug['isok']>0){
			$this->error("已经发送过邮件");
		}
    	$this->user_data=$user->selectData();
    	$this->user=turnArray($this->user_data,'id,name');
    	$this->faedebug = $faedebug;
    	$this->path = ($str=strrchr($faedebug['path'], '/'))?substr($str,1):$faedebug['path'];
    	$this->title = "发送邮件";
    	$this->url = U('sendMailHandle');
    	$this->display("sendMail");
    }
    public function sendMailHandle(){
//     	parray($_POST);
    	$data = I('post.');
    	$debug = M('faedebug');
    	$faedebug = $debug->find((int)$data['id']);
    	if(!faedebug){
    		$this->error('环境错误');
    	}
//     	echo $faedebug['path']."<br>";
//     	echo $faedebug['user_id']."<br>";
//     	echo $_SESSION['id']."<br>";
// 		echo isAuth('Home/FaeEnv/addFaeEnv');
// 		die;
    	if(!$faedebug['path'] || ($faedebug['user_id'] != $_SESSION['id']) || !isAuth('Home/FaeEnv/addFaeEnv')){
    		$this->error('非法!');
    	}
    	$data['id'] = (int)$data['id'];
    	if (!ctype_digit($data['to'])){
    		$this->error('收件人错误');
    	}
    	$ids = $data['to'];
    	if ($data['cc'])
    		$ids = $ids.",".implode(',',$data['cc']);
//     	parray($ids);
		if(!preg_match('/([0-9]+[,]?)+/i', $ids)){
			$this->error('收件人或抄送有错误');
		}
		$user = M('user');
    	$userdata = $user->field('id,name,email')->where("`id` IN ($ids)")->select();
    	$no_email = array();
    	$mail = new \Common\MyClass\SendMail();
    	   
    	foreach ($userdata as $v){
    		if(!$v['email']){
    			$no_email[] = $v['name'];
    			continue;
    		}
    		if ($v['id'] == $data['to']){
    			$mail->setReceiver($v['email']);
    		}else{
    			$mail->setCc($v['email']);
    		}
    	}
    	if($no_email){
    		$this->error("没有登记邮箱:".implode(',', $no_email));
    	}
    	$login_user = $user->find($_SESSION['id']);
//     	parray($login_user);die;
    	if(!$mail->setUser($login_user['email'],$login_user['email_passwd'],$login_user['name'])){
    		$this->error('账号email信息不完整');
    	}
    	
    	$body = "<table cellspacing='0' cols='2' border='0'>
	<colgroup width='124'></colgroup>
	<colgroup width='745'></colgroup>
	<tbody><tr>
		<td style='border: 1px solid #000000;' colspan='2' height='86' align='CENTER' valign='MIDDLE'><b><font size='4'>临时版本详细信息</font></b></td>
		</tr>
	<tr>
		<td style='border: 1px solid #000000;' min-height='64' align='LEFT' valign='MIDDLE'>说明</td>
		<td style='border: 1px solid #000000;' align='LEFT'><pre>".$faedebug['comment']."</pre></td>
	</tr>
	<tr>
		<td style='border: 1px solid #000000;' height='65' align='LEFT' valign='MIDDLE'>临时版本</td>
		<td style='border: 1px solid #000000;' align='LEFT'>".$faedebug['path']."<br></td>
	</tr>
	<tr>
		<td style='border: 1px solid #000000;' height='56' align='LEFT' valign='MIDDLE'>补充</td>
		<td style='border: 1px solid #000000;' align='LEFT'>".$data['comment']."</td>
	</tr>
	<tr>
		<td style='border: 1px solid #000000;' height='34' align='LEFT' valign='MIDDLE'>链接</td>
		<td style='border: 1px solid #000000;' align='LEFT'>FAE区环境<a href='http://192.168.3.109".U('Home/FaeEnv/showFaeEnv',array('id'=>$faedebug['faeenv_id']))."'>点击</a>[编号:".$faedebug['id']."]<br></td>
	</tr>
</tbody></table>";
//     	parray($body);
    	$mail->setMail($data['subject'], $body);
    	if($mail->sendMail()){
    		$debug->where("`id`=".$data['id'])->setField('isok',1);
    		$this->success("邮件发送成功");
    	}else{
    		$this->error("邮件发送失败:".$mail->getError());
    	}
    }
}