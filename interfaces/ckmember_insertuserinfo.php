<?php  
 error_reporting(0);
$openid = $_POST['openid'];
//$username = $_POST['username'];
$nickname = $_POST['nickname'];
$headimg = $_POST['headimg'];
//$mobile = $_POST['mobile'];
//$province = $_POST['province'];
//$address_now = $_POST['address_now'];
$adddate = $_POST['adddate'];

$nickname2 = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $nickname);

$mysql_info = json_decode(file_get_contents("mysql_info.json"));
$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
mysql_select_db($mysql_info->db_name, $con);
mysql_query("set names 'utf8'",$con);

$sql="select * from ck_member_new where openid = '".$openid ."'";
$result = mysql_query($sql,$con);
$row = mysql_fetch_array($result);
if($row[0]>0)
{
	echo "0"; //该微信号已注册过
}else{
	$sql="insert into ck_member_new (id,openid,nickname,headimg,adddate,integral) values (null,'$openid','$nickname2','$headimg','$adddate',0)";
	//echo $sql;
	echo "1"; //注册成功
}
$result = mysql_query($sql,$con);

mysql_close($con);
?>