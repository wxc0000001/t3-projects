<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>活动报名管理</title>
  <script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script>  
  var arr ;
  var arr_time;
  var record_id;
//选择框关联方法 
function link(x,bool,flag){ 
  var form2=document.getElementById("activity_type").options.length;//这句解释同上 
  var diqul=new Array(form2)//新建一个数组，项数为第一个下拉列表的项数 
  for(i=0;i<form2;i++)//循环第一个下拉列表的项数那么多次 
  diqul[i]=new Array();//子循环 
  //下面是给每个循环赋值 
  var activity_id=document.all.activity_id;//方便引用 
  var activity_id_title=document.all.activity_id_title;
  var table_name;
  if(x=="1") table_name="share_meeting"
  else if(x=="2") table_name="carnival"
/*  var obj = document.all.activity_type; //定位id
  var index = obj.selectedIndex; // 选中索引
  var table_name = obj.options[x].value; // 选中值*/
  var num;
  //关联两个选择框
  $.ajax({
            url: "php/link.php",
            type: "post",
            data: {"table_name":table_name},
            dataType: "json",
            //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
            //timeout:10000,
            success: function(data) {
              num=data.length;
              for(var p=1;p<3;p++){
                for(var q=0;q<num;q++){
                    diqul[p][q]=new Option(data[q].title,data[q].id); 
                }
              }
              if(flag=="content"){//浮层的选择框关联
                for(m=activity_id.options.length-1;m>0;m--) 
                  activity_id.options[m]=null;//将该项设置为空,也就等于清除了 
                for(j=0;j<diqul[x].length;j++)//这个循环是填充下拉列表 
                    activity_id.options[j]=new Option(diqul[x][j].text,diqul[x][j].value); //注意上面这据,列表的当前项等于 新项(数组对象的x,j项的文本为文本，) 
                if(bool!=0) {
                  for(var i=1; i<=num; i++) { 
                      if(activity_id.options[i].value==bool) 
                        activity_id.options[i].selected=true;
                    }
                }
              }else if(flag=="title"){//界面的选择框关联
                for(m=activity_id_title.options.length-1;m>0;m--) 
                    activity_id_title.options[m]=null;//将该项设置为空,也就等于清除了 
                for(j=0;j<diqul[x].length;j++)//这个循环是填充下拉列表 
                    activity_id_title.options[j]=new Option(diqul[x][j].text,diqul[x][j].text); //注意上面这据,列表的当前项等于 新项(数组对象的x,j项的文本为文本，) 
                if(bool!=0) {
                  for(var i=1; i<=num; i++) { 
                      if(activity_id_title.options[i].value==bool) 
                        activity_id_title.options[i].selected=true;
                    }
                }
              }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
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
                data: {"tr_id":tr_id,"table_name":"activity_apply"},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {
                  if(data!="读取信息失败!"){
                      arr=data.split("##");                      
                      document.getElementById("username").value=arr[1];                        
                      document.getElementById("apply_time").value=arr[2];
                      document.getElementById("mobile").value=arr[3];
                      document.getElementById("adult_num").value=arr[4];
                      document.getElementById("child_num").value=arr[5];
                      //document.getElementById("user_open_id").value=arr[8];
                      var parent=document.all.activity_type;
                      if(arr[6]=="carnival") parent.options.selectedIndex=2;//浮层内选择框加载和关联
                      else parent.options.selectedIndex=1; 
                      link(parent.options.selectedIndex,arr[7],'content');                                                      
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
      document.getElementById("username").value="";                        
      document.getElementById("apply_time").value="";      
      document.getElementById("mobile").value="";      
      document.getElementById("adult_num").value="";      
      document.getElementById("child_num").value="";                   
      //document.getElementById("user_open_id").value="";        
       $("#add").show();
      $("#edit").hide(); 
    }    
   }
//筛选记录
function reload(){
    //每次筛选前先清空隐藏table中的内容，
    var tb = document.getElementById('table_hidden');
    var rowNum=tb.rows.length;
    for (i=0;i<rowNum;i++) 
    {
        tb.deleteRow(i);
        rowNum=rowNum-1;
        i=i-1;
    }
    $.ajax({//清空隐藏table内容后，重新加入所有记录
            url: "php/filter.php",
            type: "post",
            data: {},
            dataType: "json",
            //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
            //timeout:10000,
            success: function(data) {
              for(var i=0;i<data.length;i++){
                var newTr = tb.insertRow(i);//添加新行，trIndex就是要添加的位置 
                var newTd1 = newTr.insertCell(); 
                newTd1.innerHTML = data[i].id; 
                var newTd2 = newTr.insertCell(); 
                newTd2.innerHTML = data[i].title; 
                var newTd3 = newTr.insertCell(); 
                newTd3.innerHTML = data[i].activity_type; 
                var newTd4 = newTr.insertCell(); 
                newTd4.innerHTML = data[i].username; 
                var newTd5 = newTr.insertCell(); 
                newTd5.innerHTML = data[i].apply_time; 
                var newTd6 = newTr.insertCell(); 
                newTd6.innerHTML = data[i].mobile; 
                var newTd7 = newTr.insertCell(); 
                newTd7.innerHTML = data[i].adult_num; 
                var newTd8 = newTr.insertCell(); 
                newTd8.innerHTML = data[i].child_num; 
              }
              filter();//最后再进行筛选
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
    }); 
}
//筛选记录
  function filter(){
    var activity_type=$("#activity_type_title").val();
    var activity_id=$("#activity_id_title").val();
    if(activity_type=="") alert("请选择活动类型！");
    else{
      //筛选显示的table记录
        var storeId = document.getElementById('table2');//获取table的id标识  
        var rowsLength = storeId.rows.length;//表格总共有多少行  
        var key = activity_id//获取输入框的值  
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
      //筛选隐藏的table记录
        var storeId_hidden = document.getElementById('table_hidden');//获取table的id标识  
        var rowsLength_hidden = storeId_hidden.rows.length;//表格总共有多少行
        var j=0;
        for(var i=0;i<rowsLength_hidden;i++){//按表的行数进行循环，本例第一行是标题，所以i=1，从第二行开始筛选（从0数起）  
            var searchText_hidden = storeId_hidden.rows[j].cells[searchCol].innerHTML;//取得table行，列的值  
            if(searchText_hidden.match(key)){//用match函数进行筛选，如果input的值，即变量 key的值为空，返回的是ture， 
                j++;
            }else{
                storeId_hidden.deleteRow(j);
            }
        }//console.log(j);
      }
   }
//关闭浮层，清除控件值
  function Lock_CheckForm(theForm){   
   document.all.ly.style.display='none';
   document.all.Layer2.style.display='none';
   document.all.activity_type.options.selectedIndex =0;
   var child=document.all.activity_id;
   for(var i=child.options.length-1; i>=0; i--) child.remove(i);
   child.options[0]= new Option("— 活动选择 —","");
    child.options.selectedIndex =0;

   var radio = document.getElementsByName("activity_type");
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
  $sql="select * from activity_apply order by id desc";
  $result = mysql_query($sql,$con);
?>

<div id="ly" style="display: none;position: absolute;top: 0%;left: 0%;width: 100%;height: 100%;background-color: gray;z-index:2;-moz-opacity: 0.3;opacity:.30;filter: alpha(opacity=30);"> </div>
    <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget no-margin">
                  <div class="widget-header">
                    <div class="title">
                     活动报名记录
                    </div>
                  </div>
                  <div class="widget-body"><div style="text-align:left;margin-top:-10px;position:absolute;">
                    <input class="btn btn-sm btn-success" type="button" value="添加新纪录" onClick="locking('add')">&nbsp;&nbsp;
                    <input class="btn btn-sm btn-success" type="button" value="导出记录" onClick="javascript:method1('table')"></div>
                    <!-- 筛选活动 -->
                    <div style="text-align:center;margin-top:-10px;">
                      <select id="activity_type_title" onchange="link(options.selectedIndex,0,'title')" style="display:inline;width:12%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                          <option value="">— 活动类别 —</option>
                          <option value="share_meeting">五好分享会</option> 
                          <option value="carnival">五好嘉年华</option> 
                      </select> &nbsp;&nbsp;
                      <select id="activity_id_title" style="display:inline;width:12%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                          <option value="">— 活动选择 —</option>
                      </select> &nbsp;&nbsp;
                      <input style="margin-top:-5px;" class="btn btn-sm btn-success" type="button" value="筛选" onClick="reload()">
                    </div>
                    <!-- 用于导出记录的隐藏table -->
                    <table id="table" style="display:none;">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>活动名称</th>
                          <th>活动类别</th>
                          <th>用户名</th>
                          <th>申请时间</th>
                          <th>联系方式</th>
                          <th>成人数目</th>
                          <th>儿童数目</th>
                        </tr>
                      </thead>
                      <tbody id="table_hidden">
                        <?php //显示所有记录
                         $sql_invisible="select * from activity_apply order by id desc";
                        $result_invisible = mysql_query($sql_invisible,$con);
                        while($row_invisible=mysql_fetch_assoc($result_invisible)){
                        ?>
                        <tr>
                          <td><?php echo $row_invisible["id"];?></td>
                          <td style="vertical-align:middle;"><?php //根据id查询标题
                            $sql_title_invisible="select title from ".$row_invisible["activity_type"]." where id='".$row_invisible["activity_id"]."'";
                            $result_title_invisible = mysql_query($sql_title_invisible,$con);
                            while($row_title_invisible=mysql_fetch_array($result_title_invisible)){ 
                              echo $row_title_invisible["title"];
                            }
                          ?></td>
                          <td><?php 
                          if($row_invisible["activity_type"]=="share_meeting") echo "五好分享会";
                          else echo "五好嘉年华";
                          ?></td>
                          <td><?php echo $row_invisible["username"];?></td>
                          <td><?php echo $row_invisible["apply_time"];?></td>
                          <td><?php echo $row_invisible["mobile"];?></td>
                          <td><?php echo $row_invisible["adult_num"];?></td>
                          <td><?php echo $row_invisible["child_num"];?></td> 
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    <!-- 用于显示记录的table -->
                    <table class="table no-margin" style="table-layout:fixed;word-wrap:break-word;">
                      <thead>
                        <tr>
                          <th style="width:5%;text-align:center;">id</th>
                          <th style="width:20%;text-align:center;">活动名称</th>
                          <th style="width:15%;text-align:center;">活动类别</th>
                          <th style="width:15%;text-align:center;">用户名</th>
                          <th style="width:10%;text-align:center;">申请时间</th>
                          <th style="width:10%;text-align:center;">联系方式</th>
                          <th style="width:8%;text-align:center;">成人数目</th>
                          <th style="width:8%;text-align:center;">儿童数目</th>
                          <!-- <th style="text-align:center;">用户id</th> -->                      
                          <th class="t" style="width:10%;text-align:center;">操作</th>
                        </tr>
                      </thead>
                      <tbody id="table2">
                        <?php 
                        $i=0;
                        while($row=mysql_fetch_assoc($result)){
                        if($i==0){//记录颜色交替显示
                        ?>
                        <tr align="center" class="success">
                        <td style="vertical-align:middle;"height="30px"><?php echo $row["id"];?></td>
                        <td style="vertical-align:middle;"><?php //根据id查询标题
                          $sql_title="select title from ".$row["activity_type"]." where id='".$row["activity_id"]."'";
                          $result_title = mysql_query($sql_title,$con);
                          while($row_title=mysql_fetch_array($result_title)){ 
                            echo $row_title["title"];
                          }
                        ?></td>
                        <td style="vertical-align:middle;"><?php 
                        if($row["activity_type"]=="share_meeting") echo "五好分享会";
                        else echo "五好嘉年华";
                        ?></td>
                        <td style="vertical-align:middle;"><?php echo $row["username"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["apply_time"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["mobile"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["adult_num"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["child_num"];?></td> 
                        <!-- <td><?php echo $row["user_open_id"];?></td> -->
                        <td class="t" style="vertical-align:middle;"><input class="btn btn-sm btn-success" type="button" value="修改" onClick="locking(this)"> 
                          <input id="delete" class="btn btn-sm btn-success" type="button" value="删除" onclick="delTableRow(this)"> 
                          </td>
                        </tr>

                        <?php 
                        $i++;
                      }elseif ($i==1) {?><!-- 记录颜色交替显示 -->
                        <tr align="center">
                        <td style="vertical-align:middle;" height="30px"><?php echo $row["id"];?></td>
                        <td style="vertical-align:middle;"><?php //根据id查询标题
                          $sql_title="select title from ".$row["activity_type"]." where id='".$row["activity_id"]."'";
                          $result_title = mysql_query($sql_title,$con);
                          while($row_title=mysql_fetch_array($result_title)){ 
                            echo $row_title["title"];
                          }
                        ?></td>
                        <td style="vertical-align:middle;"><?php 
                        if($row["activity_type"]=="share_meeting") echo "五好分享会";
                        else echo "五好嘉年华";
                        ?></td>
                        <td style="vertical-align:middle;"><?php echo $row["username"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["apply_time"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["mobile"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["adult_num"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["child_num"];?></td>  
                        <!-- <td><?php echo $row["user_open_id"];?></td> -->
                        <td class="t" style="vertical-align:middle;"><input class="btn btn-sm btn-success" type="button" value="修改" onClick="locking(this)"> 
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
                        <label for="userName" class="col-sm-2 control-label">用户名</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="username" placeholder="用户名">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">联系方式</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="mobile" placeholder="电话">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">报名时间</label>
                        <div class="col-sm-10"> <span style="float:left;">
                          <input type="text" class="Wdate" id="apply_time" onClick="WdatePicker()" placeholder="报名时间">
                        </span></div>
                      </div>  

                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">成人人数</label>
                        <div class="col-sm-10">
                         <input type="text" class="form-control"  id="adult_num" placeholder="成人数目"></div>
                      </div>
                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">儿童人数</label>
                        <div class="col-sm-10">
                         <input type="text" class="form-control"  id="child_num" placeholder="儿童数目"></div>
                      </div>
                      <div class="form-group">
                        <label for="rptPwd" class="col-sm-2 control-label">报名活动</label>
                        <div class="col-sm-10" style="text-align:justify"><span float="left" >
                            <select name="activity_type" id="activity_type" onchange="link(options.selectedIndex,0,'content')" style="display:inline;width:40%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                                <option value="">— 活动类别 —</option>
                                <option value="share_meeting">五好分享会</option> 
                                <option value="carnival">五好嘉年华</option> 
                            </select> &nbsp;&nbsp;&nbsp;&nbsp;
                            <select name="activity_id" id="activity_id" style="display:inline;width:40%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                                <option value="">— 活动选择 —</option>
                            </select> 
                        </span></div>
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
                data: {"tr_id":tr_id,"table_name":"activity_apply"},
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
  var apply_time=$("#apply_time").val();
  var mobile=$("#mobile").val();
  var adult_num=$("#adult_num").val();
  var child_num=$("#child_num").val();
  var activity_type=document.getElementById("activity_type").value;
  var activity_id=document.getElementById("activity_id").value;
  //var user_open_id=$("#user_open_id").val();  
   if(username!=""&&apply_time!=""&&mobile!=""&&adult_num!=""&&child_num!=""&&activity_id!=""&&activity_type!=""){
    var length = mobile.length;  
    if(length == 11 && /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1})|)+\d{8})$/.test(mobile) ){  //判断电话号码是否合格
      if(/^\d+$/.test(adult_num)&&/^\d+$/.test(child_num)){  
        if(parseInt(adult_num)<100&&parseInt(child_num)<100){     
        $.ajax({
                url: "php/insert_edit_activity_apply.php",
                type: "post",
                data: {"change_type":"add","username":username,"apply_time":apply_time,"mobile":mobile,"adult_num":adult_num,
                      "child_num":child_num,"activity_id":activity_id,"activity_type":activity_type},
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
                    else if(data=="2")
                      alert("该用户不存在！");
                    else if(data=="3")
                      alert("没有该活动！");
                    else
                      alert("添加出错！"+data);                      
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
            });
      }else alert("报名人数超过上限！");
    }else alert("人数格式不正确！");
  }else alert("手机号格式不正确！");
}else alert("输入不能为空！");
});
//修改记录
$("#edit").click(function(){
  var username=$("#username").val();
  var apply_time=$("#apply_time").val();
  var mobile=$("#mobile").val();
  var adult_num=$("#adult_num").val();
  var child_num=$("#child_num").val();
  var activity_type=document.getElementById("activity_type").value;
  var activity_id=document.getElementById("activity_id").value;

   if(username!=""&&apply_time!=""&&mobile!=""&&adult_num!=""&&child_num!=""&&activity_id!=""&&activity_type!=""){
    var length = mobile.length;  
    if(length == 11 && /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1})|)+\d{8})$/.test(mobile) ){  //判断电话号码是否合格
      if(/^\d+$/.test(adult_num)&&/^\d+$/.test(child_num)){  
        if(parseInt(adult_num)<100&&parseInt(child_num)<100){     
        $.ajax({
                url: "php/insert_edit_activity_apply.php",
                type: "post",
                data: {"record_id":record_id,"change_type":"edit","username":username,"apply_time":apply_time,"mobile":mobile,"adult_num":adult_num,
                      "child_num":child_num,"activity_id":activity_id,"activity_type":activity_type},
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
                    else if(data=="2")
                      alert("该用户不存在！");
                    else if(data=="3")
                      alert("没有该活动！");
                    else
                      alert("修改出错！"+data);                      
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
            });
      }else alert("报名人数超过上限！");
    }else alert("人数格式不正确！");
  }else alert("手机号格式不正确！");
}else alert("输入不能为空！");
});
</script>
<script type="text/javascript" language="javascript">
//导出记录
    var idTmr;
    function  getExplorer() {//获取浏览器类型
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
function method1(tableid) {//导出记录
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
     var pageSize = 11;
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
        if(pageSize<numberRowsInTable){
            nextLink();
            lastLink();
          }else{
            nextText();
            lastText();
          }
     }
     hide();
</script>

</body>
</html>
