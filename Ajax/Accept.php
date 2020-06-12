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
    $accept_id = $_GET['accept_id'];
    $sql="UPDATE mrbs_entry SET status=1 WHERE id='$accept_id' ";
  $result= mysql_query($sql);
if(isset( $accept_id))
   echo "window.location.href='../admin_grant.php' ";
else
  echo "nothing here."; 	
//header("Refresh:0;url=admin_grant.php");

/*
    require_once "../functions.php";  
    include_once "../Sendmial/mail.class.php";  
    
    $accept_id = $_GET['accept_id'];
    $sql="UPDATE mrbs_entry SET status=1 WHERE id='$accept_id' ";
    $result= mysql_query($sql);
    
    //Send email to inform the user

    $sql="SELECT email, tel FROM mrbs_entry WHERE id='$accept_id'";
    $result=mysql_query($sql);

    $row=mysql_fetch_row($result);
    $email = $row[0];
    $tel = $row[1];
    $smtpserver = "smtp.qq.com";
    $smtpserverport = "465";
    $smtpusermail = "1121350675@qq.com";
    $smtpemailto = "1121350675@qq.com";
    $smtpuser = "1121350675@qq.com";
    $smtppass = "******";
    $mailsubject = "mrbs";
    $mailbody = "You have successfully booked the meeting room";
    $mailtype = "TXT";
    $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
    echo "alert('hello');";
    //$smtp->debug = false;
    //$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype); 

    //Refresh the page
    echo "window.location.href='../admin.php'; ";
*/
?>
