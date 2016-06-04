<?php
namespace Common\Api;

class CategoryApi{

	//递归 一维数组
	static public function unlimitedForLevel($cate,$pid=0,$level=0,$html='&nbsp;&nbsp;|--'){
		$arr=array();
		foreach ($cate as $k=>$v){
			if ($v['pid'] == $pid){
				$v['level'] = $level + 1;
				$v['html'] = str_repeat($html, $level);
				$arr[] = $v;
				unset($cate[$k]);
				//			echo count($cate)."<br/>";
				$arr = array_merge($arr, unlimitedForLevel($cate,$v['id'],$level+1));
			}
		}
		return $arr;
	}
	//完成 1 >> 2 >> 3 这种路经的数组 一维数组
	//传递子id ,返回所有父类id
	static public function getParentsID($cate,$id){
		$arr = array();
		foreach ($cate as $v){
			if($v['id'] == $id){
				$arr[]=$v;
				$arr=array_merge(self::getParentsID($cate, $v['pid']),$arr);
	//			return $arr;
			}
		}
		return $arr;
	}
	//传递父id,返回所有子id 一维数组
	static public function getChildsID($cate,$pid){
		$arr = array();
		foreach ($cate as $v){
			if ($v['pid'] == $pid){
				$arr[] = $v['id'];
				$arr =array_merge($arr,self::getChildsID($cate, $v['id']));
			}
		}
		return $arr;
	}
	//传递父id,返回所有子级分类  一维数组
	static public function getChilds($cate,$pid){
		$arr = array();
		foreach ($cate as $v){
			if ($v['pid'] == $pid){
				$arr[] = $v;
				$arr =array_merge($arr,self::getChildsID($cate, $v['id']));
			}
		}
		return $arr;
	}
}