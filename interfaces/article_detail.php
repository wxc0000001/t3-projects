<?php 
	error_reporting(0);

	$id=$_POST['id'];

	$mysql_info = json_decode(file_get_contents("mysql_info.json"));
	$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
 	mysql_select_db($mysql_info->db_name, $con);
 	mysql_query("set names 'utf8'",$con);


	$sql="select * from ck_wx_article where id=".$id;

	$result = mysql_query($sql,$con);
	$res = array();
	while( $row = mysql_fetch_array($result) ) {
	    $res[] = array("title"=>$row[1],"img"=>$row[2],"url"=>$row[3],"detail"=>$row[4],"point_change"=>$row_[0]);	   
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