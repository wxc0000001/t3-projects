<?php 
	error_reporting(0);

	$openid=$_POST['openid'];
	
	$mysql_info = json_decode(file_get_contents("mysql_info.json"));
	$con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
 	mysql_select_db($mysql_info->db_name, $con);
 	mysql_query("set names 'utf8'",$con);

	$sql_="select title from ck_wx_article where id=some(select note from point_record where user_open_id='$openid')";
	$result_ = mysql_query($sql_,$con);
	$num_rows=mysql_num_rows($result_);
	while($row_=mysql_fetch_array($result_)){ 
		$title[]=$row_["title"];
	}
	$sql="select change_type_name,note,record,time from point_record where user_open_id='".$openid."' order by id desc";
	$result = mysql_query($sql,$con);
	//$row = mysql_fetch_array($result);
	//$num_rows=mysql_num_rows($result);
	$res = array();
	$i=0;
	while( $row = mysql_fetch_array($result) ) {
		if($row[0]=="分享"){
			 $res[] = array("record_name"=>$row[0],"title"=>$title[$i],"record"=>$row[2],"time"=>$row[3]);
			 $i++;	
		}
		else {
			$res[] = array("record_name"=>$row[0],"note"=>$row[1],"record"=>$row[2],"time"=>$row[3]);
		}	
	    
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