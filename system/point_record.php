<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>积分记录管理</title>
<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script>  
  var arr ;
  var arr_time;
  var record_id;
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
      $.ajax({//加载修改信息
                url: "php/enquiry_activity.php",
                type: "post",
                data: {"tr_id":tr_id,"table_name":"v_point_record"},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {
                  if(data!="读取信息失败!"){
                      arr=data.split("##");  
                      document.getElementById("username").value=arr[7];
                      document.getElementById("record").value=arr[4];
                      document.getElementById("time").value=arr[6];
                      
                      var radio = document.getElementsByName("change_type");
                      var num=0;
                      for(i=0;i<radio.length;i++){//判断记录类别
                          if(radio[i].value==arr[2]){
                            radio[i].checked = true;
                            num++;
                          }  
                      }
                      if(arr[2]=="share"){//记录类型为分享时显示文章列表
                        document.all.article_id.style.display='inline';
                        document.getElementById("article_id").value=arr[3];
                      }
                      else if(arr[2]=="other"){//记录类型为其他时显示文本框
                        document.all.note.style.display='inline';
                        document.getElementById("note").value=arr[3];
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
      document.getElementById("record").value="";
      document.getElementById("time").value="";
      document.getElementById("article_id").value="";
      $("#add").show();
      $("#edit").hide(); 
    }  
   }
//查询记录
   function search(){
    var key_words=$("#key_words").val();
    if(key_words=="") alert("请输入用户名！");
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
//关闭浮层，清空控件值
  function Lock_CheckForm(theForm){   
   document.all.ly.style.display='none';
   document.all.Layer2.style.display='none';
   document.all.article_id.style.display='none';
   document.all.note.style.display='none';
   document.getElementById("note").value="";
   var radio = document.getElementsByName("change_type");
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

<?php//页面加载时显示所有记录
error_reporting(0);
$mysql_info = json_decode(file_get_contents("json/mysql_info.json"));
  $con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
  mysql_select_db("fivekids", $con);
  $sql="select * from v_point_record order by id desc";
  $result = mysql_query($sql,$con);
  //$row=mysql_fetch_array($result);
  //$num_rows=mysql_num_rows($result);
  $sql_article="select id,title from share_article order by id desc";//查询所有文章id和标题
  $result_article = mysql_query($sql_article,$con);
  $num_rows=mysql_num_rows($result_article);
  while($row_article=mysql_fetch_array($result_article)){ 
    $article_name[]=$row_article["title"];
    $article_id[]=$row_article["id"];
  }
?>

<div id="ly" style="display: none;position: absolute;top: 0%;left: 0%;width: 100%;height: 100%;background-color: gray;z-index:2;-moz-opacity: 0.3;opacity:.30;filter: alpha(opacity=30);"> </div>
    <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="widget no-margin">
                  <div class="widget-header">
                    <div class="title">
                     积分信息
                    </div>
                  </div>
                  <div class="widget-body" >
                    <input style="margin-top:-10px;float:left" class="btn btn-sm btn-success" type="button" value="添加新纪录" onClick="locking('add')">
                    <div style="text-align:center;margin-top:-10px;">
                    <input type="text" id="key_words" placeholder="用户姓名" style="margin-top:0px;display:inline;width:20%;height:30px;padding:0px 12px;font-size:14px;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                    &nbsp;<input style="margin-top:-5px;" class="btn btn-sm btn-success" type="button" value="查询" onClick="search()">
                    </div>
                    <table class="table no-margin" style="table-layout:fixed;word-wrap:break-word;">
                      <thead>
                        <tr>
                          <th style="width:5%;text-align:center;">id</th>
                          <th style="width:10%;text-align:center;">用户名</th>
                          <th style="width:20%;text-align:center;">用户id</th>
                          <th style="width:5%;text-align:center;">类别</th>
                          <th style="width:15%;text-align:center;">备注</th>
                          <th style="width:10%;text-align:center;">积分改变</th>
                          <th style="width:15%;text-align:center;">提交时间</th>
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
                        <td style="vertical-align:middle;"><?php echo $row["username"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["user_open_id"];?></td>
                        <td style="vertical-align:middle;"><?php 
                        if($row["change_type"]=="sign_in") echo "签到";
                        else if($row["change_type"]=="share") echo "分享";
                        else if($row["change_type"]=="other") echo "其他";
                        else echo $row["change_type"];
                        ?></td>
                        <td style="vertical-align:middle;"><?php 
                        if($row["change_type"]=="share"){
                          $sql_title="select title from share_article where id='".$row["note"]."'";//根据文章id显示标题
                          $result_title = mysql_query($sql_title,$con);
                          while($row_title=mysql_fetch_array($result_title)){ 
                            echo $row_title["title"];
                          }
                        } else echo $row["note"];
                        ?></td>
                        <td style="vertical-align:middle;"><?php echo $row["record"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["time"];?></td>
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
                        <td style="vertical-align:middle;"><?php echo $row["user_open_id"];?></td>
                        <td style="vertical-align:middle;"><?php 
                        if($row["change_type"]=="sign_in") echo "签到";
                        else if($row["change_type"]=="share") echo "分享";
                        else if($row["change_type"]=="other") echo "其他";
                        else echo $row["change_type"];
                        ?></td>
                        <td style="vertical-align:middle;"><?php 
                        if($row["change_type"]=="share"){
                          $sql_title="select title from share_article where id='".$row["note"]."'";//根据文章id显示标题
                          $result_title = mysql_query($sql_title,$con);
                          while($row_title=mysql_fetch_array($result_title)){ 
                            echo $row_title["title"];
                          }
                        }else echo $row["note"];
                        ?></td>
                        <td style="vertical-align:middle;"><?php echo $row["record"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["time"];?></td>
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
                     积分信息
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
                        <label for="emailId" class="col-sm-2 control-label">用户活动</label>
                        <div class="col-sm-10"> <span style="float:left;">
                          <input type="radio" name="change_type" value="sign_in" onClick="document.all.article_id.style.display='none';document.all.note.style.display='none'">签到 &nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="change_type" value="share" onClick="document.all.note.style.display='none';document.all.article_id.style.display='inline'">分享 &nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="change_type" value="other" onClick="document.all.article_id.style.display='none';document.all.note.style.display='inline';document.all.note.placeholder='修改原因'">其他 &nbsp;&nbsp;&nbsp;&nbsp;
                          <!-- 文章列表 -->
                          <select name="article_id" id="article_id" onchange="link(options.selectedIndex)" style="display:none;width:200px;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;border:1px solid #ccc;border-radius:4px;">
                                <option value="">— 选择文章 —</option>
                                <?php for($i=0;$i<$num_rows;$i++) {?>
                                <option value=<?php echo $article_id[$i]; ?>><?php echo $article_name[$i]; ?></option>
                                <?php }?>
                          </select> 
                          <input type="text" name="note" id="note" style="display:none;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;border:1px solid #ccc;border-radius:4px;">
                        </span></div>
                      </div>
                      <div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">积分改变</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="record" placeholder="例如：+2" >
                        </div>
                      </div><div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">提交时间</label>
                        <div class="col-sm-10"><span style="float:left;">
                          <input type="text" class="Wdate" id="time" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" placeholder="时间">
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
                data: {"tr_id":tr_id,"table_name":"point_record"},
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
  var record=$("#record").val();
  var time=$("#time").val();
  var article_id=$("#article_id").val();
  var note=$("#note").val();
  //取选中标签
  var radio=document.getElementsByName("change_type");
  var radio_checked="";
  for(var i=0;i<radio.length;i++){
       if(radio[i].checked==true)
          radio_checked=radio[i].value;
  } 
    if(username!=""&&radio_checked!=""&&record!=""&&time!=""){
      if(radio_checked=="share"&&article_id=="") alert("请选择分享的文章！");
      else{
        $.ajax({//调用接口添加
                url: "php/insert_edit_point_record.php",
                type: "post",
                data: {"type":"add","username":username,"change_type":radio_checked,"article_id":article_id,"note":note,"record":record,"time":time},
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
                    }else if(data=="2"){
                      alert("用户不存在！请重新输入！");
                    }else alert("添加出错！"+data);                      
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
            });
      }  
  }else alert("输入不能为空！");
});
//修改记录
$("#edit").click(function(){
  var username=$("#username").val();
  var record=$("#record").val();
  var time=$("#time").val();
  var article_id=$("#article_id").val();
  var note=$("#note").val();
  //取选中标签
  var radio=document.getElementsByName("change_type");
  var radio_checked="";
  for(var i=0;i<radio.length;i++){
       if(radio[i].checked==true)
          radio_checked=radio[i].value;
  } 
    if(username!=""&&radio_checked!=""&&record!=""&&time!=""){
      if(radio_checked=="share"&&article_id=="") alert("请选择分享的文章！");
      else{
        $.ajax({//调用接口修改
                url: "php/insert_edit_point_record.php",
                type: "post",
                data: {"record_id":record_id,"type":"edit","username":username,"change_type":radio_checked,"article_id":article_id,"note":note,"record":record,"time":time},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                 success: function(data) {
                    if(data=="1") {
                      alert("修改成功！");
                      document.all.ly.style.display='none';
                      document.all.Layer2.style.display='none';
                      window.location.reload(true);
                    }else if(data=="2"){
                      alert("用户不存在！请重新输入！");
                    }else alert("修改出错！"+data);                      
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
            });
      }   
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

         nextLink();
         lastLink();
     }
     hide();
</script>
</body>
</html>
