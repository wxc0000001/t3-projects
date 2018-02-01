<?php 
	error_reporting(0);

	$user_open_id=$_POST['user_open_id'];
	$change_type=$_POST['change_type'];
	$user_name=$_POST['user_name'];
	$user_mobile=$_POST['user_mobile'];
	$user_address=$_POST['user_address'];
	$item_id=$_POST['item_id'];
	$price=$_POST['price'];
	$exchange_point=$_POST['exchange_point'];
	$time=$_POST['time'];
	
	$mysql_info = json_decode(file_get_contents("mysql_info.json"));
	$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
 	mysql_select_db($mysql_info->db_name, $con);
 	mysql_query("set names 'utf8'",$con);

//取用户总分
 	$sql="select integral from ck_member_new where openid='".$user_open_id."'";
	$result = mysql_query($sql,$con);
	$row = mysql_fetch_array($result);
//计算新的总分
	$user_point=$row[0]-$exchange_point;
	if($user_point<0){
		echo 3;
		exit;
	} 
//检查订单状态
	$sql_check="select point from item_list where id='".$item_id."'";
	$result_check = mysql_query($sql_check,$con);
	$row_check = mysql_fetch_array($result_check);
	if($row_check[0]==$exchange_point){
		$sql_="insert point_record (id,user_open_id,change_type_name,change_type,record,time,user_mobile,user_name,user_address,item_id,
			price,exchange_point,status) values (null,'".$user_open_id."','积分兑换','".$change_type."','-".$exchange_point."','".$time."',
			'".$user_mobile."','".$user_name."','".$user_address."','".$item_id."','".$price."','".$exchange_point."','未完成')";
		$sql_user="update user_info set user_point='".$user_point."' where user_openid='".$user_open_id."'";
		try {   
			mysql_query($sql_,$con);
			mysql_query($sql_user,$con);
			echo 1;
		} catch (Exception $e) {   
			echo $e->getMessage();
			exit();
		}   	
	}else echo 2;
	//1:成功 2.异常 3：积分不足
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