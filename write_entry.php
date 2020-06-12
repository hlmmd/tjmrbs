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
$p_unit = $_POST["unit"];
$p_name = $_POST["name1"];
$p_number = $_POST['number'];
$p_email = $_POST['email'];
$p_phone = $_POST['phone'];
$p_date = $_POST['date'];
$ss1=$_POST['select2'];
$p_start = substr($ss1,0,5);
$p_end =  substr($ss1,6,5);
$p_theme = $_POST['theme'];
//$p_detail = $_POST['detail'];
$p_detail = "";
$p_count = $_POST['count'];
$p_roomname = $_POST['select'];
$p_type = $_POST['type'];
if($p_type=="部门")
	$p_type=0;
else 
	$p_type=2;
//echo $p_type;
$p_roomid =0;

//get the room id
$admin= 0;
//管理员登陆后可以预约周末
session_start();
if(!isset($_SESSION['user']))
{
	if(date('w',strtotime($p_date))==6 ||date('w',strtotime($p_date))==0)
	{
		echo "<p><center>周末不开放预约……(2s后返回主页)</p></center>";
		header("Refresh:2;url=home.php");
		exit(0);
	}	
}
else 
	$admin = 1;


sql_mysql_default_connect();  //connect mysql


$sql = "SELECT id,room_name FROM mrbs_room   WHERE disabled = 0 ";

while($row=mysql_fetch_row($result)) 
{
	$room_nameid[$row[0]]=$row[1];
}


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
//echo $start_time."+".(strtotime(date("Y-m-d",time()) )+$min_forward_time);
if ( $admin==0 && ( $start_time  <(strtotime(date("Y-m-d",time()) )+$min_forward_time )))
{
echo "<p><center>该时间段预约已过期……(2s后返回主页)</p></center>";
header("Refresh:2;url=home.php");
exit(0);
}
if($admin==0 && ($start_time >(strtotime(   date("Y-m-d",time()) )+$max_forward_time+$eveningends*3600+$eveningends_minutes*60) ))
{
echo "<p><center>该时间段预约暂不开放……(2s后返回主页)</p></center>";
header("Refresh:2;url=home.php");
exit(0);
}

//检查由于网络状况不好而造成的重复提交
$sql = "select count(*) from mrbs_entry where name=
	'$p_name' and student_id='$p_number' and room_id='$p_roomid'  and tel='$p_phone' and email='$p_email' 
	and count='$p_count'  and theme='$p_theme'  and detail='$p_detail' and start_time='$start_time' and end_time= '$end_time';";

$result = mysql_query($sql);
$row=mysql_fetch_row($result);
if($row[0]>=1){
echo "<p><center>已提交，请等待审核……(2s后返回主页)</p></center>";
//header("Location: home.php?");
header("Refresh:2;url=home.php");
exit(0);
}


$sql = "select count(*) from mrbs_entry where  room_id=$p_roomid and  status!=2  and ( start_time >= $start_time and start_time < $end_time or end_time > $start_time and end_time <= $end_time  )" ;
//$sql="select count(*) from mrbs_entry where room_id=2 and status!=2 and ( start_time > 1492047000 and start_time <= 1492074000 or end_time >= 1492047000 and end_time <= 1492074000 )";

//echo $sql;
//exit(0);
$result = mysql_query($sql);
$row=mysql_fetch_row($result);
if($row[0]>=1){

echo "<p><center>会议室时间冲突，请检查日程表……(2s后返回)</p></center>";
//echo date("Y-m-d H:i",$start_time);
//echo date("Y-m-d H:i",$end_time);
//header("Location: home.php?");
header("Refresh:2;url=detail.php?room=$p_roomid&date=$start_time");
exit(0);
}


$time_now=time();
$sql = "INSERT into mrbs_entry (type, unit,name,student_id,room_id,tel,email,count,theme,start_time,end_time,timestamp) values ( 
	'$p_type','$p_unit','$p_name','$p_number','$p_roomid','$p_phone','$p_email','$p_count','$p_theme','$start_time','$end_time','$time_now');";

$result = mysql_query($sql);
if($result==1){
	//echo "<p><center>提交成功，请等待审核……(2s后返回)</p></center>";
header("Refresh:0;url=detail.php?room=$p_roomid&date=$start_time");
}
else{
	echo "<p><center>提交失败，请重试……(2s后返回)</p></center>";
header("Refresh:2;url=detail.php?room=$p_roomid&date=$start_time");
}
?>