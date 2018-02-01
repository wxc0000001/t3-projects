<?php 
	error_reporting(0);

	//$activity_type=$_POST['activity_type'];
	
	$mysql_info = json_decode(file_get_contents("mysql_info.json"));
	$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
 	mysql_select_db($mysql_info->db_name, $con);
 	mysql_query("set names 'utf8'",$con);


	$sql="select * from ck_wx_activity";

	$result = mysql_query($sql,$con);
	//$row = mysql_fetch_array($result);
	//$num_rows=mysql_num_rows($result);
	$res = array();
	while( $row = mysql_fetch_array($result) ) {
	    $res[] = array("id"=>$row[0],"title"=>$row[1],"location"=>$row[2],"begin_time"=>$row[3],"end_time"=>$row[4],"introduction"=>$row[5],"img"=>$row[6],"url"=>$row[7]);	   
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