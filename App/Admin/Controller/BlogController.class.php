<?php
namespace Admin\Controller;
use Think\Controller;


class BlogController extends Controller{
	
	
	
	public function index(){
//		echo ACTION_NAME;die;
	//	$field = array('id','title','content','time','click');
		// relation 中选择表名 会只关联对应表
// 		$field = array('del');
// 		//读取没有被标记的博文
// 		$where = array('del'=>0);
// 		$this->blog = D('BlogRelation')->field($field,true)->where($where)->relation(true)->select();
// 		parray($blog);

		$this->blog = D('BlogRelation')->getBlogs(0);
		$this->display();
	}
	
	//回收站删除/还原
	public function toTrach(){
		$get=I('get.');
		if(!isset($get['del'])){
			$this->error('严重错误');
		}
		$update = array(
				'id' => (int) $get['id'],
				'del' => $get['del'],
				);
		$str=$get['del']?"删除":"恢复";
		$url=$get['del']?"index":"trach";
		if(M('blog')->save($update)){
			$this->success('已'.$str.'成功',U($url));
		}else{
			$this->error('失败');
		}
	}
	
	//回收站
	public function trach(){
		$this->blog = D('BlogRelation')->getBlogs(1);
		$this->display('index');
	}
	public function EmptyTrach(){
		echo "暂未实现";
	}
	
	public function delete(){
		$id = (int) I('get.id');
		D('BlogRelation')->relation(true)->delete($id);
		//或直接用下面
// 		if(M('blog')->delete($id)){
// 			M('blog_attr')->where(array('bid'=>$id))->delete();
// 			$this->success('成功',U('trach'));
// 		}else{
// 			$this->error('失败');
// 		}
		$this->success('成功',U('trach'));
	}
	
	public function addBlog(){
		$cate = M('cate')->order('sort')->select();
		$this->cate = \Common\Api\CategoryApi::unlimitedForLevel($cate);
		$this->attr = M('attr')->select();
		$this->display();
	}
	
	public function runAddBlog(){
//		$from=I("post."); //I会转义掉html标签.
		$from=$_POST;
		$data = array(
				'title' => $from['title'],
				'summary' => $from['summary'],
				'content' => $from['content'],
				'click' => (int)$from['click'],
				'time' => time(),
				'cid' => (int) $from['cid'],
				);
		if (isset($from['aid'])){
			$data['attr']=array();
			foreach ($from['aid'] as $v){
				$data['attr'][] = $v;
			}
		}
		parray($data);
		D('BlogRelation')->relation(true)->add($data);
		$this->display('null');
// 		echo oooo;die;
// 		$this->success('成功',U('index'));
	}
	//编辑器图片上传处理
	public function uploadimg(){
		echo 11;
	}
	
	
}
