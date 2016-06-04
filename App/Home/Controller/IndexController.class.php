<?php
namespace Home\Controller;
//use Home\Model\UserModel;
//use Think\Model;
use Think\Upload;

use Think\Controller;

class IndexController extends Controller {

	
    public function index(){
   // 	echo U();
    	parray($_GET);
    	 
//    	$this->display();
    	 
    //    $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
      }
	public function model(){
// 		$user = new Model('users');
// 		echo "<pre>";
// 		dump($user->select());
// 		echo "</pre>";

// 		$user = new UserModel("users");
// 		echo "<pre>";
// 		dump($user->select());
// 		echo "</pre>";

		$user=M('users');
		$ary=array(
				'id'=>"3",
				);
		
		$map['id']=array('in','3,4,5');
		echo "<pre>";
		dump($user->where("id=3")->select());
		echo "--------";
		dump($user->where($ary)->select());
		echo "--------";
		dump($user->where($map)->select());
		
		echo "</pre>";		
	}

	public function upload(){
		//echo "upload";
		$upload = new Upload();
		$upload->maxSize = 3145728;
	//	$upload->exts = array("text");
		$upload->savePath = './';
	//	$upload->subName = "get_user_id";
		//$upload->savaName = "";
		$info = $upload->upload();
		if ($info){
		//	$this->success("上传成功");
			echo "<pre>";
			foreach ($info as $file){
				echo $file['key'].'<br />';
				echo $file['savepath'].'<br />';
				echo $file['name'].'<br />';
				echo $file['savename'].'<br />';
				echo $file['size'].'<br />';
				echo $file['type'].'<br />';
				echo $file['ext'].'<br />';
				echo $file['md5'].'<br />';
				echo $file['sha1'].'<br />';
				
			}
			echo "</pre>";
		}else{
			$this->error($upload->getError());
		}
	}

}