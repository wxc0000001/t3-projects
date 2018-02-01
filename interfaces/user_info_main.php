<?php 
	error_reporting(0);

	$openid=$_POST['openid'];
	
	$mysql_info = json_decode(file_get_contents("mysql_info.json"));
	$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
 	mysql_select_db($mysql_info->db_name, $con);
 	mysql_query("set names 'utf8'",$con);
	
	//判断是否签过到
	$sql_sign="select * from point_record where change_type='sign_in' and date(time)=date(now()) and user_open_id='".$openid."'";
	$result_sign = mysql_query($sql_sign,$con);	
	$num_rows=mysql_num_rows($result_sign);
	if($num_rows>0) $sign_in="1";
	else $sign_in="0";

	//取用户信息         
	$sql="select integral,gender,occupation,hobbies,follow,signature,mobile,email,nickname,headimg from ck_member_new where openid='".$openid."'";
	$result = mysql_query($sql,$con);	
	//$row = mysql_fetch_array($result);
	
	while( $row = mysql_fetch_array($result) ) {
	   	$res[] = array("integral"=>$row[0],"gender"=>$row[1],"occupation"=>$row[2],"hobbies"=>$row[3],"follow"=>$row[4],"signature"=>$row[5],"mobile"=>$row[6],"email"=>$row[7],"nickname"=>$row[8],"headimg"=>$row[9],"sign_in"=>$sign_in);
	}

	echo json_encode($res);
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