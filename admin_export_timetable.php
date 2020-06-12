<?php
//防止从url直接访问本文件
/*
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}
*/
require_once "functions.inc.php";

checkright();

print_admin("export_timetable");
?>
<link rel="stylesheet" href="/css/mybk.css" type="text/css"/>
<script type="text/javascript">

function gen2()
{
	document.getElementById('form2').action="export_timetable.php"; 

}

</script>

<div class="">
	<form  method="post" id="form2"> 日程表

  <label for="date_timetable">日期(一周中任意一天)
            <input type="date" value="<?php echo date("Y-m-d",time()) ;?>"  id="date_timetable" name="date_timetable"/>  
 <br>
 
 <input type="submit" name="sub2" value="导出" id="sub2"  onclick="gen2()"  /> 
</div>

<?php
output_trailer();
 ?>
