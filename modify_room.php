<?php
//获取表单提交的数据

//防止从url直接访问本文件
if( $_SERVER['HTTP_REFERER'] == "" )
{
//echo "<p><center>未授权访问......(5s后返回主页)</p></center>";
//header("Location: home.php?");
//header("Refresh:5;url=home.php");
echo "nothing here.";
exit(0);
}

require_once "functions.inc.php";
$p_id = $_POST["rid"];
$p_room = $_POST["room"];
$p_type = $_POST["type"];
$p_equip = $_POST["equip"];
$p_people = $_POST['people'];
if ($p_people=="")
	$p_people="0";
//$p_detail = $_POST['detail'];
$p_detail = "";
sql_mysql_default_connect();  //connect mysql


$sql = "update mrbs_room set room_name='$p_room' ,type='$p_type',equipment='$p_equip',capacity='$p_people',description='$p_detail' where id=$p_id";

$result = mysql_query($sql);
if($result==1){
	header("Location: admin_room.php");
}
else{
	echo "<p><center>提交失败</p></center>";
	header("Refresh:2;url=admin_room.php");
}
?>