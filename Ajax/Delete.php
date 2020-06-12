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
    $delete_id = $_GET['delete_id'];
//echo "alert('$delete_id');";

    $sql="Delete from mrbs_entry WHERE id='$delete_id' ";

//echo  "alert('$sql');";

   $result= mysql_query($sql);
if(isset( $delete_id))
   echo "window.location.href='../admin_grant.php' ";
else
  echo "nothing here."; 
//header("url=admin_grant.php");

/*
    require_once "../functions.php";    
    
    //Change the status
    $reject_id = $_GET['reject_id'];
    $sql="UPDATE mrbs_entry SET status=2 WHERE id='$reject_id' ";
    $result= mysql_query($sql);
    

    $sql="SELECT email, tel FROM mrbs_entry WHERE id='$reject_id'";
    $result=mysql_query($sql);

    $row=mysql_fetch_row($result);
    $email = $rwo[0];
    $tel = $row[1];

    //Refresh the page
    echo "window.location.href='../admin.php' ";
*/
?>