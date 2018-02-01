<?php 
	error_reporting(0);

	$user_open_id=$_POST['user_open_id'];
	
	$mysql_info = json_decode(file_get_contents("mysql_info.json"));
	$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
 	mysql_select_db($mysql_info->db_name, $con);
 	mysql_query("set names 'utf8'",$con);


	$sql="select item_name,price,original_price,point,img,status,time from v_user_order where user_open_id='".$user_open_id."' order by time desc";

	$result = mysql_query($sql,$con);
	//$row = mysql_fetch_array($result);
	//$num_rows=mysql_num_rows($result);
	$res = array();
	while( $row = mysql_fetch_array($result) ) {
	    $res[] = array("item_name"=>$row[0],"price"=>$row[1],"original_price"=>$row[2],"point"=>$row[3],"img"=>$row[4],"status"=>$row[5],"time"=>$row[6]);	   
	}

	mysql_free_result($result);

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