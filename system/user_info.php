<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>用户信息管理</title>
<script>  
  var arr ;
  var arr_time;
  var record_id;
//浮层内关注行家的选择框和文本框关联
  function link(x){//alert(x);
    var opts=document.getElementById("expert_id").options;
    var choose=opts[x].text;
    var name= document.getElementById("followed_expert_id").value;
    if(name=="") document.getElementById("followed_expert_id").value="'"+choose+"'";
    else{
      var data=name.split(",");
      if(data.indexOf("'"+choose+"'")==-1){
          data.push("'"+choose+"'");
          document.getElementById("followed_expert_id").value=data.toString();//"'"+data.join("','")+"'";
      }
    }
  }
//锁定浮层
  function locking(button_type){   
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
      $.ajax({
                url: "php/enquiry_activity.php",
                type: "post",
                data: {"tr_id":tr_id,"table_name":"user_info"},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {
                  if(data!="读取信息失败!"){
                      arr=data.split("##");                      
                      document.getElementById("username").value=arr[1];  
                      document.getElementById("img").value=arr[2].substring(arr[2].lastIndexOf("/")+1,arr[2].length);
                      document.getElementById("occupation").value=arr[4];
                      document.getElementById("hobbies").value=arr[5];
                      document.getElementById("signature").value=arr[6];
                      document.getElementById("mobile").value=arr[7];
                      document.getElementById("email").value=arr[8];
                       $.ajax({//加载修改记录中的关注行家
                                url: "php/link.php",
                                type: "post",
                                data: {"expert_id":arr[9],"table_name":"expert_info"},
                                dataType: "json",
                                success: function(data) { 
                                  var name_arr=[];
                                  for(var i=0;i<data.length;i++){
                                    name_arr[i]=data[i].name;
                                  }
                                  //console.log(name_arr);
                                  document.getElementById("followed_expert_id").value="'"+name_arr.join("','")+"'";
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert(XMLHttpRequest.status);
                                    alert(XMLHttpRequest.readyState);
                                    alert(textStatus);
                                }
                        });
                      document.getElementById("point").value=arr[10];
                      document.getElementById("user_open_id").value=arr[11];
                      var radio = document.getElementsByName("sex");//加载性别
                      for(i=0;i<radio.length;i++){
                        if(radio[i].value==arr[3]) {
                                 radio[i].checked = true;
                              }
                          } 
                      $("#edit").show();
                      $("#add").hide();                      
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
     
    }else{
 //添加按钮界面
      document.getElementById("username").value="";  
      document.getElementById("img").value=""; 
      document.getElementById("occupation").value="";
      document.getElementById("hobbies").value="";
      document.getElementById("signature").value="";
      document.getElementById("mobile").value="";
      document.getElementById("email").value="";
      document.getElementById("followed_expert_id").value="";
      document.getElementById("point").value="";
      document.getElementById("user_open_id").value="";
      $("#add").show();
      $("#edit").hide(); 
    }  
   }
//查询记录
   function search(){
    var key_words=$("#key_words").val();
    if(key_words=="") alert("请输入用户姓名！");
    else{
        var storeId = document.getElementById('table2');//获取table的id标识  
        var rowsLength = storeId.rows.length;//表格总共有多少行  
        var key = key_words//获取输入框的值  
        var searchCol = 1;//要搜索的哪一列，这里是第一列，从0开始数起  
        var j=0;
        for(var i=0;i<rowsLength;i++){//按表的行数进行循环，本例第一行是标题，所以i=1，从第二行开始筛选（从0数起）  
            var searchText = storeId.rows[i].cells[searchCol].innerHTML;//取得table行，列的值  
            if(searchText.match(key)){//用match函数进行筛选，如果input的值，即变量 key的值为空，返回的是ture，  
                storeId.rows[i].style.display='';//显示行操作，
                j++;
            }else{  
                storeId.rows[i].style.display='none';//隐藏行操作  
            }  
        }
        document.getElementById("page").style.display="none";
    }
   }
//关闭浮层，清除控件值
  function Lock_CheckForm(theForm){   
   document.all.ly.style.display='none';
   document.all.Layer2.style.display='none';
   document.all.expert_id.options[0].selected=true;
   var radio = document.getElementsByName("sex");
    for(i=0;i<radio.length;i++) radio[i].checked = false;
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
<script src="uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css"> 
</head>
<body>

<?php
error_reporting(0);//页面加载时显示所有记录
$mysql_info = json_decode(file_get_contents("json/mysql_info.json"));
  $con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);
  mysql_select_db("fivekids", $con);
  $sql="select * from user_info order by id desc";
  $result = mysql_query($sql,$con);
  //$row=mysql_fetch_array($result);
  //$num_rows=mysql_num_rows($result);
  $sql_expert="select id,name from expert_info order by id desc";//取所有行家的id和名字
  $result_expert = mysql_query($sql_expert,$con);
  $num_rows=mysql_num_rows($result_expert);
  while($row_expert=mysql_fetch_array($result_expert)){ 
    $expert_name[]=$row_expert["name"];
    $expert_id[]=$row_expert["id"];
  }
?>

<div id="ly" style="display: none;position: absolute;top: 0%;left: 0%;width: 100%;height: 100%;background-color: gray;z-index:2;-moz-opacity: 0.3;opacity:.30;filter: alpha(opacity=30);"> </div>
		<div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget no-margin">
                  <div class="widget-header">
                    <div class="title">
                     用户信息
                    </div>
                  </div>
                  <div class="widget-body" ><div style="text-align:left;margin-top:-10px;position:absolute;">
                    <input class="btn btn-sm btn-success" type="button" value="添加新纪录" onClick="locking('add')">&nbsp;&nbsp;
                    <input class="btn btn-sm btn-success" type="button" value="导出所有记录" onClick="javascript:method1('table')"></div>
                    <div style="text-align:center;margin-top:-10px;">
                    <input type="text" id="key_words" placeholder="用户姓名" style="margin-top:0px;display:inline;width:20%;height:30px;padding:0px 12px;font-size:14px;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                    &nbsp;<input style="margin-top:-5px;" class="btn btn-sm btn-success" type="button" value="查询" onClick="search()">
                    </div>
                    <!-- 用于导出记录的隐藏table -->
                    <table id="table" style="display:none;">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>姓名</th>
                          <th>性别</th>
                          <th>职业</th>
                          <th>兴趣爱好</th>
                          <th>个性签名</th>
                          <th>联系方式</th>
                          <th>电子邮箱</th>
                          <th>关注行家</th>
                          <th>积分</th>
                          <th>用户id</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php //显示所有记录
                        $sql_invisible="select * from user_info order by id desc";
                        $result_invisible = mysql_query($sql_invisible,$con);
                        while($row_invisible=mysql_fetch_assoc($result_invisible)){
                        ?>
                        <tr>
                        <td><?php echo $row_invisible["id"];?></td>
                        <td><?php echo $row_invisible["username"];?></td>
                        <td><?php echo $row_invisible["sex"];?></td>
                        <td><?php echo $row_invisible["occupation"];?></td>
                        <td><?php echo $row_invisible["hobbies"];?></td>
                        <td><?php echo $row_invisible["signature"];?></td>
                        <td><?php echo $row_invisible["mobile"];?></td>
                        <td><?php echo $row_invisible["email"];?></td>
                        <td><?php //根据行家id显示名字
                          $sql_expert_invisible="select name from expert_info where id in (".$row_invisible["followed_expert_id"].")";
                          $result_expert_invisible = mysql_query($sql_expert_invisible,$con);
                          $temp_invisible= array(); 
                          while($row_expert_invisible=mysql_fetch_array($result_expert_invisible)){ 
                            $temp_invisible[]=$row_expert_invisible["name"];
                          }
                          echo implode(" , ",$temp_invisible);
                        ?></td>
                        <td><?php echo $row_invisible["point"];?></td>
                        <td><?php echo $row_invisible["user_open_id"];?></td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    <!-- 用于显示数据的table -->
                    <table class="table no-margin" style="table-layout:fixed;word-wrap:break-word;">
                      <thead>
                        <tr>
                          <th style="width:5%;text-align:center;">id</th>
                          <th style="width:5%;text-align:center;">姓名</th>
                          <th class="t" style="width:10%;text-align:center;">头像</th>
                          <th style="width:5%;text-align:center;">性别</th>
            						  <th style="width:5%;text-align:center;">职业</th>
                          <th style="width:10%;text-align:center;">兴趣爱好</th>
            						  <th style="width:10%;text-align:center;">个性签名</th>
            						  <th style="width:10%;text-align:center;">联系方式</th>
                          <th style="width:10%;text-align:center;">电子邮箱</th>
                          <th style="width:10%;text-align:center;">关注行家</th>
                          <th style="width:5%;text-align:center;">积分</th>
                          <th style="width:10%;text-align:center;">用户id</th>
            						  <th style="width:12%;text-align:center;">操作</th>
                        </tr>
                      </thead>
                      <tbody id="table2">
                        <?php 
                        $i=0;
                        while($row=mysql_fetch_assoc($result)){
                        if($i==0){ //记录颜色交替显示
                        ?>
                        <tr align="center" class="success">
                        <td style="vertical-align:middle;"><?php echo $row["id"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["username"];?></td>
                        <td class="t" style="vertical-align:middle;" height="110px"><img width="100%" height="100%" src='<?php echo $row["photo_url"];?>'></td>
                        <td style="vertical-align:middle;"><?php echo $row["sex"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["occupation"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["hobbies"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["signature"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["mobile"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["email"];?></td>
                        <!-- <td><?php echo $row["followed_expert_id"];?></td> -->
                        <td style="vertical-align:middle;"><?php //根据行家id显示名字
                          $sql_expert="select name from expert_info where id in (".$row["followed_expert_id"].")";
                          $result_expert = mysql_query($sql_expert,$con);
                          $temp= array(); 
                          while($row_expert=mysql_fetch_array($result_expert)){ 
                            $temp[]=$row_expert["name"];
                          }
                          echo implode(" , ",$temp);
                        ?></td>
                        <td style="vertical-align:middle;"><?php echo $row["point"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["user_open_id"];?></td>
                        <td style="vertical-align:middle;"><input class="btn btn-sm btn-success" type="button" value="修改" onClick="locking(this)"> 
                          <input id="delete" class="btn btn-sm btn-success" type="button" value="删除" onclick="delTableRow(this)"> 
                          </td>
                        </tr>
                        <?php 
                        $i++;
                      }elseif ($i==1) {?><!-- 记录颜色交替显示 -->
                        <tr align="center">
                        <td style="vertical-align:middle;"><?php echo $row["id"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["username"];?></td>
                        <td class="t" style="vertical-align:middle;" height="110px"><img width="100%" height="100%" src='<?php echo $row["photo_url"];?>'></td>
                        <td style="vertical-align:middle;"><?php echo $row["sex"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["occupation"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["hobbies"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["signature"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["mobile"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["email"];?></td>
                        <!-- <td><?php echo $row["followed_expert_id"];?></td> -->
                        <td style="vertical-align:middle;"><?php //根据行家id显示名字
                          $sql_expert="select name from expert_info where id in (".$row["followed_expert_id"].")";
                          $result_expert = mysql_query($sql_expert,$con);
                          $temp= array(); 
                          while($row_expert=mysql_fetch_array($result_expert)){ 
                            $temp[]=$row_expert["name"];
                          }
                          echo implode(" , ",$temp);
                        ?></td>
                        <td style="vertical-align:middle;"><?php echo $row["point"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["user_open_id"];?></td>
                        <td style="vertical-align:middle;"><input class="btn btn-sm btn-success" type="button" value="修改" onClick="locking(this)"> 
                          <input id="delete" class="btn btn-sm btn-success" type="button" value="删除" onclick="delTableRow(this)"> 
                          </td>
                        </tr>
                        <?php $i--;
                      }
                    }?>
                      </tbody>
                    </table>
                    <div id="page" style="margin-top:0px;height:30px;text-align:center;">
                        <span style="line-height:50px;font-size:10pt;border:1px;background:#FFF">
                          <span style="border:1px;background:#FFF" id="spanFirst">首页</span> 
                          <span id="spanPre">上一页</span> 
                          <span id="spanNext">下一页</span> 
                          <span id="spanLast">尾页</span>
                          第<span id="spanPageNum"></span>页/共<span id="spanTotalPage"></span>页
                        </span>
                      </div>
                  </div>
                </div>
    

<link rel="stylesheet" href="control/css/zyUpload.css" type="text/css">

<script src="core/zyFile.js"></script>
<!-- 引用控制层插件 -->
<script src="control/js/zyUpload.js"></script>
<!-- 引用初始化JS -->
<script src="core/jq22.js"></script>
<!--浮层框架开始-->
    <div id="Layer2" align="center" style=" width:600px;position: absolute; z-index: 3; left: 20%; top: 10%;background-color: ; display: none;">
		<div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
                     用户信息
                    </div>
               <a href=JavaScript:; class="STYLE1" onclick="Lock_CheckForm(this);">[关闭]</a>     
                  </div>
                  <div class="widget-body">
                    <form class="form-horizontal no-margin" >
                      <div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">用户姓名</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="username" placeholder="姓名">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="emailId" class="col-sm-2 control-label">性别</label>
                        <div class="col-sm-10"> <span style="float:left;">
                          <input type="radio" name="sex" value="男">男 &nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="sex" value="女">女 &nbsp;&nbsp;&nbsp;&nbsp;
                        </span></div>
                      </div>
                      <div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">职业</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="occupation" placeholder="职业">
                        </div>
                      </div><div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">兴趣爱好</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="hobbies" placeholder="兴趣爱好">
                        </div>
                      </div><div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">个性签名</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="signature" placeholder="个性签名">
                        </div>
                      </div><div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">联系方式</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="mobile" placeholder="联系方式">
                        </div>
                      </div><div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">电子邮箱</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="email" placeholder="电子邮箱">
                        </div>
                      </div><div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">关注行家</label>
                        <div class="col-sm-10" style="text-align:justify"><span float="left">
                          <input type="text" id="followed_expert_id" placeholder="已关注的行家" style="display:inline;width:65%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                          <select name="expert_id" id="expert_id" onchange="link(options.selectedIndex)" style="display:inline;width:30%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                                <option value="">— 选择行家 —</option>
                                <?php for($i=0;$i<$num_rows;$i++) {?>
                                <option value=<?php echo $expert_id[$i]; ?>><?php echo $expert_name[$i]; ?></option>
                                <?php }?>
                          </select> 
                        </span></div>
                      </div><div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">用户积分</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="point" placeholder="用户积分">
                        </div>
                      </div>
					           <div class="form-group">
                        <label for="rptPwd" class="col-sm-2 control-label">用户id</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="user_open_id"  placeholder="id">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="rptPwd" class="col-sm-2 control-label">用户头像</label>
                        <div class="col-sm-10" >    
                              <input type="text" class="form-control" id="img"  placeholder="图片" readonly="true">
                              <input  type="file" name="uploadify" id="uploadify" class="form-control"/> <span style="float:left;"></span>         
                        </div>  
                        <div id="show"></div>
                      </div>	
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
						                <input id="add" type="button" class="btn btn-info" value="添加">
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
    
<script type="text/javascript">
var filename;
//图片上传控件方法
$(function(){ 
  $('#uploadify').uploadify({
    'fileDataName':'Filedata',    
    'swf':'uploadify/uploadify.swf',//选择文件按钮
    'uploader':'uploadify/uploadify.php',//处理文件上传的php文件
    'removeCompleted':true,
    'width':'130',//选择文件按钮的宽度
    'height':'26',//选择文件按钮的高度
    'buttonText': '上传图片',
    'buttonImg': 'uploadify/browse-btn2.png',
    'fileTypeDesc': 'Image Files',
    'fileTypeExts': '*.gif; *.jpg; *.png; *.jpeg',
     //checkExisting: 'uploadify/check-exists.php',
    'debug':false,
    'multi':false,//设置为true时可以上传多个文件
    'formData':{'targetFolder':'fivekids/img_upload/user_photo'},
    'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
  
    'onUploadStart':function(file){//上传前验证图片大小
      if(file.size>2*1024*1014){
        alert("文件超过大小限制2M，请重新选择！");
        $('#uploadify').uploadify('stop');
      }
     },
    'onUploadError':function(file,errorCode,errorMsg){
      alert('上传错误：错误代码：'+obj2string(errorCode)+'错误消息：'+obj2string(errorMsg));
    },
    onUploadSuccess:function(file,data,response){//上传成功，加载图片预览
      //alert(data);
      document.getElementById('img').value=file.name; 
      imgObj="<p><img width='100px' height='100px' src='http://t3china.t3group.cn/fivekids/img_upload/user_photo/"+file.name+"'></p>"
      $("#show").empty();
      $("#show").append(imgObj);
    }
  });
});
</script>	  
<script>
//删除记录
function delTableRow(obj){
  if(window.confirm('确定删除该记录吗？')){
    var row = obj.parentNode.parentNode; 
    var tbl = obj.parentNode.parentNode.parentNode; 
    var index = row.rowIndex; 
    var tr_id = $(".table tbody tr").eq(parseInt(index)-1).children("td").eq(0).html();
    $.ajax({
                url: "php/delete_activity.php",
                type: "post",
                data: {"tr_id":tr_id,"table_name":"user_info"},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {
                      alert(data); 
                      window.location.reload(true);                     
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
        });
  }
}
//添加记录
$("#add").click(function(){ 
	var username=$("#username").val();
	var photo_url="http://t3china.t3group.cn/fivekids/img_upload/user_photo/"+document.getElementById("img").value;
  var occupation=$("#occupation").val();
  var hobbies=$("#hobbies").val();
  var signature=$("#signature").val();
  var mobile=$("#mobile").val();
  var email=$("#email").val();
  var followed_expert_id=$("#followed_expert_id").val();
  var point=$("#point").val();
	var user_open_id=$("#user_open_id").val();
  //取选中标签
  var radio=document.getElementsByName("sex");
  var radio_checked;
  for(var i=0;i<radio.length;i++){
       if(radio[i].checked==true)
          radio_checked=radio[i].value;
   } 
    if(name!=""&&user_open_id!=""&&point!=""){
      if(/^\d+$/.test(point)){  
        $.ajax({
                url: "php/insert_edit_user.php",
                type: "post",
                data: {"change_type":"add","username":username,"photo_url":photo_url,"occupation":occupation,"hobbies":hobbies,"signature":signature,"mobile":mobile,"email":email,"followed_expert_id":followed_expert_id,"point":point,"user_open_id":user_open_id,"sex":radio_checked},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {
                    if(data=="1") {
                      alert("添加成功！");
                      document.all.ly.style.display='none';
                      document.all.Layer2.style.display='none';
                      window.location.reload(true);
                    }else if(data=="0"){
                      alert("该记录已存在！请重新输入！");
                    }else
                      alert("添加出错！"+data);                      
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
            });
    }else alert("积分必须为数字！");
  }else alert("输入不能为空！");
});
//修改记录
$("#edit").click(function(){
  var username=$("#username").val();
  var photo_url="http://t3china.t3group.cn/fivekids/img_upload/user_photo/"+document.getElementById("img").value;
  var occupation=$("#occupation").val();
  var hobbies=$("#hobbies").val();
  var signature=$("#signature").val();
  var mobile=$("#mobile").val();
  var email=$("#email").val();
  var followed_expert_id=$("#followed_expert_id").val();
  var point=$("#point").val();
  var user_open_id=$("#user_open_id").val();
  //取选中标签
  var radio=document.getElementsByName("sex");
  var radio_checked;
  for(var i=0;i<radio.length;i++){
       if(radio[i].checked==true)
          radio_checked=radio[i].value;
   } 
    if(name!=""&&user_open_id!=""&&point!=""){
      if(/^\d+$/.test(point)){  
        $.ajax({
                url: "php/insert_edit_user.php",
                type: "post",
                data: {"record_id":record_id,"change_type":"edit","username":username,"photo_url":photo_url,"occupation":occupation,"hobbies":hobbies,"signature":signature,"mobile":mobile,"email":email,"followed_expert_id":followed_expert_id,"point":point,"user_open_id":user_open_id,"sex":radio_checked},
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
    }else alert("积分必须为数字！");
  }else alert("输入不能为空！");
});
</script>
 <script>
//记录分页显示
     var theTable = document.getElementById("table2");
     var totalPage = document.getElementById("spanTotalPage");
     var pageNum = document.getElementById("spanPageNum");

     var spanPre = document.getElementById("spanPre");
     var spanNext = document.getElementById("spanNext");
     var spanFirst = document.getElementById("spanFirst");
     var spanLast = document.getElementById("spanLast");

     var numberRowsInTable = theTable.rows.length;
     var pageSize = 5;
     var page = 1;
     //下一页
     function next() {
         hideTable();
         currentRow = pageSize * page;
         maxRow = currentRow + pageSize;
         if (maxRow > numberRowsInTable) maxRow = numberRowsInTable;
         for (var i = currentRow; i < maxRow; i++) {
             theTable.rows[i].style.display = '';
         }
         page++;
         if (maxRow == numberRowsInTable) { nextText(); lastText(); }
         showPage();
         preLink();
         firstLink();
     }
     //上一页
     function pre() {
         hideTable();
         page--;
         currentRow = pageSize * page;
         maxRow = currentRow - pageSize;
         if (currentRow > numberRowsInTable) currentRow = numberRowsInTable;
         for (var i = maxRow; i < currentRow; i++) {
             theTable.rows[i].style.display = '';
         }
         if (maxRow == 0) { preText(); firstText(); }
         showPage();
         nextLink();
         lastLink();
     }
     //第一页
     function first() {
         hideTable();
         page = 1;
         for (var i = 0; i < pageSize; i++) {
             theTable.rows[i].style.display = '';
         }
         showPage();
         preText();
         nextLink();
         lastLink();
     }
     //最后一页
     function last() {
         hideTable();
         page = pageCount();
         currentRow = pageSize * (page - 1);
         for (var i = currentRow; i < numberRowsInTable; i++) {
             theTable.rows[i].style.display = '';
         }
         showPage();
         preLink();
         nextText();
         firstLink();
     }

     function hideTable() {
         for (var i = 0; i < numberRowsInTable; i++) {
             theTable.rows[i].style.display = 'none';
         }
     }

     function showPage() {
         pageNum.innerHTML = page;
     }

     //总共页数
     function pageCount() {
         var count = 0;
         if (numberRowsInTable % pageSize != 0) count = 1;
         return parseInt(numberRowsInTable / pageSize) + count;
     }

     //显示链接
     function preLink() { spanPre.innerHTML = "<a href='javascript:pre();'>上一页</a>"; }
     function preText() { spanPre.innerHTML = "上一页"; }
     function nextLink() { spanNext.innerHTML = "<a href='javascript:next();'>下一页</a>"; }
     function nextText() { spanNext.innerHTML = "下一页"; }
     function firstLink() { spanFirst.innerHTML = "<a href='javascript:first();'>首页</a>"; }
     function firstText() { spanFirst.innerHTML = "首页"; }
     function lastLink() { spanLast.innerHTML = "<a href='javascript:last();'>尾页</a>"; }
     function lastText() { spanLast.innerHTML = "尾页"; }
     //隐藏表格
     function hide() {
         for (var i = pageSize; i < numberRowsInTable; i++) {
             theTable.rows[i].style.display = 'none';
         }

         totalPage.innerHTML = pageCount();
         pageNum.innerHTML = '1';

         nextLink();
         lastLink();
     }
     hide();
</script>
<script type="text/javascript" language="javascript">
//导出记录方法
        var idTmr;
    function  getExplorer() {//取浏览器类型
      var explorer = window.navigator.userAgent ;
      //ie 
      if (explorer.indexOf("MSIE") >= 0) {
        return 'ie';
      }
      //firefox 
      else if (explorer.indexOf("Firefox") >= 0) {
        return 'Firefox';
      }
      //Chrome
      else if(explorer.indexOf("Chrome") >= 0){
        return 'Chrome';
      }
      //Opera
      else if(explorer.indexOf("Opera") >= 0){
        return 'Opera';
      }
      //Safari
      else if(explorer.indexOf("Safari") >= 0){
        return 'Safari';
      }
    }
function method1(tableid) {//导出记录到excel
/*      $('table tr').find('td:eq(8)').hide();
      $('table tr').find('th:eq(8)').hide();*/
      if(getExplorer()=='ie')
      {
        var curTbl = document.getElementById(tableid);
        var oXL = new ActiveXObject("Excel.Application");
        //´´½¨AX¶ÔÏóexcel 
        var oWB = oXL.Workbooks.Add();
        //»ñÈ¡workbook¶ÔÏó 
        var xlsheet = oWB.Worksheets(1);
        //¼¤»îµ±Ç°sheet 
        var sel = document.body.createTextRange();
        sel.moveToElementText(curTbl);
        //°Ñ±í¸ñÖÐµÄÄÚÈÝÒÆµ½TextRangeÖÐ 
        sel.select();
        //È«Ñ¡TextRangeÖÐÄÚÈÝ 
        sel.execCommand("Copy");
        //¸´ÖÆTextRangeÖÐÄÚÈÝ  
        xlsheet.Paste();
        //Õ³Ìùµ½»î¶¯µÄEXCELÖÐ       
        oXL.Visible = true;
        //ÉèÖÃexcel¿É¼ûÊôÐÔ

        try {
          var fname = oXL.Application.GetSaveAsFilename("Excel.xls", "Excel Spreadsheets (*.xls), *.xls");
        } catch (e) {
          print("Nested catch caught " + e);
        } finally {
          oWB.SaveAs(fname);

          oWB.Close(savechanges = false);
          //xls.visible = true;
          oXL.Quit();
          oXL = null;
          //½áÊøexcel½ø³Ì£¬ÍË³öÍê³É
          //window.setInterval("Cleanup();",1);
          idTmr = window.setInterval("Cleanup();", 1);
        }
      }else{
        tableToExcel(tableid)
      }
        }
        function Cleanup() {
            window.clearInterval(idTmr);
            CollectGarbage();
        }
    var tableToExcel = (function() {
        var uri = 'data:application/vnd.ms-excel;base64,',
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
        base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) },
        format = function(s, c) {
          return s.replace(/{(\w+)}/g,
          function(m, p) { return c[p]; }) }
        return function(table, name) {
        if (!table.nodeType) table = document.getElementById(table)
        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
        window.location.href = uri + base64(format(template, ctx))
        }
      })()
    </script>
</body>
</html>
