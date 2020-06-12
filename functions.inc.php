<?php
$vocab["tj_name"]               = "经纬楼会议室预定";
$vocab["tj_home"]               = "首页";
$vocab["tj_detail"]               = "会议日程";
$vocab["tj_booking"]               = "会议预定";
$vocab["tj_qa"]               = "流程说明";
$vocab["tj_admin"]               = "管理员登录";

$num_of_days         =    7; //days per week;
$num_of_days_admin         =    7; //days per week;
$morningstarts         = 7;   // must be integer in range 0-23
$morningstarts_minutes = 0;   // must be integer in range 0-59

// The beginning of the last slot of the day (DEFAULT VALUES FOR NEW AREAS)
$eveningends           = 22;  // must be integer in range 0-23
$eveningends_minutes   = 00;   // must be integer in range 0-59

$min_forward_time  = 3600*24*7;//86400 ;//3600*24;   // 最小提前预定时间
$max_forward_time   = 3600*24*30 ;//3600*24*15;   //最大提前预定时间

function get_vocab($tag)
{
    global $vocab;
    // Return the tag itself if we can't find a vocab string
    return (isset($vocab[$tag])) ? $vocab[$tag] : $tag;
}

function sql_mysql_default_connect()
{
    global $sql_mysql_conn ;
date_default_timezone_set('Asia/Shanghai');


	$err_level = error_reporting(0);  
	$sql_mysql_conn = mysql_connect( "localhost","", "");//登录数据库
	
//	$conn = mysql_connect('params');  
	error_reporting($err_level); 

	mysql_select_db("tj91",$sql_mysql_conn);//选择数据库
	mysql_set_charset('utf8', $sql_mysql_conn); //设置字符集

	
	$db_username = "";
	$db_password = "";


	$room_nameid=array();

	$sql = "SELECT id,room_name FROM mrbs_room    ";
	$result = mysql_query($sql);
	while($row=mysql_fetch_row($result)) 
	{
		$room_nameid[$row[0]]=$row[1];
	}

	$meeting_room_prefix = "四平校区-经纬楼";


	try{

		$conn = new PDO("oci:dbname=//ip:port/dbname;charset=UTF8",$db_username,$db_password);
	 //   $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);

		//	SELECT RQ,SDMC,DWMC,CDMC  from T_JY_XJH_XXtoXS  where CDMC like 'Ëƽ
		//echo 'SELECT RQ,SDMC,DWMC,CDMC  from T_JY_XJH_XXtoXS  where CDMC like \'四平校区-经纬楼%\' ';
	  
	$sth = $conn->prepare('SELECT RQ,SDMC,DWMC,CDMC  from T_JY_XJH_XXtoXS  where CDMC like \'四平校区-经纬楼%\' ');
	// $sth = $conn->prepare("SELECT * from T_JY_XJH_XX ");
	$sth->execute();

		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

	foreach($result as $key=>$value)
	{
	//	echo "<br>".$key." ";
		
		$str_t = explode("-",$value['SDMC']);
		$p_start =$str_t[0];
		$p_end =$str_t[1];
		if ( $p_start=='' || $p_end=='')
			continue;
		$p_start_time = strtotime($value['RQ']." ".$p_start); 
		$p_end_time = strtotime($value['RQ']." ".$p_end);

		$p_unit = $value['DWMC'];

		$start = strlen($meeting_room_prefix);
		$roomname = substr($value['CDMC'],$start);
		if( !in_array( $roomname,$room_nameid)){
			continue;
		}
		else
			$p_room_id = array_keys($room_nameid,$roomname)[0];
		
		
		//检查记录是否已经被插入过
		$sql = "select count(*) from mrbs_entry where  room_id=$p_room_id  and status!=2  and ( start_time >= $p_start_time and start_time < $p_end_time or end_time > $p_start_time and end_time <= $p_end_time  )" ;

		$result = mysql_query($sql);
		$row=mysql_fetch_row($result);
		if(isset($row) && $row[0]>=1){
			$conflict_num++;
			continue;
		}
		
		$today_time = strtotime(date("Y-m-d",time())) ;
		$sql = "INSERT into mrbs_entry (type,unit,room_id,start_time,end_time,timestamp) values ( 
		'1','$p_unit','$p_room_id','$p_start_time','$p_end_time',    '$today_time'  );";
		$result = mysql_query($sql);
	}

	}catch(PDOException $e){
	//	echo ($e->getMessage());
	}

}


function renew_company()
{
	sql_mysql_default_connect();
	
	$db_username = "usr_jyfw_website";
	$db_password = "ktDKUZdl3SE";


	$room_nameid=array();

	$sql = "SELECT id,room_name FROM mrbs_room    ";
	$result = mysql_query($sql);
	while($row=mysql_fetch_row($result)) 
	{
		$room_nameid[$row[0]]=$row[1];
	}

	$meeting_room_prefix = "四平校区-经纬楼";


	try{

		$conn = new PDO("oci:dbname=//192.168.132.35:1521/tjdb;charset=UTF8",$db_username,$db_password);
	 //   $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);

		//	SELECT RQ,SDMC,DWMC,CDMC  from T_JY_XJH_XXtoXS  where CDMC like 'Ëƽ
		//echo 'SELECT RQ,SDMC,DWMC,CDMC  from T_JY_XJH_XXtoXS  where CDMC like \'四平校区-经纬楼%\' ';
	  
	$sth = $conn->prepare('SELECT RQ,SDMC,DWMC,CDMC  from T_JY_XJH_XXtoXS  where CDMC like \'四平校区-经纬楼%\' ');
	// $sth = $conn->prepare("SELECT * from T_JY_XJH_XX ");
	$sth->execute();

		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

	foreach($result as $key=>$value)
	{
	//	echo "<br>".$key." ";
		
		$str_t = explode("-",$value['SDMC']);
		$p_start =$str_t[0];
		$p_end =$str_t[1];
		if ( $p_start=='' || $p_end=='')
			continue;
		$p_start_time = strtotime($value['RQ']." ".$p_start); 
		$p_end_time = strtotime($value['RQ']." ".$p_end);

		$p_unit = $value['DWMC'];

		$start = strlen($meeting_room_prefix);
		$roomname = substr($value['CDMC'],$start);
		if( !in_array( $roomname,$room_nameid)){
			continue;
		}
		else
			$p_room_id = array_keys($room_nameid,$roomname)[0];
		
		
		//检查记录是否已经被插入过
		$sql = "select count(*) from mrbs_entry where  room_id=$p_room_id  and status!=2  and ( start_time >= $p_start_time and start_time < $p_end_time or end_time > $p_start_time and end_time <= $p_end_time  )" ;

		$result = mysql_query($sql);
		$row=mysql_fetch_row($result);
		if(isset($row) && $row[0]>=1){
			$conflict_num++;
			continue;
		}
		
		$today_time = strtotime(date("Y-m-d",time())) ;
		$sql = "INSERT into mrbs_entry (type,unit,room_id,start_time,end_time,timestamp) values ( 
		'1','$p_unit','$p_room_id','$p_start_time','$p_end_time',    '$today_time'  );";
		$result = mysql_query($sql);
	}

	}catch(PDOException $e){
	//	echo ($e->getMessage());
	}
}
function checkright()
{
	session_start();
	if(!isset($_SESSION['user']))
	{
		echo "please login.";
		header("Refresh:5;url=login.php");
		exit(0);
	}
}

function checkright_ajax()
{
	session_start();
	if(!isset($_SESSION['user']))
	{
		echo "window.location.href='../login.php';";
		exit(0);
	}
}
function my_php_alert($str)
{
	echo "<script language=\"JavaScript\">alert(\"".$str."\");";
//	echo " history.back();";
	echo "</script>";
}

// Print the page header
function print_header($page)
{
 global $sql_mysql_conn,$eveningends,$eveningends_minutes;
	global $g_room,$g_date,$min_forward_time,$max_forward_time,$morningstarts,$morningstarts_minutes;
 if(isset($_SESSION['room'])) {
	$g_room = $_SESSION['room'];
}
else{
	$_SESSION['room'] = 1;
	$g_room = 1;
}

if (isset($_SESSION['date'])){
	$g_date = $_SESSION['date'];
}
else{
	$g_date=time();
	$g_date = $g_date - $g_date%1800+1800 +$min_forward_time  ;

	  if((date('w') == 6))
		$g_date = $g_date +3600*24*2  ;
	 else if((date('w') == 0))
		$g_date = $g_date +3600*24  ;
	$_SESSION['date'] = $g_date;
}

$str= $_SERVER["QUERY_STRING"];
if(!empty($str)){
	 parse_str($str, $output);
	if( isset($output['room'] )&& !empty($output['room']))
		$g_room=$output['room'];
	if( isset($output['date'])&& !empty($output['date']))
		$g_date=$output['date'];

}
//echo date("Y-m-d H:i",time());
//echo date("Y-m-d H:i",$g_date);
//echo date("Y-m-d H:i",(strtotime(date("Y-m-d",time()) )+$min_forward_time));

if($g_date<strtotime(date("Y-m-d",time()) ))
{
	$g_date=strtotime(date("Y-m-d",time()) );
	$g_date = $g_date + $morningstarts*3600+$morningstarts_minutes*60;
	$_SESSION['date'] = $g_date;
}

if($g_date>(strtotime(   date("Y-m-d",time()) )+$max_forward_time+$eveningends*3600+$eveningends_minutes*60))
{
	$g_date=strtotime( date("Y-m-d",time()) )+$max_forward_time;
	$g_date = $g_date + $morningstarts*3600+$morningstarts_minutes*60;
	$_SESSION['date'] = $g_date;
}

//echo date("Y-m-d h:i",time());

    if (!isset($sql_mysql_conn))
        sql_mysql_default_connect();
//<link rel="stylesheet" href="/CSS/mybk.css" type="text/css"/>
//
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <title><?php echo get_vocab("tj_name") ?></title>



<link rel="stylesheet" href="/css/mybk.css" type="text/css"/>
<link rel="stylesheet" href="/css/mytable.css" type="text/css"/>
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<style>

</style>
</head>
<body>

<div id="header-row">
<div class="container">
  <div class="row">

<?php
//		  <div  style="width:1000px;height:80px;margin:0px auto;margin-bottom:5px;"><a  href="home.php"><img src="img/logo1.jpg"/></a></div>
?>
  <div class="span9">
			<div class="navbar  pull-left" style="margin-top:40px ;margin-left:350px;">
			  <div class="navbar-inner">
				<a data-target=".navbar-responsive-collapse" data-toggle="collapse" class="btn btn-navbar"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a>
				<div class="nav-collapse collapse navbar-responsive-collapse">
				<ul class="nav">

  <?php
        $query_str="room=$g_room&date=$g_date";

        $cur1 = ($page=="home")?"class=\"active\"":"";
       echo "<li ". $cur1."> <a href=\"home.php\">" . get_vocab("tj_home") . "</a></li>\n";

        $cur1 = ($page=="detail")?"class=\"active\"":"";
        echo "<li ". $cur1."> <a href=\"detail.php?$query_str\">" . get_vocab("tj_detail") . "</a></li>\n";


        $cur1 = ($page=="booking")?"class=\"active\"":"";
        echo "<li ". $cur1."> <a href=\"booking.php?$query_str\">" . get_vocab("tj_booking") . "</a></li>\n";

       $cur1 = ($page=="qa")?"class=\"active\"":"";
        echo "<li ". $cur1."> <a href=\"qa.php?$query_str\">" . get_vocab("tj_qa") . "</a></li>\n";

        $cur1 = ($page=="admin")?"class=\"active\"":"";
      //  echo "<li ". $cur1."> <a href=\"login.html\">" . get_vocab("tj_admin") . "</a></li>\n";
  echo "<li ". $cur1."> <a href=\"login.php\">" . get_vocab("tj_admin") . "</a></li>\n";
        ?>


				</ul>
			  </div>

			  </div>
			</div>
		  </div>

 </div>
</div>
</div>




<?php

} // end of print_header()


function output_trailer()
{
?>

<footer>
<div class="container">
  <div class="row">
	<div style="margin-left:300px ;color:black;">©同济大学学生就业指导中心&nbsp&nbsp&nbsp&nbsp联系电话: 65981173</div>

  </div>
</div>
</div>
</footer>

</body>
</html>
<?php
}

// Print the page header
function print_admin($page)
{
 global $sql_mysql_conn,$eveningends,$eveningends_minutes;
	global $g_room,$g_date,$min_forward_time,$max_forward_time,$morningstarts,$morningstarts_minutes;

$g_room = 1;
$g_date=time();
$g_date = $g_date - $g_date%1800+1800;

$str= $_SERVER["QUERY_STRING"];
if(!empty($str)){
	 parse_str($str, $output);
	if( isset($output['room'] )&& !empty($output['room']))
		$g_room=$output['room'];
	if( isset($output['date'])&& !empty($output['date']))
		$g_date=$output['date'];

}
    if (!isset($sql_mysql_conn))
        sql_mysql_default_connect();
//<link rel="stylesheet" href="/CSS/mybk.css" type="text/css"/>
//
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <title><?php echo get_vocab("tj_name") ?></title>
<link rel="stylesheet" href="/css/mytable.css" type="text/css"/>
<link rel="stylesheet" href="/css/grant.css" type="text/css"/>
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/myjs1.js"></script>
</head>
<body>

<script type="text/javascript">


</script>


<div id="header-row">
<div class="container">
  <div class="row">
  <div class="span9">
			<div class="navbar  pull-left" style="margin-top:40px ;margin-left:350px;">
			  <div class="navbar-inner">
				<a data-target=".navbar-responsive-collapse" data-toggle="collapse" class="btn btn-navbar"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a>
				<div class="nav-collapse collapse navbar-responsive-collapse">
				<ul class="nav">

  <?php
        $query_str="room=$g_room&date=$g_date";

        $cur1 = ($page=="table")?"class=\"active\"":"";
        echo "<li ". $cur1."> <a href=\"admin_table.php?$query_str\">" . "会议日程" . "</a></li>\n";

		$cur1 = ($page=="settime")?"class=\"active\"":"";
        echo "<li ". $cur1."> <a href=\"admin_settime.php?$query_str\">" . "时段设置" . "</a></li>\n";

       $cur1 = ($page=="grant")?"class=\"active\"":"";
        echo "<li ". $cur1."> <a href=\"admin_grant.php?$query_str\">" . "申请审批" . "</a></li>\n";

        $cur1 = ($page=="modify")?"class=\"active\"":"";
        echo "<li ". $cur1."> <a href=\"admin_modify.php?$query_str\">" . "申请修改" . "</a></li>\n";

        $cur1 = ($page=="room")?"class=\"active\"":"";
        echo "<li ". $cur1."> <a href=\"admin_room.php?$query_str\">" . "会场管理" . "</a></li>\n";

        $cur1 = ($page=="import")?"class=\"active\"":"";
        echo "<li ". $cur1."> <a href=\"admin_import.php?$query_str\">" . "导入" . "</a></li>\n";

        $cur1 = ($page=="export")?"class=\"active\"":"";
        echo "<li ". $cur1."> <a href=\"admin_export.php?$query_str\">" . "导出" . "</a></li>\n";

		$cur1 = ($page=="export_timetable")?"class=\"active\"":"";
        echo "<li ". $cur1."> <a href=\"admin_export_timetable.php?$query_str\">" . "日程表" . "</a></li>\n";


        echo "<li > <a href=\"home.php?$query_str\">" . "返回" . "</a></li>\n";

   //     $cur1 = ($page=="admin")?"class=\"active\"":"";
        echo "<li > <a  href=\"login.php\"? onclick=\"Exit()\">" ."退出" . "</a></li>\n";
        ?>


				</ul>
			  </div>

			  </div>
			</div>
		  </div>

 </div>
</div>
</div>




<?php

} // end of print_header()


?>
