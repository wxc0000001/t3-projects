<?php 
	error_reporting(0);

	//$next_id=$_POST['next_id'];
	
	$con = mysql_connect('115.28.145.249','T3User','T3chuanbo');//本地
 	mysql_select_db("vipformall", $con);
 	mysql_query("set names 'utf8'",$con);

	$sql="select * from item_list order by id desc";
	$result = mysql_query($sql,$con);
	//$row = mysql_fetch_array($result);
	//$num_rows=mysql_num_rows($result);
	$res = array();
	while( $row = mysql_fetch_array($result) ) {
	    $res[] = array("id"=>$row[0],"item_name"=>$row[1],"price"=>$row[2],"original_price"=>$row[3],"point"=>$row[4],"img"=>$row[5],"detail"=>$row[6]);	   
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