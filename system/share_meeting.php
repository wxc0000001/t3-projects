<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>五好分享会管理</title>
  <script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
  <script type="text/javascript" charset="utf-8" src="ueditor143/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="ueditor143/ueditor.all.min.js"> </script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="ueditor143/lang/zh-cn/zh-cn.js"></script>
<script>  
  var arr ;
  var arr_time;
  var record_id;
  function locking(button_type){ //锁定浮层
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
                data: {"tr_id":tr_id,"table_name":"share_meeting"},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {//加载修改信息
                  if(data!="读取信息失败!"){
                      arr=data.split("##");                      
                      document.getElementById("title").value=arr[1];                        
                      document.getElementById("location").value=arr[2];
                      document.getElementById("begin_time").value=arr[3];
                      document.getElementById("end_time").value=arr[4];
                      document.getElementById("img").value=arr[5].substring(arr[5].lastIndexOf("/")+1,arr[5].length);                      
                      document.getElementById("introduction").value=arr[9];

                      var radio = document.getElementsByName("class");//显示修改记录类别
                      for(i=0;i<radio.length;i++){
                        if(radio[i].value==arr[10]) {
                                 radio[i].checked = true;
                              }
                          }                      

                      UE.getEditor('editor').setContent(arr[6]);
                      var val = arr[8].split(",");
                      var boxes = document.getElementsByName("label");//显示修改记录标签
                       for(i=0;i<boxes.length;i++){
                        for(j=0;j<val.length;j++){
                          if(boxes[i].value == val[j]){
                            boxes[i].checked = true;
                            break;
                          }
                        }
                      }                                        
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
        $("#edit").show();
        $("#add").hide(); 
    }
    else{
 //添加按钮界面
      document.getElementById("title").value="";                        
      document.getElementById("location").value="";     
      document.getElementById("begin_time").value="";     
      document.getElementById("end_time").value="";     
      document.getElementById("img").value="";     
      UE.getEditor('editor').setContent("");         
      document.getElementById("introduction").value="";   
       $("#add").show();
      $("#edit").hide(); 
    }    
   }
//筛选记录
   function filter(){
    var activity_type=$("#activity_type").val();
    if(activity_type=="") alert("请选择活动类型！");
    else{
        var storeId = document.getElementById('table2');//获取table的id标识  
        var rowsLength = storeId.rows.length;//表格总共有多少行  
        var key = activity_type//获取输入框的值  
        var searchCol = 2;//要搜索的哪一列，这里是第一列，从0开始数起  
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
//查询记录
   function search(){
    var key_words=$("#key_words").val();
    if(key_words=="") alert("请输入标题关键字！");
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
//关闭浮层
  function Lock_CheckForm(theForm){   
   document.all.ly.style.display='none';
   document.all.Layer2.style.display='none';
  var radio = document.getElementsByName("class");
    for(i=0;i<radio.length;i++) radio[i].checked = false;
  var boxes = document.getElementsByName("label");
   for(i=0;i<boxes.length;i++){
    boxes[i].checked = false;
   }
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

<script src="js/jQuery.min.2.1.1.js"></script> 
<script src="uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css">

<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/new.css" rel="stylesheet" type="text/css">
<link href="css/parent_child_activity.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
error_reporting(0);//页面加载时显示所有记录
$mysql_info = json_decode(file_get_contents("json/mysql_info.json"));
  $con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
  mysql_select_db("fivekids", $con);
  $sql="select * from share_meeting order by id desc";
  $result = mysql_query($sql,$con);

  $sql_="select id,label_name from activity_label";//取所有标签的id和名称
  $result_ = mysql_query($sql_,$con);
  //$row=mysql_fetch_array($result);
  //$num_rows=mysql_num_rows($result);
  $num_rows=mysql_num_rows($result_);
  while($row_=mysql_fetch_array($result_)){ 
    $label_name[]=$row_["label_name"];
    $id[]=$row_["id"];
  }
?>

<div id="ly" style="display: none;position: absolute;top: 0%;left: 0%;width: 100%;height: 100%;background-color: gray;z-index:2;-moz-opacity: 0.3;opacity:.30;filter: alpha(opacity=30);"> </div>
    <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget no-margin">
                  <div class="widget-header">
                    <div class="title">
                     五好分享会记录
                    </div>
                  </div>
                  <div class="widget-body">
                    <input style="margin-top:-10px;float:left" class="btn btn-sm btn-success" type="button" value="添加新纪录" onClick="locking('add')">
                    <!-- 筛选和查询 -->
                    <div style="text-align:center;margin-top:-10px;">
                    <select name="activity_type" id="activity_type" style="display:inline;width:10%;height:34px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                          <option value="">— 活动标签 —</option>
                          <?php for($i=0;$i<$num_rows;$i++) {?>
                                <option value=<?php echo $label_name[$i]; ?>><?php echo $label_name[$i]; ?></option>
                          <?php }?>
                    </select> &nbsp;
                    <input style="margin-top:-5px;" class="btn btn-sm btn-success" type="button" value="筛选" onClick="filter()">&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" id="key_words" placeholder="标题关键字" style="margin-top:0px;display:inline;width:20%;height:30px;padding:0px 12px;font-size:14px;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                    &nbsp;<input style="margin-top:-5px;" class="btn btn-sm btn-success" type="button" value="查询" onClick="search()">
                    </div>
                    <table class="table no-margin" style="table-layout:fixed;word-wrap:break-word;">
                      <thead>
                        <tr>
                          <th style="width:10%;text-align:center;">id</th>
                          <th style="width:10%;text-align:center;">标题</th>
                          <th style="width:10%;text-align:center;">标签</th>
                          <th style="width:10%;text-align:center;">地点</th>
                          <th style="width:10%;text-align:center;">开始时间</th>
                          <th style="width:10%;text-align:center;">结束时间</th>
                          <th style="width:10%;text-align:center;">介绍图片</th>
                          <th style="width:10%;text-align:center;">介绍</th>
                          <th style="width:10%;text-align:center;">类别</th>
                          <!-- <th style="text-align:center;">审核状态</th> -->                          
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
                        <td style="vertical-align:middle;"><?php echo $row["title"];?></td>
                        <td style="vertical-align:middle;"><?php 
                          $sql_label="select label_name from activity_label where id in (".$row["label"].")";//根据标签id显示名称
                          $result_label = mysql_query($sql_label,$con);
                          $temp= array(); 
                          while($row_label=mysql_fetch_array($result_label)){ 
                            $temp[]=$row_label["label_name"];
                          }
                          echo implode(" | ",$temp);
                        ?></td>
                        <td style="vertical-align:middle;"><?php echo $row["location"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["begin_time"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["end_time"];?></td>
                        <td style="vertical-align:middle;" height="110px"><img width="100%" height="100%" src='<?php echo $row["img_url"];?>'></td>
                        <!-- <td><?php echo $row["label"];?></td> -->
                        <td style="vertical-align:middle;"><?php echo $row["introduction"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["class"];?></td>
                        <!-- <td><?php echo $row["status"];?></td> -->
                        <td style="vertical-align:middle;"><input class="btn btn-sm btn-success" type="button" value="修改" onClick="locking(this)"> 
                          <input id="delete" class="btn btn-sm btn-success" type="button" value="删除" onclick="delTableRow(this)"> 
                          </td>
                        </tr>
                        <?php 
                        $i++;
                      }elseif ($i==1) {?><!-- 记录颜色交替显示 -->
                        <tr align="center">
                        <td style="vertical-align:middle;"><?php echo $row["id"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["title"];?></td>
                        <td style="vertical-align:middle;"><?php 
                          $sql_label="select label_name from activity_label where id in (".$row["label"].")";//根据标签id显示名称
                          $result_label = mysql_query($sql_label,$con);
                          $temp= array(); 
                          while($row_label=mysql_fetch_array($result_label)){ 
                            $temp[]=$row_label["label_name"];
                          }
                          echo implode(" | ",$temp);
                        ?></td>
                        <td style="vertical-align:middle;"><?php echo $row["location"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["begin_time"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["end_time"];?></td>
                         <td style="vertical-align:middle;" height="110px"><img width="100%" height="100%" src='<?php echo $row["img_url"];?>'></td>                  
                        <!-- <td><?php echo $row["label"];?></td> -->
                        <td style="vertical-align:middle;"><?php echo $row["introduction"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["class"];?></td>
                        <!-- <td><?php echo $row["status"];?></td> -->
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
        
    <!--浮层框架开始-->
    <div id="Layer2" align="center" style=" width:600px;position: absolute; z-index: 3; left: 20%; top: 10%;background-color: ; display: none;">
    <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget">
                  <div class="widget-header">
                    <div class="title">
                      添加新纪录 
                    </div>
               <a href=JavaScript:; class="STYLE1" onclick="Lock_CheckForm(this);">[关闭]</a>     
                  </div>
                  <div class="widget-body">
                    <form class="form-horizontal no-margin" >
                      <div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">活动标题</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="title" placeholder="标题">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">活动地点</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="location" placeholder="地点">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">活动时间</label>
                        <div class="col-sm-10">
                          <input type="text" class="Wdate" id="begin_time" onClick="WdatePicker()" placeholder="开始时间">
                          &nbsp;&nbsp;&nbsp;至&nbsp;&nbsp;&nbsp;
                          <input type="text" class="Wdate" id="end_time" onClick="WdatePicker()" placeholder="结束时间">
                        </div>
                      </div>  

                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">活动标签</label>
                        <div class="col-sm-10">
                          <?php for($i=0;$i<$num_rows;$i++) {?>
                          <span style="float:left;"><input id="label" name="label" type="checkbox" value="<?php echo $id[$i]?>" /><?php echo $label_name[$i]?>
                            &nbsp;&nbsp;&nbsp;&nbsp;</span>                 
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">活动介绍</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="introduction" placeholder="介绍">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">活动类别</label>
                        <div class="col-sm-10"> <span style="float:left;">
                          <input type="radio" name="class" value="德">德 &nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="class" value="智">智 &nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="class" value="体">体 &nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="class" value="美">美 &nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="class" value="游">游 &nbsp;&nbsp;&nbsp;&nbsp;
                        </span></div>
                      </div>

                       <div class="form-group">
                        <label for="rptPwd" class="col-sm-2 control-label">介绍图片</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="img"  placeholder="图片" readonly="true">
                          <input type="file" name="uploadify" id="uploadify" />
                        </div>
                        <div id="show"></div>
                      </div>
       
                     <div class="form-group">
                        <label for="rptPwd" class="col-sm-2 control-label">活动详情</label>
                      </div>
                        <div><script id="editor" type="text/plain" style="width:540px;height:300px;"></script></div>
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
    //'fileSizeLimit':1024,
     //checkExisting: 'uploadify/check-exists.php',
    'debug':false,
    'multi':false,//设置为true时可以上传多个文件
    'formData':{'targetFolder':'fivekids/img_upload/share_meeting/'},
    'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
  
    'onUploadStart':function(file){//上传前检查图片大小
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
      imgObj="<p><img width='100px' height='100px' src='http://t3china.t3group.cn/fivekids/img_upload/share_meeting/"+file.name+"'></p>"
      $("#show").empty();
      $("#show").append(imgObj);
    }
  });
});

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
                data: {"tr_id":tr_id,"table_name":"share_meeting"},
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

  var title=$("#title").val();
  var location=$("#location").val();
  var begin_time=$("#begin_time").val();
  var end_time=$("#end_time").val();
  var introduction=$("#introduction").val();  
  var img_url="http://t3china.t3group.cn/fivekids/img_upload/share_meeting/"+document.getElementById("img").value;
  //取选中类别
  var radio=document.getElementsByName("class");
  var radio_checked;
  for(var i=0;i<radio.length;i++){
       if(radio[i].checked==true)
          radio_checked=radio[i].value;
   } 
  var j=0;
  var result= new Array();
  //取选中标签
  var check_array=document.getElementsByName("label");
       for(var i=0;i<check_array.length;i++)
       {
           if(check_array[i].checked==true)
           {         
              result[j]=check_array[i].value;
              j++;
           }
       } 
  var label=result.join(",");
  //取编辑器内容
  var arr = [];
  arr.push(UE.getEditor('editor').getContent());
  var content=arr.join("\n"); 
   if(title!=""&&location!=""&&begin_time!=""&&end_time!=""&&document.getElementById("img").value!=""){
      if(begin_time<=end_time){
        $.ajax({
                url: "php/insert_edit_carnival.php",
                type: "post",
                data: {"table_name":"share_meeting","change_type":"add","title":title,"location":location,"begin_time":begin_time,"end_time":end_time,
                      "img_url":img_url,"content":content,"label":label,"introduction":introduction,"class":radio_checked},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {
                    if(data=="1") {
                      alert("添加成功！");
                      document.all.ly.style.display='none';
                      document.all.Layer2.style.display='none';
                      window.location.reload(true);
                    }                      
                    else if(data=="0")
                      alert("该记录已存在！请重新输入！");
                    else
                      alert("添加出错！"+data);                      
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
            });
      }else alert("开始时间不能小于结束时间！");
  }else alert("输入不能为空！");

});
//修改记录
$("#edit").click(function(){
  var title=$("#title").val();
  var location=$("#location").val();
  var begin_time=$("#begin_time").val();
  var end_time=$("#end_time").val();
  var introduction=$("#introduction").val();  
  var img_url="http://t3china.t3group.cn/fivekids/img_upload/share_meeting/"+document.getElementById("img").value;
  //取选中类别
  var radio=document.getElementsByName("class");
  var radio_checked;
  for(var i=0;i<radio.length;i++){
       if(radio[i].checked==true)
          radio_checked=radio[i].value;
   }  
  var j=0;
  var result= new Array();
  //取选中标签
  var check_array=document.getElementsByName("label");
       for(var i=0;i<check_array.length;i++)
       {
           if(check_array[i].checked==true)
           {         
              result[j]=check_array[i].value;
              j++;
           }
       } 
  var label=result.join(",");
  //取编辑器内容
  var arr = [];
  arr.push(UE.getEditor('editor').getContent());
  var content=arr.join("\n");

   if(title!=""&&location!=""&&begin_time!=""&&end_time!=""&&document.getElementById("img").value!=""){
      if(begin_time<=end_time){
        $.ajax({
                url: "php/insert_edit_carnival.php",
                type: "post",
                data: {"table_name":"share_meeting","record_id":record_id,"change_type":"edit","title":title,"location":location,"begin_time":begin_time,"end_time":end_time,
                      "img_url":img_url,"content":content,"label":label,"introduction":introduction,"class":radio_checked},
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
                    else if(data=="0")
                      alert("该记录已存在！请重新输入！");
                    else
                      alert("修改出错！"+data);                      
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
            });
      }else alert("开始时间不能小于结束时间！");
  }else alert("输入不能为空！");
});
</script>
<script type="text/javascript">
    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor');

    function isFocus(e){
        alert(UE.getEditor('editor').isFocus());
        UE.dom.domUtils.preventDefault(e)
    }
    function setblur(e){
        UE.getEditor('editor').blur();
        UE.dom.domUtils.preventDefault(e)
    }
    function insertHtml() {
        var value = prompt('插入html代码', '');
        UE.getEditor('editor').execCommand('insertHtml', value)
    }
    function createEditor() {
        enableBtn();
        UE.getEditor('editor');
    }
    function getAllHtml() {
        alert(UE.getEditor('editor').getAllHtml())
    }
    function getContent() {
        var arr = [];
        arr.push("使用editor.getContent()方法可以获得编辑器的内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getContent());
        alert(arr.join("\n"));
    }
    function getPlainTxt() {
        var arr = [];
        arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getPlainTxt());
        alert(arr.join('\n'))
    }
    function setContent(isAppendTo) {
        var arr = [];
        arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
        UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
        alert(arr.join("\n"));
    }
    function setDisabled() {
        UE.getEditor('editor').setDisabled('fullscreen');
        disableBtn("enable");
    }

    function setEnabled() {
        UE.getEditor('editor').setEnabled();
        enableBtn();
    }

    function getText() {
        //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
        var range = UE.getEditor('editor').selection.getRange();
        range.select();
        var txt = UE.getEditor('editor').selection.getText();
        alert(txt)
    }

    function getContentTxt() {
        var arr = [];
        arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
        arr.push("编辑器的纯文本内容为：");
        arr.push(UE.getEditor('editor').getContentTxt());
        alert(arr.join("\n"));
    }
    function hasContent() {
        var arr = [];
        arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
        arr.push("判断结果为：");
        arr.push(UE.getEditor('editor').hasContents());
        alert(arr.join("\n"));
    }
    function setFocus() {
        UE.getEditor('editor').focus();
    }
    function deleteEditor() {
        disableBtn();
        UE.getEditor('editor').destroy();
    }
    function disableBtn(str) {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            if (btn.id == str) {
                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
            } else {
                btn.setAttribute("disabled", "true");
            }
        }
    }
    function enableBtn() {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
        }
    }

    function getLocalData () {
        alert(UE.getEditor('editor').execCommand( "getlocaldata" ));
    }

    function clearLocalData () {
        UE.getEditor('editor').execCommand( "clearlocaldata" );
        alert("已清空草稿箱")
    }
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
</body>
</html>
