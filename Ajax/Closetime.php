<?php
//防止从url直接访问本文件
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}
  require_once "../functions.inc.php";  
  checkright_ajax();
  sql_mysql_default_connect();
  $room_id = $_GET['room_id'];
  $starttime  =  $_GET['starttime'];
  $endtime = $_GET['endtime'];
  //echo "alert('$starttime');";
  //$sql = "INSERT into mrbs_entry (room_id,status,start_time,end_time,unit,name,student_id,tel,email,count,theme,timestamp) values ('1','100','1526450400','1526461200' ,'a','a',0,'a','a',0,'a',0);";
  //$sql = "INSERT into mrbs_entry (room_id,status,start_time,end_time, unit,name,student_id,room_id,tel,email,count,theme,) values ('$1','100','$starttime','$endtime');";
  $sql = "INSERT into mrbs_entry (room_id,status,start_time,end_time) values ('$room_id','100','$starttime','$endtime');";
  //echo "alert('$sql');";

  $result= mysql_query($sql);

  if(isset( $room_id))
  {
    $herf = "../admin_settime.php?room=".$room_id."&date=".$starttime;
    echo "window.location.href='$herf' ";
  }
    
  else
    echo "nothing here."; 	

//   echo "alert('$room_id'.'$starttime'.'$endtime');";
//    $sql="UPDATE mrbs_room SET disabled=0 WHERE id='$enable_id' ";
   
// if(isset( $enable_id))
//    echo "window.location.href='../admin_room.php' ";
// else
//   echo "nothing here."; 	
//header("Refresh:0;url=admin_grant.php");


?>