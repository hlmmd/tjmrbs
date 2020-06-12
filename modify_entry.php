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
$p_unit = $_POST["unit"];
$p_money = $_POST["money"];
$p_name = $_POST["name"];
$p_number = $_POST['number'];
$p_date = $_POST['date'];
$p_start = $_POST['start'];
$p_end = $_POST['end'];
$p_roomname = $_POST['select'];
echo $p_name;

sql_mysql_default_connect();  //connect mysql

$p_roomid =0;
$sql = "SELECT id,room_name FROM mrbs_room   WHERE disabled = 0 ";
$result = mysql_query($sql);
while($row=mysql_fetch_row($result)) 
{
	if( strcmp( $p_roomname ,$row[1])==0)
	{
		$p_roomid=$row[0];
		break;
	}
}

//get the starttime and endtime

$start_time = strtotime($p_date." ".$p_start); 
$end_time = strtotime($p_date." ".$p_end); 

if ($start_time  < time())
{
echo "<p><center>该时间段预约已过期……(5s后返回)</p></center>";
header("Refresh:5;url=admin_modify.php");
exit(0);
}

$sql = "select count(*) from mrbs_entry where  id!=$p_id and room_id=$p_roomid and  status!=2  and ( start_time >= $start_time and start_time < $end_time or end_time > $start_time and end_time <= $end_time  )" ;

//$sql="select count(*) from mrbs_entry where room_id=2 and status!=2 and ( start_time > 1492047000 and start_time <= 1492074000 or end_time >= 1492047000 and end_time <= 1492074000 )";
//echo $sql;
//exit(0);
$result = mysql_query($sql);
$row=mysql_fetch_row($result);
if($row[0]>=1){
echo "<p><center>会议室时间冲突，请检查日程表……(2s后返回)</p></center>";
header("Refresh:2;url=admin_table.php?room=$p_roomid&date=$start_time");
exit(0);
}

$sql = "update mrbs_entry set room_id='$p_roomid' ,unit='$p_unit',money='$p_money',start_time='$start_time',end_time='$end_time' where id=$p_id";

$result = mysql_query($sql);
if($result==1)
	header("Location: admin_modify.php");
else{
	echo "<p><center>提交失败</p></center>";
header("Refresh:2;url=admin_modify.php");
}
?>