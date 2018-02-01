<?php 
	error_reporting(0);
	$gender=$_POST['gender'];
	$occupation=$_POST['occupation'];
	$hobbies=$_POST['hobbies'];
	$follow=$_POST['follow'];
	$signature=$_POST['signature'];
	$mobile=$_POST['mobile'];
	$email=$_POST['email'];
	$openid=$_POST['openid'];
	
	$mysql_info = json_decode(file_get_contents("mysql_info.json"));
	$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
 	mysql_select_db($mysql_info->db_name, $con);
 	mysql_query("set names 'utf8'",$con);

 	$sql="update ck_member_new set gender='".$gender."',occupation='".$occupation."',hobbies='".$hobbies."',
 	follow='".$follow."',signature='".$signature."',mobile='".$mobile."',email='".$email."' where openid='".$openid."'";
	try {   
		mysql_query($sql,$con);
		echo "1";
	} catch (Exception $e) {   
		echo "0".$e->getMessage();
		exit();   
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