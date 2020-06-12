<?php
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "由于涉及网络安全问题，请不要通过修改url跳转网页，谢谢！";
exit(0);
}
require_once "functions.inc.php";

// Check the user is authorised for this page
//checkAuthorised();

// print the page header
$pg="booking";
print_header("booking");
if($g_date<(strtotime(date("Y-m-d",time()) )+$min_forward_time))
{
	$g_date=(strtotime(date("Y-m-d",time()) )+$min_forward_time);
	$g_date = $g_date + $morningstarts*3600+$morningstarts_minutes*60;
	$_SESSION['date'] = $g_date;
}


?>

<?php
$sql = "SELECT id,room_name FROM mrbs_room   WHERE disabled = 0 ";
$result = mysql_query($sql);
$room_number=mysql_num_rows($result);
if($room_number==0)
{
	echo "<center><h2><strong>会议室预约关闭</strong></h2></center>";
output_trailer();
  exit(0);
}
?>

	<div class="booking">
	<form  method="post" id="form"> 
		<h1><center>会议申请表</center></h1>
        	<label for="name">会议室:</label>
        <select name="select" id="select" >

<?php

while($row=mysql_fetch_row($result)) 
{
	if($g_room!=$row[0])
		echo "<option>$row[1]</option>";
	else
		echo "<option selected=\"true\">$row[1]</option>";
}

?>

    </select>
    <br>
        
        <label for="unit" >申请单位:</label>
		
           

  <select name="unit" id="unit" >
<option>团委</option>
<option>工会</option>
<option>组织部</option>
<option>宣传部</option>
<option>统战部</option>
<option>教师工作部</option>
<option>学生工作部</option>
<option>保卫部</option>
<option>武装部</option>
<option>研究生工作部</option>
<option>学生处</option>
<option>教务处</option>
<option>研究生院</option>
<option>文科办</option>
<option>人事处</option>
<option>财务处</option>
<option>保卫处</option>
<option>信息化办公室</option>
<option>材料科学与工程学院</option>
<option>测绘与地理信息学院</option>
<option>电子与信息工程学院</option>
<option>法学院</option>
<option>国际文化交流学院</option>
<option>海洋与地球科学学院</option>
<option>航空航天与力学学院</option>
<option>化学科学与工程学院</option>
<option>环境科学与工程学院</option>
<option>机械与能源工程学院</option>
<option>建筑与城市规划学院</option>
<option>交通运输工程学院</option>
<option>经济与管理学院</option>
<option>口腔医学院</option>
<option>马克思主义学院</option>
<option>汽车学院</option>
<option>人文学院</option>
<option>软件学院</option>
<option>上海国际设计创新学院</option>
<option>设计创意学院</option>
<option>生命科学与技术学院</option>
<option>数学科学学院</option>
<option>体育教学部</option>
<option>铁道与城市轨道交通研究院</option>
<option>土木工程学院</option>
<option>外国语学院</option>
<option>物理科学与工程学院</option>
<option>医学院</option>
<option>艺术与传媒学院</option>
<option>政治与国际关系学院</option>
<option>职业技术教育学院</option>
<option>中德工程学院</option>
<option>中德学院</option>
<option>其他</option>

    </select>

	<label for="type" >申请类型:</label>
<select name="type" id="type" >
<option>社团</option>
<option>部门</option>
  </select>
			<label for="name" >申请人:</label>
		
            	<input type="text" minlength="2" maxlength="20" name="name1" id="name1" value="" /> 
        <script language="javascript">
name1.onkeyup=function(){
var re=/[#%\'\\\/:\*\?"\<\>\|]/;//
if(re.test(this.value)){
alert("请勿输入非法字符");
this.value=this.value.substr(0,this.value.length-1);//将最后输入的字符去除
  }
}
</script>

            <label for="number" >学号/工号:</label>
			<input type="tel" minlength="2"  maxlength="10" name="number" id="number" value="" /> 
            
			<label for="email">邮箱:</label> 
			<input type="email" id="email" name="email" value=""  /> 
			<label for="phone" >手机号码:</label> 
			<input type="tel"  minlength="11" maxlength="11" id="phone" name="phone"  /> 
          
         <label for="date">日期:</label> 
            <input type="date" value="<?php echo date("Y-m-d",$g_date) ;?>"  id="date" name="date"/>  
            <br> 


   
        <label for="time">时间:</label>
        <select name="select2" id="select2" >

<?php

$str_arr=array("09:00~11:30","14:00~17:00","18:00~20:00");
	$hstr=date("H:i",$g_date);
for($i=0;$i<3;$i=$i+1)
{
	
	if($hstr[0]==$str_arr[$i][0] &&$hstr[1]==$str_arr[$i][1] )
		echo "<option selected=\"true\">$str_arr[$i]</option>";
	else
		echo "<option>$str_arr[$i]</option>";
}

?>

    </select>
        	   
        
      	<label for="theme" >活动内容:</label> 
			<input  type="text"  minlength="2" maxlength="20" id="theme"   name="theme" value=""  /> 


        <script language="javascript">
theme.onkeyup=function(){
var re=/[#%\'\\\/:\*\?"\<\>\|]/;//
if(re.test(this.value)){
alert("请勿输入非法字符");
this.value=this.value.substr(0,this.value.length-1);//将最后输入的字符去除
  }
}
</script>
      	<label for="count" >与会人数:</label> 
			<input  type="number"  min="2" max="1000" id="count"  name="count" value=""  /> 
			<br>
          
            <input type="submit" name="sub" value="提交申请" id="sub"  onclick="checkform()"  /> 
 	<label  > </label> 
<br>
<br>
  <p >附件：<a href="file/大学生活动中心场地使用申请表.doc">大学生活动中心场地使用申请表</a></p>

</div>

<script type="text/javascript">    
function checkform(){ 
	if(!confirm("确定要提交吗？提交后无法修改!")){  
	return false;   
	}   

if(document.getElementById('unit').value.length==0){    
        alert('单位输入为空！');
document.getElementById('form').action="#"; 
      //document.getElementById('unit').focus();
        return false;
    }
var str = document.getElementById('unit').value;
var re=/[#()%\'\\\/:\*\?"\<\>\|]/;//
if(re.test(str)){
alert("请勿输入非法字符");
return false;
}

      if(document.getElementById('name1').value.length==0){    
        alert('申请人输入为空！');
document.getElementById('form').action="#"; 
      //document.getElementById('name1').focus();
        return false;
    }

 str = document.getElementById('name1').value;

if(re.test(str)){
alert("请勿输入非法字符");
return false;
}

    if(document.getElementById('number').value.length==0){    
        alert('学号/工号输入为空！');
document.getElementById('form').action="#"; 
// document.getElementById('number').focus();
        return false;
    }
	var reg = new RegExp("^[0-9]*$"); 
    if(!reg.test(document.getElementById('number').value)){  
        alert("学号请输入数字!");  
document.getElementById('form').action="#"; 
// document.getElementById('number').focus();
        return false;
    }

    if(document.getElementById('email').value.length==0){    
        alert('邮箱输入为空！');
document.getElementById('form').action="#"; 
     //   document.getElementById('email').focus();
        return false;
    }

    if(document.getElementById('phone').value.length==0){    
        alert('联系方式输入为空！');
document.getElementById('form').action="#"; 
    //    document.getElementById('phone').focus();
        return false;
    }

	  if(!reg.test(document.getElementById('phone').value)){  
        alert("联系方式请输入数字!");  
document.getElementById('form').action="#"; 
// document.getElementById('number').focus();
        return false;
    }

    if(document.getElementById('date').value.length==0){    
        alert('日期输入为空！');
document.getElementById('form').action="#"; 
  //      document.getElementById('date').focus();
        return false;
    }
var   reg   =   /^(\d{4})-(\d{2})-(\d{2})$/;  
      var   str   =  document.getElementById('date').value;
      var   arr   =   reg.exec(str);  
      if   (str!="")  {
      if   (!reg.test(str)&&RegExp.$2<=12&&RegExp.$3<=31){  
        alert("请输入的日期格式为yyyy-mm-dd或正确的日期!");  
        return   false;  
        }  
   }

    if(document.getElementById('theme').value.length==0){    
        alert('主题输入为空！');
document.getElementById('form').action="#"; 
  //      document.getElementById('theme').focus();
        return false;
    }
str = document.getElementById('theme').value;
 re=/[#()%\'\\\/:\*\?"\<\>\|]/;//
if(re.test(str)){
alert("请勿输入非法字符");
return false;
}
    if(document.getElementById('count').value.length==0){    
        alert('与会人数输入为空！');
document.getElementById('form').action="#"; 
   //     document.getElementById('count').focus();
        return false;
    }

	document.getElementById('form').action="write_entry.php"; 
	
}


</script>

<?php

output_trailer();

?>
