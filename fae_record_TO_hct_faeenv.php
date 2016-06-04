<?php
$con = mysql_connect("localhost","root","zhouwei");
	if (!$con){
		die('Could not connect: '.mysql_error());
	}
	mysql_select_db("project",$con);
mysql_query("set names 'utf8'");
$result = mysql_query("SELECT * FROM `FAE_record` ",$con);

//INSERT INTO `hct_user`(`name`) VALUES("张艳玲"),("张丽艳"),("伍伦东"),("刘欢"),("李佳岭"),("聂新秀"),("唐艳妮"),("姜武欢"),("董瑾"),("华飞"),("储兰芳"),("石雨芳"),("陈万昌"),("王巧莲"),("罗梦佳");

/*

INSERT INTO `hct_user`(`id`,`name`) VALUES
('100',"张艳玲"),
('',"张丽艳"),
('',"伍伦东"),
('',"刘欢"),
('',"李佳岭"),
('',"聂新秀"),
('',"唐艳妮"),
('',"姜武欢"),
('',"董瑾"),
('',"华飞"),
('',"储兰芳"),
('',"石雨芳"),
('',"陈万昌"),
('',"王巧莲"),
('',"罗梦佳");

张艳玲
张丽艳
伍伦东
刘欢
李佳岭
聂新秀
唐艳妮
姜武欢
董瑾
华飞
储兰芳
石雨芳
陈万昌
王巧莲
罗梦佳


*/

function return_user_id($name){
	switch ($name) {
		case "张艳玲": return 100; break;
		case "张丽艳": return 101; break;
		case "伍伦东": return 102; break;
		case "刘欢": return 103; break;
		case "李佳岭": return 104; break;
		case "聂新秀": return 105; break;
		case "唐艳妮": return 106; break;
		case "姜武欢": return 107; break;
		case "董瑾": return 108; break;
		case "华飞": return 109; break;
		case "储兰芳": return 110; break;
		case "石雨芳": return 111; break;
		case "陈万昌": return 112; break;
		case "王巧莲": return 113; break;
		case "罗梦佳": return 114; break;
		default: return 0;
	}
}

function return_faepc_id($str){
	switch($str){
		case "FAE01":return 1;
		case "FAE02":return 2;
		case "FAE03":return 3;
		case "FAE04":return 4;
		case "FAE05":return 5;
		case "FAE06":return 6;
		case "FAE07":return 7;
		case "FAE08":return 8;
		case "FAE09":return 9;
		case "FAE10":return 10;
		default :return 0;
	}
}

mysql_query("TRUNCATE TABLE `hct_faeenv`;");
print_r($result);
$data = array();
while($row = mysql_fetch_array($result)){
  	// print_r($row);
  	$tmp = array();
  	$tmp['user_id'] = return_user_id($row['pm']);
  	$tmp['path'] = $row['path'];
  	$tmp['faepc_id'] = return_faepc_id($row['fae']);
  	$tmp['date'] = $row['date'];
  	$tmp['comment'] = $row['dists'];
  	$data[] = $tmp;
  	$sql = "INSERT INTO `hct_faeenv`(`user_id`,`path`,`faepc_id`,`date`,`comment`)	VALUES ('".$tmp['user_id']."','".$tmp['path']."','".$tmp['faepc_id']."','".$tmp['date']."','".$tmp['comment']."');" ;
  	echo $sql."\n";
  	$rslt = mysql_query($sql);
 	// print_r($rslt);
 }


// print_r($data);
echo "\n";