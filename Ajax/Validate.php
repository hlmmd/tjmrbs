<?php

//防止从url直接访问本文件
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}
 require_once "../functions.inc.php";
  sql_mysql_default_connect();
  $name = $_GET['name'];
  $password = $_GET['password'];
  $password = md5($password);
//    echo "alert('$password');";
  $query = "select name from mrbs_users where name='$name' and password='$password'";
  $result = mysql_query( $query);
  $num=mysql_num_rows($result);

  if($num==0)
  {
    echo "document.getElementById('Validate').innerHTML='<font color=red>用户名或者密码错误.</font>';";
  }
  else {
    $row=mysql_fetch_row($result);
    $user=$row[0];
    session_start();
    $_SESSION['user']=$user;
//	echo "alert('$user');";
//    session_destroy();
    echo "window.location.href='../admin_table.php';";
  }
  mysql_close();

?>
