<?php 
	error_reporting(0);
	$name=$_POST['name'];
	$date=$_POST['date'];
	$mobile=$_POST['mobile'];
	$adult_num=$_POST['AdultNum'];
	$child_num=$_POST['childrenNum'];
	$activity_id=$_POST['activity_id'];
	$openid=$_POST['openid'];
	$apply_time=$_POST['apply_time'];

	$mysql_info = json_decode(file_get_contents("mysql_info.json"));
	$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
 	mysql_select_db($mysql_info->db_name, $con);
 	mysql_query("set names 'utf8'",$con);

	$sql="select * from ck_wx_activity_apply where openid='".$openid."' and activity_id='".$activity_id."'";
	$result = mysql_query($sql,$con);
	$row = mysql_fetch_array($result);

	if($row[0]!="")
	{
		echo "0";
	}else{		
		$sql="insert into ck_wx_activity_apply value (null,'".$name."','".$date."','".$mobile."','".$adult_num."','".$child_num."','".$activity_id."','".$openid."','".$apply_time."')";		
		try {   
		mysql_query($sql,$con);
		echo "1";
		} catch (Exception $e) {   
		echo "报名失败！".$e->getMessage();
		exit();   
		}   		
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