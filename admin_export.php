<?php
//防止从url直接访问本文件
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}
require_once "functions.inc.php";

checkright();

print_admin("export");
?>
<link rel="stylesheet" href="/css/mybk.css" type="text/css"/>
<script type="text/javascript">

function chooseall()
{
	document.getElementById('room').checked = true;
	document.getElementById('name1').checked = true;
	document.getElementById('number').checked = true;
	document.getElementById('unit').checked = true;
	document.getElementById('tel').checked = true;
	document.getElementById('mail').checked = true;
	document.getElementById('money').checked = true;
	document.getElementById('theme').checked = true;
	document.getElementById('people').checked = true;
}

function choosenone()
{
	document.getElementById('room').checked= false;
	document.getElementById('name1').checked = false;
	document.getElementById('number').checked =false;
	document.getElementById('unit').checked =false;
	document.getElementById('tel').checked =false;
	document.getElementById('mail').checked = false;
	document.getElementById('money').checked = false;
	document.getElementById('theme').checked = false;
	document.getElementById('people').checked =false;
}

function gen()
{
/*
	var sql="select ";
	var type ="1";
	if(document.getElementById('radio1').checked==true)
		type ="0";

	if(document.getElementById('room').checked==true)
		sql+="room_id,";
	
	if(document.getElementById('name').checked == true)
		sql+="name,";
	if( document.getElementById('number').checked == true)
		sql+="student_id,";
	if( document.getElementById('unit').checked == true)
		sql+="unit,";
	if( document.getElementById('tel').checked == true)
		sql+="tel,";
	if( document.getElementById('mail').checked == true)
		sql+="mail,";
	if( document.getElementById('money').checked == true)
		sql+="money,";
	if( document.getElementById('theme').checked == true)
		sql+="theme,";
	if( document.getElementById('people').checked == true)
		sql+="count,";
	sql+="start_time,end_time,status,timestamp from mrbs_entry where  type = "+type+";";

	alert(sql);
*/
	document.getElementById('form').action="export_file.php"; 

}

function gen2()
{

	document.getElementById('form2').action="export_timetable.php"; 

}

</script>
<div class="">
	<form  method="post" id="form"> 详细信息


<label for="type" >使用方
<input id="radio1" type="radio" checked="checked" value="student" name="radio1" />学生
<input id="radio2" type="radio"  name="radio1" value="company"  />企业

  <label for="date1">开始日期
            <input type="date" value="<?php echo date("Y-m-d",time()) ;?>"  id="date1" name="date1"/>  
  <label for="date2">结束日期
            <input type="date" value="<?php echo date("Y-m-d",time()) ;?>"  id="date2" name="date2"/> 

<br>
 
 <input type="submit" name="sub" value="导出" id="sub"  onclick="gen()"  /> 
</div>



<?php
output_trailer();
 ?>
