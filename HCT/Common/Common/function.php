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


/**
 *  将数据库的数据 id 与 内容 变成数组
 *  [id] => [内容]
 * 	@param array $data 数据源
 * 	@param array $filed  转换的字段
 *
 * 	@example turnArray($data,array('id','name'))
 *
 */
function turnArray($data=array(),$filed=null){
	if (!is_array($data) || !$filed){
		return null;
	}
	if (is_string($filed)){
		$filed = explode(',',$filed);
		if (count($filed) != 2){
			return null;
		}
	}else if (is_array($filed)){
		if (count($filed) != 2){
			return null;
		}
	}else{
		return null;
	}

	$rslt = array();
	foreach ($data as $i){
		$rslt[$i[$filed[0]]] = $i[$filed[1]];
	}
	return $rslt;
}
/**
 * 多个转换 id,type,color  [,xx,xx]
 * [id] => array(
 *      type => 'xx',
 *      color => '#xxx',
 *   )
 *   $filed : 'id,type,color'
 *    或者 ['id','type','color']
 * @param array $data
 * @param array,str $filed
 * @return NULL|multitype:unknown
 */

function turnMulArray($data=array(),$filed=null){
	if (!is_array($data) || !$filed){
		return null;
	}
	if (is_string($filed)){
		$filed = explode(',',$filed);
		if (($len = count($filed)) < 2){
			return null;
		}
	}else if (is_array($filed)){
		if (($len = count($filed)) < 2){
			return null;
		}
	}else{
		return null;
	}
	$rslt = array();
	foreach ($data as $v){
		$tmp = array();
		for ($i=1;$i<$len;$i++){
			$tmp[$filed[$i]] = $v[$filed[$i]];
		}
		$rslt[$v[$filed[0]]] = $tmp;
	}
	return $rslt;
}

function native_mysql_conn(){
	static $con='';
	if ($con != '') return $con;
	// 资料说 mysql_connect 打开的数据库和上次是一样的会选择上次的资源连接 id
	$con = mysql_connect(C('DB_HOST'),C('DB_USER'),C("DB_PWD"));
	if (!$con){
		die('Could not connect: '.mysql_error());
	}
	mysql_select_db(C("DB_NAME"),$con);
	mysql_query("set names 'utf8'",$con);
	return $con;
}

function formatTime($time){ 
    $now=time(); 
    $day=date('Y-m-d',$time); 
    $today=date('Y-m-d'); 
    
    $dayArr=explode('-',$day); 
    $todayArr=explode('-',$today); 
    
    //距离的天数，这种方法超过30天则不一定准确，但是30天内是准确的，因为一个月可能是30天也可能是31天 
    $days=($todayArr[0]-$dayArr[0])*365+(($todayArr[1]-$dayArr[1])*30)+($todayArr[2]-$dayArr[2]); 
    //距离的秒数 
    $secs=$now-$time; 
    
    if($todayArr[0]-$dayArr[0]>0 && $days>3){//跨年且超过3天 
        return date('Y-m-d',$time); 
    }else{ 
    
        if($days<1){//今天 
            if($secs<60)return $secs.'秒前'; 
            elseif($secs<3600)return floor($secs/60)."分钟前"; 
            else return floor($secs/3600)."小时前"; 
        }else if($days<2){//昨天 
            $hour=date('h',$time); 
            return "昨天".$hour.'点'; 
        }elseif($days<3){//前天 
            $hour=date('h',$time); 
            return "前天".$hour.'点'; 
        }else{//三天前 
            return date('m月d号',$time); 
        } 
    } 
} 
/**
 * 用于验证是否有权限,配合$_SESSION 和node,sector,access三张表使用
 * @param string $str
 * @return boolean
 */
function isAuth($str = null){
	if(!isset($_SESSION['access']) ) return false;
	if(null != $str){
		$str = explode('/',$str);
		$len = count($str);
	}else{
		$len = 0;
	}
	switch ($len){
		case 0:
			$module = MODULE_NAME;$controller = CONTROLLER_NAME;
			$action = ACTION_NAME;
			break;
		case 1:
			$module = MODULE_NAME;$controller = CONTROLLER_NAME;
			$action = $str[0];
			break;
		case 2:
			$module = MODULE_NAME;$controller = $str[0];
			$action = $str[1];
			break;
		case 3:
			$module = $str[0];$controller = $str[1];
			$action = $str[2];
			break;
		default:
			return false;
			break;
	}
	$data = $_SESSION['access'];
// 	echo ">>>:".$modul."/".$controller."/".$action;
	if(isset($data[$module][$controller][$action]) && $data[$module][$controller][$action]){
		return true;
	}else{
		return false;
	}
}

/**
 * Goofy 2011-11-30
 * getDir()去文件夹列表，getFile()去对应文件夹下面的文件列表,二者的区别在于判断有没有"."后缀的文件，其他都一样
 */

//获取文件目录列表,该方法返回数组
function getDir($dir) {
	$dirArray[]=NULL;
	if (false != ($handle = opendir ( $dir ))) {
		$i=0;
		while ( false !== ($file = readdir ( $handle )) ) {
			//去掉""."、".."以及带".xxx"后缀的文件
			if ($file != "." && $file != ".."&&!strpos($file,".")) {
				$dirArray[$i]=$file;
				$i++;
			}
		}
		//关闭句柄
		closedir ( $handle );
	}
	return $dirArray;
}

//获取文件列表
function getFile($dir) {
	$fileArray[]=NULL;
	if (false != ($handle = opendir ( $dir ))) {
		$i=0;
		while ( false !== ($file = readdir ( $handle )) ) {
			//去掉""."、".."以及带".xxx"后缀的文件
			if ($file != "." && $file != ".."&&strpos($file,".")) {
				$fileArray[$i]="./imageroot/current/".$file;
				if($i==100){
					break;
				}
				$i++;
			}
		}
		//关闭句柄
		closedir ( $handle );
	}
	return $fileArray;
}

function getLastDate(){
	include "data/all_include.php";
	return end($date);
}

function is_positive_int($num){
	if(ereg('^[0-9]*$',$num)){
		return true;
	}else{
		return false;		
	}
}

function delDirAndFile( $dirName )
{
	if ( $handle = opendir( "$dirName" ) ) {
// 		echo "<br/>";
		while ( false !== ( $item = readdir( $handle ) ) ) {
			if ( $item != "." && $item != ".." ) {
				if ( is_dir( "$dirName/$item" ) ) {
					delDirAndFile( "$dirName/$item" );
				} else {
					if( unlink( "$dirName/$item" ) )echo "成功删除文件： $dirName/$item<br/>";
					else echo "删除文件失败: $dirName/$item<br/>";
				}
			}
		}
		closedir( $handle );
		if( rmdir( $dirName ) )echo "成功删除目录： $dirName<br/>";
	}
}

function explode_path(){
	
}

?>



