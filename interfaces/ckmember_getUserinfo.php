<?php
error_reporting(0);
header('Content-type: text/json');
$openid = $_POST['openid'];


$mysql_info = json_decode(file_get_contents("mysql_info.json"));
$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
mysql_select_db($mysql_info->db_name, $con);
mysql_query("set names 'utf8'",$con);

$sql="select nickname,headimg,integral from ck_member_new where openid = '".$openid ."'";

$result = mysql_query($sql,$con);

$rows=array();  
$n=0;  

while($row = mysql_fetch_array($result))  
{  
    $rows[$n]=array("nickname"=>$row[0],"headimg"=>$row[1],"integral"=>$row[2]);
    $n++    ;  
}

echo json_encode($rows);  

mysql_close($con);
?>