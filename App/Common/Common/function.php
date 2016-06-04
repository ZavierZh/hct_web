<?php 
/*
 * 递归重组节点信息为多为数组
 */
function node_merge($node,$access=NULL,$pid=0){
	$arr = array();
	foreach($node as $v){
		if (is_array($access)){
			$v['access'] = in_array($v['id'], $access)?1:0;
		}
		if ($v['pid'] == $pid){
			$v['child'] = node_merge($node,$access,$v['id']);
			$arr[] = $v;
		}
	}
	return $arr;
	
}
/*
 * 无限极分级
 */

function unlimitedForLevel($cate,$pid=0,$level=0,$html='&nbsp;&nbsp;|--'){
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

function parray($array=array()){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

/*
 * 查找数组中的重复值 
 * Rbac/
 */
function RepeatMemberInArray($array) {
	$len = count ( $array );
	for($i = 0; $i < $len; $i ++) {
		for($j = $i + 1; $j < $len; $j ++) {
			if ($array [$i] == $array [$j]) {
				return $j;
			}
		}
	}
	return 0;
}
// if (count($role_arr) != count(array_unique($role_arr))) {
// 	$this->error('角色有重复');
// 	die;
// }


function mydb_init(){

/*	'DB_TYPE'               =>  'mysql',     // 数据库类型
	'DB_HOST'               =>  'localhost', // 服务器地址
	'DB_NAME'               =>  'project',          // 数据库名
	'DB_USER'               =>  'root',      // 用户名
	'DB_PWD'                =>  'zhouwei',          // 密码
	'DB_PORT'               =>  '',        // 端口
	'DB_PREFIX'             =>  '',    // 数据库表前缀
	'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
	*/
	$con = mysql_connect(C('DB_HOST'),C('DB_USER'),C('DB_PWD'));
	if (!$con){
		die('Could not connect: '.mysql_error());
	}
	mysql_select_db(C('DB_NAME'),$con);
	mysql_query("set names '".C('DB_CHARSET')."'",$con);
	return $con;
}

function mydb_updateAll($table=null,$data=array(),$table=array()){
	if($table == null || empty($data) || empty($table)){
		return null;
	}
	foreach ($data as $k=>$v ){
		echo $k."|".$v."|";
		$sort[]=array(
				"id"=>$k,
				"sort"=>$v,
		);
	}
	parray($sort);
	
}






?>



