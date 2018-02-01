<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>亲子活动管理</title>
  <script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script>  
  var arr ;
  var arr_time;
  var record_id;
  function locking(button_type){   //浮层锁定
     document.all.ly.style.display="block";   
     document.all.ly.style.width=document.body.clientWidth;   
     document.all.ly.style.height=document.body.clientHeight;   
     document.all.Layer2.style.display='block'; 
     if(button_type!="add"){
      //修改按钮界面
      var row = button_type.parentNode.parentNode; 
      var index = row.rowIndex; 
      var tr_id = $(".table tbody tr").eq(parseInt(index)-1).children("td").eq(0).html();
      record_id=tr_id;
      $.ajax({//加载修改信息
                url: "php/enquiry_activity.php",
                type: "post",
                data: {"tr_id":tr_id,"table_name":"point_rules"},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {
                  if(data!="读取信息失败!"){
                      arr=data.split("##");                      
                      document.getElementById("operation").value=arr[1];  
                      document.getElementById("point_change").value=arr[2];   
                      document.getElementById("operation").readOnly=true;                 
                    }else{
                      alert(data); 
                      window.location.reload(true); 
                    }
                                        
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
        });
     
    }
    else{
 //添加按钮界面
      document.getElementById("operation").value="";  
      document.getElementById("point_change").value="";  
    }    
   }
//关闭浮层
  function Lock_CheckForm(theForm){   
   document.all.ly.style.display='none';
   document.all.Layer2.style.display='none';
   return false;   
   } 
 
</script>
<style type="text/css">
.STYLE1 {/*关闭浮层按钮样式*/
  font-size: 12px;
  float: right;
  color: gray;
  text-decoration: none;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/new.css" rel="stylesheet" type="text/css">
<link href="css/parent_child_activity.css" rel="stylesheet" type="text/css">
<script src="js/jQuery.min.2.1.1.js"></script>   
</head>
<body>

<?php//页面加载时查询所有记录
error_reporting(0);
$mysql_info = json_decode(file_get_contents("json/mysql_info.json"));
  $con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
  mysql_select_db("fivekids", $con);
  $sql="select * from point_rules order by id desc";
  $result = mysql_query($sql,$con);
  //$row=mysql_fetch_array($result);
  //$num_rows=mysql_num_rows($result);
?>

<div id="ly" style="display: none;position: absolute;top: 0%;left: 0%;width: 100%;height: 100%;background-color: gray;z-index:2;-moz-opacity: 0.3;opacity:.30;filter: alpha(opacity=30);"> </div>
		<div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget no-margin">
                  <div class="widget-header">
                    <div class="title">
                     积分规则
                    </div>
                  </div>
                  <div class="widget-body" >
                    <table class="table no-margin">
                      <thead>
                        <tr>
                          <th >id</th>
                          <th style="text-align:center;">用户活动</th>
                          <th style="text-align:center;">积分改变</th>
            						  <th style="text-align:center;">操作</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $i=0;
                        //记录颜色交替显示
                        while($row=mysql_fetch_assoc($result)){
                        if($i==0){
                        ?>
                        <tr align="center" class="success">
                        <td><?php echo $row["id"];?></td>
                        <td><?php echo $row["operation_name"];?></td>
                        <td><?php echo $row["point_change"];?></td>
                        <td><input class="btn btn-sm btn-success" type="button" value="修改" onClick="locking(this)"> 
                          </td>
                        </tr>

                        <?php 
                        $i++;
                      }elseif ($i==1) {?><!-- 记录颜色交替显示 -->
                        <tr align="center">
                        <td><?php echo $row["id"];?></td>
                        <td><?php echo $row["operation_name"];?></td>
                        <td><?php echo $row["point_change"];?></td>
                        <td><input class="btn btn-sm btn-success" type="button" value="修改" onClick="locking(this)"> 
                          </td>
                        </tr>
                        <?php $i--;
                      }
                    }
                       ?>
                        
                      </tbody>
                    </table>
                  </div>
                </div>
    
    <!--浮层框架开始-->
    <div id="Layer2" align="center" style=" width:600px;position: absolute; z-index: 3; left: 20%; top: 10%;background-color: ; display: none;">
		<div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
                      活动规则
                    </div>
               <a href=JavaScript:; class="STYLE1" onclick="Lock_CheckForm(this);">[关闭]</a>     
                  </div>
                  <div class="widget-body">
                    <form class="form-horizontal no-margin" >
                      <div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">用户活动</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="operation" placeholder="活动">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="emailId" class="col-sm-2 control-label">积分改变</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="point_change" placeholder="改变">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input id="edit" type="button" class="btn btn-info" value="修改">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
    </div>
    <!--浮层框架结束--> 	

<script>
//修改记录
$("#edit").click(function(){
  var operation=$("#operation").val();
  var point_change=$("#point_change").val();
    if(point_change!=""){
        $.ajax({
                url: "php/edit_p_c_activity.php",
                type: "post",
                data: {"operation":operation,"point_change":point_change},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {
                    if(data=="1") {
                      alert("修改成功！");
                      document.all.ly.style.display='none';
                      document.all.Layer2.style.display='none';
                      window.location.reload(true);
                    }                      
                    else 
                      alert("修改出错！"+data);                      
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
            });
  
  }else{
    alert("输入不能为空！");
  }
});
</script>
</body>
</html>
