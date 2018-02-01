<?php  
 error_reporting(0);
$openid = $_POST['openid'];

$mysql_info = json_decode(file_get_contents("mysql_info.json"));
$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
mysql_select_db($mysql_info->db_name, $con);
mysql_query("set names 'utf8'",$con);

$sql="select integral  from ck_member_new where openid = '".$openid ."'";
$result = mysql_query($sql,$con);
$row = mysql_fetch_array($result);
if($row[0]>0)
{
	echo "0"; //该微信号已注册过
}else{
	echo "1"; //未注册
}
mysql_close($con);
?>