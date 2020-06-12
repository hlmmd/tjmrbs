<?php
//防止从url直接访问本文件
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}
  session_start();
  if(isset($_SESSION['user']))
  {
    echo "window.location.href='../admin_table.php';";
  }
?>
