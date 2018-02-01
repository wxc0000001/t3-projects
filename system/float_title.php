<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>页面横幅管理</title>
<script>  
  var arr ;
  var arr_time;
  var record_id;
  //添加和修改浮层控制
  function locking(button_type){   
    //显示浮层
     document.all.ly.style.display="block";   
     document.all.ly.style.width=document.body.clientWidth;   
     document.all.ly.style.height=document.body.clientHeight;   
     document.all.Layer2.style.display='block'; 
     if(button_type!="add"){
      //修改时浮层内容加载
      var row = button_type.parentNode.parentNode; 
      var index = row.rowIndex; 
      var tr_id = $(".table tbody tr").eq(parseInt(index)-1).children("td").eq(0).html();
      record_id=tr_id;
      $.ajax({
                url: "php/enquiry_activity.php",
                type: "post",
                data: {"tr_id":tr_id,"table_name":"float_title"},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {
                  if(data!="读取信息失败!"){
                      arr=data.split("##");                       
                      document.getElementById("img").value=arr[1].substring(arr[1].lastIndexOf("/")+1,arr[1].length);
                      document.all.activity_type_.value=arr[3];
                      document.getElementById("index_num").value=arr[4];
                      //读取并显示横幅类别，四类横幅分别查询和显示
                      switch (arr[3]){
                        case "carnival"://嘉年华
                          $.ajax({
                                url: "php/enquiry_activity.php",
                                type: "post",
                                data: {"tr_id":arr[2],"table_name":"carnival"},
                                dataType: "text",
                                success: function(data) {
                                  arr_=data.split("##");    
                                  document.all.note.value=arr_[1];
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert(XMLHttpRequest.status);
                                    alert(XMLHttpRequest.readyState);
                                    alert(textStatus);
                                }
                          });
                          break;
                        case "share_meeting"://分享会
                          $.ajax({
                                url: "php/enquiry_activity.php",
                                type: "post",
                                data: {"tr_id":arr[2],"table_name":"share_meeting"},
                                dataType: "text",
                                success: function(data) {
                                  arr_=data.split("##");    
                                  document.all.note.value=arr_[1];
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert(XMLHttpRequest.status);
                                    alert(XMLHttpRequest.readyState);
                                    alert(textStatus);
                                }
                          });
                          break;
                        case "expert": //我是行家
                          document.all.note.value=arr[2];
                          document.all.note.disabled='';
                          break;
                        case "activity_apply"://活动报名
                          var arr_=arr[2].split(",");
                          $.ajax({
                                url: "php/enquiry_activity.php",
                                type: "post",
                                data: {"tr_id":arr_[0],"table_name":arr_[1]},
                                dataType: "text",
                                success: function(data) {
                                  arr_apply=data.split("##");
                                  if(arr_[1]=="carnival")  document.all.note.value=arr_apply[1]+",五好嘉年华";  
                                  else if(arr_[1]=="share_meeting")  document.all.note.value=arr_apply[1]+",五好分享会";  
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert(XMLHttpRequest.status);
                                    alert(XMLHttpRequest.readyState);
                                    alert(textStatus);
                                }
                          });
                          break;
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
      document.getElementById("img").value="";
      document.getElementById("index_num").value="";
      $("#add").show();
      $("#edit").hide(); 
    }  
   }
//记录筛选
  function filter(){
    var activity_type=$("#activity_type").val();
    if(activity_type=="") alert("请选择横幅类型！");
    else{
        var storeId = document.getElementById('table2');//获取table的id标识  
        var rowsLength = storeId.rows.length;//表格总共有多少行  
        var key = activity_type//获取输入框的值  
        var searchCol = 3;//要搜索的哪一列，这里是第一列，从0开始数起  
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
//关闭浮层方法
  function Lock_CheckForm(theForm){   
   document.all.ly.style.display='none';
   document.all.Layer2.style.display='none';
   document.all.note.disabled='disabled';
   //清除浮层内容
   document.all.note.placeholder='';
   document.all.note.value='';
   document.all.activity_type.value='';
   var radio = document.getElementsByName("activity_type");
    for(i=0;i<radio.length;i++) radio[i].checked = false;
   return false;   
   }
</script>
<style type="text/css">
.STYLE1 {/*浮层关闭按钮样式*/
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
<link rel="stylesheet" href="css/drop_down_list.css" type="text/css" media="screen">
</head>
<body>

<?php//页面加载时查询所有数据
error_reporting(0);
$mysql_info = json_decode(file_get_contents("json/mysql_info.json"));
  $con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);
  mysql_select_db("fivekids", $con);
  $sql="select * from float_title order by activity_type,index_num";
  $result = mysql_query($sql,$con);
  //$row=mysql_fetch_array($result);
  //$num_rows=mysql_num_rows($result);
  $sql_carnival="select id,title from carnival order by id desc";//取嘉年华的id和标题
  $result_carnival = mysql_query($sql_carnival,$con);
  $num_carnival=mysql_num_rows($result_carnival);
  while($row_carnival=mysql_fetch_array($result_carnival)){ 
    $carnival_id[]=$row_carnival["id"];
    $carnival_title[]=$row_carnival["title"];
  }
  $sql_meeting="select id,title from share_meeting order by id desc";//取分享会的id和标题
  $result_meeting = mysql_query($sql_meeting,$con);
  $num_meeting=mysql_num_rows($result_meeting);
  while($row_meeting=mysql_fetch_array($result_meeting)){ 
    $meeting_id[]=$row_meeting["id"];
    $meeting_title[]=$row_meeting["title"];
  }
?>

<div id="ly" style="display: none;position: absolute;top: 0%;left: 0%;width: 100%;height: 100%;background-color: gray;z-index:2;-moz-opacity: 0.3;opacity:.30;filter: alpha(opacity=30);"> </div>
		<div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget no-margin">
                  <div class="widget-header">
                    <div class="title">
                     页面横幅
                    </div>
                  </div>
                  <div class="widget-body" >
                    <input style="margin-top:-10px;float:left" class="btn btn-sm btn-success" type="button" value="添加新纪录" onClick="locking('add')">
                    <div style="text-align:center;margin-top:-10px;">
                      <!-- 筛选框和按钮 -->
                    <select name="activity_type" id="activity_type" style="display:inline;width:10%;height:34px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                          <option value="">— 活动类别 —</option>
                          <option value="五好分享会">五好分享会</option> 
                          <option value="五好嘉年华">五好嘉年华</option> 
                          <option value="我是行家">我是行家</option> 
                          <option value="活动报名">活动报名</option> 
                    </select> &nbsp;
                    <input style="margin-top:-5px;" class="btn btn-sm btn-success" type="button" value="筛选" onClick="filter()">
                    </div>
                    <table class="table no-margin" style="table-layout:fixed;word-wrap:break-word;">
                      <thead>
                        <tr>
                          <th style="width:5%;text-align:center;">id</th>
                          <th style="width:20%;text-align:center;">横幅图片</th>
                          <th style="width:45%;text-align:center;">横幅链接</th>
                          <th style="width:10%;text-align:center;">横幅类别</th>
            						  <th style="width:10%;text-align:center;">优先顺序</th>
            						  <th style="width:10%;text-align:center;">操作</th>
                        </tr>
                      </thead>
                      <tbody id="table2">
                        <?php 
                        $i=0;
                        while($row=mysql_fetch_assoc($result)){
                        if($i==0){//记录颜色交替显示
                        ?>
                        <tr align="center" class="success">
                        <td style="vertical-align:middle;"><?php echo $row["id"];?></td>
                        <td style="vertical-align:middle;" height="130px"><img width="100%" height="100%" src='<?php echo $row["img_url"];?>'></td>
                        <td style="vertical-align:middle;"><?php 
                        if($row["activity_type"]=="carnival"||$row["activity_type"]=="share_meeting"){//根据id取嘉年华和分享会标题
                          $sql_title="select title from ".$row["activity_type"]." where id='".$row["detail_url"]."'";
                          $result_title = mysql_query($sql_title,$con);
                          while($row_title=mysql_fetch_array($result_title)){ 
                            echo $row_title["title"];
                          }
                        }else if($row["activity_type"]=="expert"){//行家文章链接
                          echo $row["detail_url"];
                        }else{ //活动报名
                           if(strstr($row["detail_url"], "carnival")){//活动报名-嘉年华
                            preg_match('|(\d+)|',$row["detail_url"],$r); //取数字
                            $sql_title="select title from carnival where id='".$r[1]."'";//取标题
                            $result_title = mysql_query($sql_title,$con);
                            while($row_title=mysql_fetch_array($result_title)){ 
                              echo $row_title["title"];
                            }
                          }else if(strstr($row["detail_url"], "share_meeting")){//活动报名-分享会
                            preg_match('|(\d+)|',$row["detail_url"],$r); //取数字
                            $sql_title="select title from share_meeting where id='".$r[1]."'";//取标题
                            $result_title = mysql_query($sql_title,$con);
                            while($row_title=mysql_fetch_array($result_title)){ 
                              echo $row_title["title"];
                            }
                          }
                        }
                        ?></td>
                        <td style="vertical-align:middle;"><?php 
                        switch ($row["activity_type"])
                        {
                        case "carnival": echo "五好嘉年华";break;
                        case "expert": echo "我是行家";break;
                        case "activity_apply": echo "活动报名";break;
                        case "share_meeting": echo "五好分享会";break;
                        }
                        ?></td>
                        <td style="vertical-align:middle;"><?php echo $row["index_num"];?></td>
                        <td style="vertical-align:middle;"><input class="btn btn-sm btn-success" type="button" value="修改" onClick="locking(this)"> 
                          <input id="delete" class="btn btn-sm btn-success" type="button" value="删除" onclick="delTableRow(this)"> 
                          </td>
                        </tr>
                        <?php 
                        $i++;
                      }elseif ($i==1) {?><!-- 记录颜色交替显示 -->
                        <tr align="center">
                        <td style="vertical-align:middle;"><?php echo $row["id"];?></td>
                        <td style="vertical-align:middle;" height="130px"><img width="100%" height="100%" src='<?php echo $row["img_url"];?>'></td>
                        <td style="vertical-align:middle;"><?php //嘉年华和分享会链接
                        if($row["activity_type"]=="carnival"||$row["activity_type"]=="share_meeting"){
                          $sql_title="select title from ".$row["activity_type"]." where id='".$row["detail_url"]."'";
                          $result_title = mysql_query($sql_title,$con);
                          while($row_title=mysql_fetch_array($result_title)){ 
                            echo $row_title["title"];
                          }
                        }else if($row["activity_type"]=="expert"){//行家文章链接
                          echo $row["detail_url"];
                        }else{ //活动报名
                           if(strstr($row["detail_url"], "carnival")){//活动报名-嘉年华
                            preg_match('|(\d+)|',$row["detail_url"],$r); //取数字
                            $sql_title="select title from carnival where id='".$r[1]."'";//取标题
                            $result_title = mysql_query($sql_title,$con);
                            while($row_title=mysql_fetch_array($result_title)){ 
                              echo $row_title["title"];
                            }
                          }else if(strstr($row["detail_url"], "share_meeting")){//活动报名-分享会
                            preg_match('|(\d+)|',$row["detail_url"],$r); //取数字
                            $sql_title="select title from share_meeting where id='".$r[1]."'";//取标题
                            $result_title = mysql_query($sql_title,$con);
                            while($row_title=mysql_fetch_array($result_title)){ 
                              echo $row_title["title"];
                            }
                          }
                        }
                        ?></td>
                        <td style="vertical-align:middle;"><?php 
                        switch ($row["activity_type"])
                        {
                        case "carnival": echo "五好嘉年华";break;
                        case "expert": echo "我是行家";break;
                        case "activity_apply": echo "活动报名";break;
                        case "share_meeting": echo "五好分享会";break;
                        }
                        ?></td>
                        <td style="vertical-align:middle;"><?php echo $row["index_num"];?></td>
                        <td style="vertical-align:middle;"><input class="btn btn-sm btn-success" type="button" value="修改" onClick="locking(this)"> 
                          <input id="delete" class="btn btn-sm btn-success" type="button" value="删除" onclick="delTableRow(this)"> 
                          </td>
                        </tr>
                        <?php $i--;
                      }
                    }
                       ?>
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
    <!---浮层框架开始-->
    <div id="Layer2" align="center" style=" width:600px;position: absolute; z-index: 3; left: 20%; top: 10%;background-color: ; display: none;">
		<div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
                     横幅信息
                    </div>
               <a href=JavaScript:; class="STYLE1" onclick="Lock_CheckForm(this);">[关闭]</a>     
                  </div>
                  <div class="widget-body">
                    <form class="form-horizontal no-margin" >
                      <div class="form-group">
                        <label for="emailId" class="col-sm-2 control-label">横幅类别</label> 
                        <div class="example">
                            <ul id="nav">
                                <li><a class="fly" href="#">横幅选择</a>
                                    <ul class="dd">
                                        <li><a class="fly" href="#" onclick="document.all.activity_type_.value='carnival';document.all.note.disabled='disabled';document.all.note.placeholder=''">五好嘉年华</a>
                                            <ul>
                                              <?php for($i=0;$i<$num_carnival;$i++) {?>
                                              <li><a href="#" onclick="document.all.note.value='<?php echo $carnival_title[$i]; ?>'"><?php echo $carnival_title[$i]; ?></a></li>
                                              <?php }?>
                                            </ul>
                                        </li>
                                        <li><a class="fly" href="#" onclick="document.all.activity_type_.value='share_meeting';document.all.note.disabled='disabled';document.all.note.placeholder=''">五好分享会</a>
                                            <ul>
                                               <?php for($i=0;$i<$num_meeting;$i++) {?>
                                              <li><a href="#" onclick="document.all.note.value='<?php echo $meeting_title[$i]; ?>'"><?php echo $meeting_title[$i]; ?></a></li>
                                              <?php }?>
                                            </ul>
                                        </li>
                                        <li><a class="fly" href="#" onclick="document.all.activity_type_.value='activity_apply';document.all.note.disabled='disabled';document.all.note.placeholder=''">活动报名</a>
                                            <ul>
                                                <li><a class="fly" href="#">五好嘉年华</a>
                                                    <ul>
                                                        <?php for($i=0;$i<$num_carnival;$i++) {?>
                                                        <li><a href="#" onclick="document.all.note.value='<?php echo $carnival_title[$i]; ?>,五好嘉年华'"><?php echo $carnival_title[$i]; ?></a></li>
                                                        <?php }?>
                                                    </ul>
                                                </li>
                                                <li><a class="fly" href="#">五好分享会</a>
                                                    <ul>
                                                        <?php for($i=0;$i<$num_meeting;$i++) {?>
                                                        <li><a href="#" onclick="document.all.note.value='<?php echo $meeting_title[$i]; ?>,五好分享会'"><?php echo $meeting_title[$i]; ?></a></li>
                                                        <?php }?>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href="#" onclick="document.all.activity_type_.value='expert';document.all.note.disabled='';document.all.note.value='';document.all.note.placeholder='行家文章链接'">我是行家</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <input type="hidden" name="activity_type_" id="activity_type_" value=""></input>
                        <input type="text" name="note" id="note" disabled="disabled" style="display:inline;width:55%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;border:1px solid #ccc;border-radius:4px;">
                      </div>
                      <div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">优先顺序</label>
                        <div class="col-sm-10"> 
                          <input type="text" class="form-control" id="index_num" placeholder="顺序">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="rptPwd" class="col-sm-2 control-label">横幅图片</label>
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
//图片上传控件方法
var filename;
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
    'formData':{'targetFolder':'fivekids/img_upload/float_title'},
    'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
  
    'onUploadStart':function(file){//开始上传前验证大小
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
      imgObj="<p><img width='100px' height='100px' src='http://t3china.t3group.cn/fivekids/img_upload/float_title/"+file.name+"'></p>"
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
                data: {"tr_id":tr_id,"table_name":"float_title"},
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
  var activity_type=$("#activity_type_").val();
	var detail_url=$("#note").val();
  var index_num=$("#index_num").val();
	var img_url="http://t3china.t3group.cn/fivekids/img_upload/float_title/"+document.getElementById("img").value;
    if(detail_url!=""&&document.getElementById("img").value!=""&&activity_type!=""&&index_num!=""){
      if(/^\d+$/.test(index_num)){  
        $.ajax({
                url: "php/insert_edit_float_title.php",
                type: "post",
                data: {"change_type":"add","detail_url":detail_url,"img_url":img_url,"activity_type":activity_type,"index_num":index_num},
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
      }else alert("优先顺序必须为数字！");
  }else alert("输入不能为空！");
});
//修改记录
$("#edit").click(function(){
  var detail_url=$("#note").val();
  var index_num=$("#index_num").val();
  var img_url="http://t3china.t3group.cn/fivekids/img_upload/float_title/"+document.getElementById("img").value;
  var activity_type="";
  if($("#activity_type_").val()=="") activity_type
  else activity_type=$("#activity_type_").val();
    if(detail_url!=""&&document.getElementById("img").value!=""&&activity_type!=""&&index_num!=""){
      if(/^\d+$/.test(index_num)){  
          $.ajax({
                url: "php/insert_edit_float_title.php",
                type: "post",
                data: {"record_id":record_id,"change_type":"edit","detail_url":detail_url,"img_url":img_url,"activity_type":activity_type,"index_num":index_num},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {
                    if(data=="1") {
                      alert("修改成功！");
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
      }else alert("优先顺序必须为数字！");
  }else alert("输入不能为空！");
});
</script>
 <script>
 //显示页码
     var theTable = document.getElementById("table2");
     var totalPage = document.getElementById("spanTotalPage");
     var pageNum = document.getElementById("spanPageNum");


     var spanPre = document.getElementById("spanPre");
     var spanNext = document.getElementById("spanNext");
     var spanFirst = document.getElementById("spanFirst");
     var spanLast = document.getElementById("spanLast");


     var numberRowsInTable = theTable.rows.length;
     var pageSize = 4;
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
</body>
</html>
