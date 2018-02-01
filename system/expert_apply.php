<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>行家审核</title>
<script language="javascript" type="text/javascript" src="My97DatePicker/WdatePicker.js"></script>
<script>  
  var arr ;
  var arr_time;
  var record_id;
  function locking(button_type){
      //浮层锁定
      document.all.ly.style.display="block";   
      document.all.ly.style.width=document.body.clientWidth;   
      document.all.ly.style.height=document.body.clientHeight;   
      document.all.Layer2.style.display='block'; 
    
      //查看详情按钮
      var row = button_type.parentNode.parentNode; 
      var index = row.rowIndex; 
      var tr_id = $(".table tbody tr").eq(parseInt(index)-1).children("td").eq(0).html();
      record_id=tr_id;
      $.ajax({
                url: "php/enquiry_activity.php",
                type: "post",
                data: {"tr_id":tr_id,"table_name":"expert_apply"},
                dataType: "text",
                //jsonp: "callbackJsonp", // 传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名
                //timeout:10000,
                success: function(data) {
                  if(data!="读取信息失败!"){
                      arr=data.split("##");   
                      //复制信息到文本框内                   
                      document.getElementById("realname").value=arr[1];  
                      document.getElementById("mobile").value=arr[2];
                      document.getElementById("email").value=arr[3];
                      document.getElementById("occupation").value=arr[4];
                      document.getElementById("company").value=arr[5];
                      document.getElementById("introduction").value=arr[6];
                      document.getElementById("photo_url").value=arr[7];
                      document.getElementById("sponsor_name").value=arr[8];
                      document.getElementById("sponsor_mobile").value=arr[9]; 
                      document.getElementById("user_open_id").value=arr[11]; 
                      //设置文本框不可编辑
                      document.getElementById("realname").readOnly=true;  
                      document.getElementById("mobile").readOnly=true;
                      document.getElementById("email").readOnly=true;
                      document.getElementById("occupation").readOnly=true;
                      document.getElementById("company").readOnly=true;
                      document.getElementById("introduction").readOnly=true;
                      document.getElementById("photo_url").readOnly=true;
                      document.getElementById("sponsor_name").readOnly=true;
                      document.getElementById("sponsor_mobile").readOnly=true;
                      document.getElementById("user_open_id").readOnly=true;                   
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
//关闭浮层
  function Lock_CheckForm(theForm){   
   document.all.ly.style.display='none';
   document.all.Layer2.style.display='none';
   return false;   
   } 
 
</script>
<style type="text/css">
.STYLE1 {/*关闭按钮样式*/
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
</head>
<body>
<?php//页面加载时查询所有记录
  error_reporting(0);
  $mysql_info = json_decode(file_get_contents("json/mysql_info.json"));
  $con = mysql_connect($mysql_info->ip,$mysql_info->username,$mysql_info->password);//本地
  mysql_select_db("fivekids", $con);
  $sql="select * from expert_apply order by id desc";
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
                     行家申请记录
                    </div>
                  </div>
                  <div class="widget-body">
                    <div style="text-align:center;margin-top:-10px;">
                    <!-- 查询记录 -->
                    <input type="text" id="key_words" placeholder="用户姓名" style="margin-top:0px;display:inline;width:20%;height:30px;padding:0px 12px;font-size:14px;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);">
                    &nbsp;<input style="margin-top:-5px;" class="btn btn-sm btn-success" type="button" value="查询" onClick="search()">
                    </div>
                    <table class="table no-margin" style="table-layout:fixed;word-wrap:break-word;">
                      <thead>
                        <tr>
                          <th style="width:5%;text-align:center;">id</th>
                          <th style="width:5%;text-align:center;">姓名</th>
                          <th style="width:10%;text-align:center;">联系方式</th>
            						  <th style="width:10%;text-align:center;">电子邮箱</th>
                          <th style="width:10%;text-align:center;">从事职业</th>
                          <th style="width:10%;text-align:center;">所在单位</th>
            						  <th style="width:10%;text-align:center;">个人介绍</th>
            						  <th style="width:10%;text-align:center;">审核图片</th>
            						  <th style="width:7%;text-align:center;">推荐人</th>
            						  <th style="width:10%;text-align:center;">推荐人电话</th>
                          <th style="width:5%;text-align:center;">状态</th>
                          <th style="width:10%;text-align:center;">行家id</th>
            						  <th style="width:8%;text-align:center;">操作</th>
                        </tr>
                      </thead>
                      <tbody id="table2">
                        <?php 
                        $i=0;
                        while($row=mysql_fetch_assoc($result)){
                        if($i==0){$i++;//记录颜色交替显示
                        ?>
                        <tr align="center" class="success">
                        <td style="vertical-align:middle;"><?php echo $row["id"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["realname"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["mobile"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["email"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["occupation"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["company"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["introduction"];?></td>
                        <td style="vertical-align:middle;" height="110px"><img width="100%" height="100%" src='<?php echo $row["photo_url"];?>'></td>
                        <td style="vertical-align:middle;"><?php echo $row["sponsor_name"];?></td>                        
                        <td style="vertical-align:middle;"><?php echo $row["sponsor_mobile"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["status"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["user_open_id"];?></td>
                        <?php if($row["status"]=="未审核"){ ?>
                        <td style="vertical-align:middle;"><input class="btn btn-sm btn-success" type="button" value="查看详情" onClick="locking(this)"></td>
                        <?php }else{?><td></td><?php }?>
                        </tr>
                        <?php 
                        }elseif ($i==1) {$i--;?><!-- 记录颜色交替显示 -->
                        <tr align="center">
                        <td style="vertical-align:middle;"><?php echo $row["id"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["realname"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["mobile"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["email"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["occupation"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["company"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["introduction"];?></td>
                        <td style="vertical-align:middle;" height="110px"><img width="100%" height="100%" src='<?php echo $row["photo_url"];?>'></td>
                        <td style="vertical-align:middle;"><?php echo $row["sponsor_name"];?></td>                        
                        <td style="vertical-align:middle;"><?php echo $row["sponsor_mobile"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["status"];?></td>
                        <td style="vertical-align:middle;"><?php echo $row["user_open_id"];?></td>
                        <td style="vertical-align:middle;"><input class="btn btn-sm btn-success" type="button" value="查看详情" onClick="locking(this)">  </td>
                        <?php }?>
                        </tr>
                        <?php }?>
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
                      记录详情
                    </div>
               <a href=JavaScript:; class="STYLE1" onclick="Lock_CheckForm(this);">[关闭]</a>     
                  </div>
                  <div class="widget-body">
                    <form class="form-horizontal no-margin" >
                      <div class="form-group">
                        <label for="userName" class="col-sm-2 control-label">行家姓名</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="realname" placeholder="姓名">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="emailId" class="col-sm-2 control-label">联系方式</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="mobile" placeholder="电话">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">电子邮件</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="email" placeholder="邮件">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">从事职业</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="occupation" placeholder="职业">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">所在单位</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="company" placeholder="单位">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="rptPwd" class="col-sm-2 control-label">个人介绍</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="introduction"  placeholder="简介">
                        </div>
                      </div>
					             <div class="form-group">
                        <label for="pwd" class="col-sm-2 control-label">图片链接</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="photo_url" placeholder="链接">
                        </div>
                      </div>
					             <div class="form-group">
                        <label for="rptPwd" class="col-sm-2 control-label">推荐人姓名</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="sponsor_name" placeholder="姓名">
                        </div>
                      </div>
					           <div class="form-group">
                        <label for="rptPwd" class="col-sm-2 control-label">推荐人电话</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="sponsor_mobile"  placeholder="电话">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="rptPwd" class="col-sm-2 control-label">行家id</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="user_open_id"  placeholder="id">
                        </div>
                      </div>					  
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
						                <input id="pass_button" type="button" class="btn btn-info" value="通过审核">&nbsp;&nbsp;&nbsp;
                            <input id="refuse_button" type="button" class="btn btn-info" value="拒绝请求">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
    </div>
<!--浮层框架结束--> 	
    
<script src="./js/jQuery.min.2.1.1.js"></script>			  
<script>
//通过审核
$("#pass_button").click(function(){
    var realname=$("#realname").val();
    var mobile=$("#mobile").val();
    var email=$("#email").val();
    var occupation=$("#occupation").val();
    var company=$("#company").val();
    var introduction=$("#introduction").val();
    var photo_url=$("#photo_url").val();
    var sponsor_name=$("#sponsor_name").val();
    var sponsor_mobile=$("#sponsor_mobile").val();
    var user_open_id=$("#user_open_id").val();
    if(confirm("通过审核后不能取消！是否确认通过？")){
            $.ajax({
                url: "php/expert_check.php",
                type: "post",
                data: {"record_id":record_id,"decision":"pass","realname":realname,"mobile":mobile,"email":email,
                "occupation":occupation,"company":company,"introduction":introduction,"photo_url":photo_url,"sponsor_name":sponsor_name,"sponsor_mobile":sponsor_mobile,"user_open_id":user_open_id},
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
                      alert("该行家已存在，无法通过审核！");                    
                    else 
                      alert("修改出错！"+data);                      
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
            }); 
  }
});

//拒绝请求
$("#refuse_button").click(function(){
    if(confirm("拒绝后不能取消！是否确认拒绝？")){
            $.ajax({
                url: "php/expert_check.php",
                type: "post",
                data: {"record_id":record_id,"decision":"refuse"},
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
  }
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
</body>
</html>
