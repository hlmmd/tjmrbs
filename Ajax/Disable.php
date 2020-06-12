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
    $disable_id = $_GET['disable_id'];
 //  echo "alert('$disable_id');";
    $sql="UPDATE mrbs_room SET disabled=1 WHERE id='$disable_id' ";
  $result= mysql_query($sql);
if(isset( $disable_id))
   echo "window.location.href='../admin_room.php' ";
else
  echo "nothing here."; 	
//header("Refresh:0;url=admin_grant.php");


?>