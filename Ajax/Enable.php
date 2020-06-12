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
    $enable_id = $_GET['enable_id'];
 //  echo "alert('$enable_id');";
    $sql="UPDATE mrbs_room SET disabled=0 WHERE id='$enable_id' ";
  $result= mysql_query($sql);
if(isset( $enable_id))
   echo "window.location.href='../admin_room.php' ";
else
  echo "nothing here."; 	
//header("Refresh:0;url=admin_grant.php");


?>