<?php  
 error_reporting(0);
$openid = $_POST['openid'];
$score = $_POST['score'];
$updatetime=$_POST['updatetime'];

$mysql_info = json_decode(file_get_contents("mysql_info.json"));
$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
mysql_select_db($mysql_info->db_name, $con);
mysql_query("set names 'utf8'",$con);

$sql="select integral from ck_member_new where openid = '".$openid ."'";
$result = mysql_query($sql,$con);
$row = mysql_fetch_array($result);
if($row[0]>0)
{
	$integral = $row[0] - $score;
	if($integral < 0)
	{
		echo -1 //积分不足
		exit;
	}else{
		//修改总积分
		$sql="update ck_member_new set integral = '".$integral."' where openid = '".$openid ."'";
		mysql_query($sql,$con);
		//插入抽奖记录
		$sql="insert point_record (id,user_open_id,change_type_name,change_type,record,time) values (null,'$openid','活动抽奖','lucky_draw','-".$score."','".$updatetime."')";
		$result = mysql_query($sql,$con);

		echo $integral;
	}
}else{
	echo 0; //未注册或积分为0
}
mysql_close($con);
?>