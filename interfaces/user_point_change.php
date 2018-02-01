<?php 
	error_reporting(0);

	$openid=$_POST['openid'];
	$record_type=$_POST['record_type'];
	$record_subid=$_POST['record_subid'];
	$updatetime=$_POST['updatetime'];
	
	$mysql_info = json_decode(file_get_contents("mysql_info.json"));
	$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
 	mysql_select_db($mysql_info->db_name, $con);
 	mysql_query("set names 'utf8'",$con);

//取加分规则
	$sql_rule="select point_change from ck_wx_point_rules where change_type='".$record_type."'";
	$result_rule = mysql_query($sql_rule,$con);
	$row_rule = mysql_fetch_array($result_rule);

//取用户总分
 	$sql="select integral from ck_member_new where openid='".$openid."'";
	$result = mysql_query($sql,$con);
	$row = mysql_fetch_array($result);
	//$num_rows=mysql_num_rows($result);
//计算新的总分
	preg_match_all('/\d+/',$row_rule[0],$arr);
	$integral=$row[0]+$arr[0][0];

if($record_type=="sign_in"){
	//判断是否已经签过到
	$sql_sign="select * from point_record where change_type='sign_in' and date(time)=date(now()) and user_open_id='".$openid."'";
	$result_sign = mysql_query($sql_sign,$con);	
	$num_rows=mysql_num_rows($result_sign);
	if($num_rows==0){
		$sql_="insert point_record (id,user_open_id,change_type_name,change_type,record,time) values (null,'$openid','签到','sign_in','".$row_rule[0]."','".$updatetime."')";
		$sql_user="update ck_member_new set integral='".$integral."' where openid='".$openid."'";
		mysql_query($sql_,$con);
		mysql_query($sql_user,$con);
		echo $integral;
	}else{echo "0";}
}else if($record_type=="share"){
	//判断是否已经分享过该文章
	$sql_share="select * from point_record where change_type='share' and note='".$record_subid."' and user_open_id='".$openid."'";
	$result_share = mysql_query($sql_share,$con);	
	if(mysql_num_rows($result_share)==0){
		$sql_="insert point_record (id,user_open_id,change_type_name,change_type,note,record,time) values (null,'$openid','分享','share','".$record_subid."','".$row_rule[0]."','".$updatetime."')";
		$sql_user="update ck_member_new set integral='".$integral."' where openid='".$openid."'";
		mysql_query($sql_,$con);
		mysql_query($sql_user,$con);
		echo "1";
	}else{echo "0";}//date('Y-m-d h:i:s', $time)
}else if($record_type=="read"){
	$sql_read="select * from point_record where change_type='read' and date(time)=date(now()) and user_open_id='".$openid."'";
	$result_read = mysql_query($sql_read,$con);
	$num_rows=mysql_num_rows($result_read);	
	if($num_rows<4){
		$sql_="insert into point_record (id,user_open_id,change_type_name,change_type,note,record,time) values (null,'$openid','阅读全文','read','".$record_subid."','".$row_rule[0]."','".$updatetime."')";
		$sql_user="update ck_member_new set integral='".$integral."' where openid='".$openid."'";
		mysql_query($sql_,$con);
		mysql_query($sql_user,$con);
		echo "1";
	}else{echo "0";}
}
	mysql_close($con);

	function https_request($url,$data = null){    
			$curl = curl_init();
	    	curl_setopt($curl, CURLOPT_URL, $url);
	    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	        if (!empty($data)){
	                curl_setopt($curl, CURLOPT_POST, 1);
	                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	        }    
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	        $output = curl_exec($curl);
	        curl_close($curl);    
	        return $output;
	}
?>