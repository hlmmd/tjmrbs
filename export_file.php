<?php
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}
session_start();
	if(!isset($_SESSION['user']))
	{
		echo "please login.";
		header("url=login.php");
		exit(0);
	}
    global $sql_mysql_conn ;
date_default_timezone_set('Asia/Shanghai');
    $sql_mysql_conn = mysql_connect( "localhost","", "");//登录数据库
	mysql_select_db("tj91",$sql_mysql_conn);//选择数据库
	mysql_set_charset('utf8', $sql_mysql_conn); //设置字符集


$sql = "SELECT id,room_name FROM mrbs_room   ";
$result = mysql_query($sql);
while($row=mysql_fetch_row($result)) 
{
	$room_nameid[$row[0]]=$row[1];
}

$p_type = $_POST["radio1"];

if($p_type=="student")
	$p_type=0;
else
	$p_type=1;

$p_date1 = $_POST['date1'];
$p_date2=$_POST['date2'];
$stime = strtotime($p_date1);
$etime = strtotime($p_date2);
if($p_type==1)
	$sql = "select room_id,start_time,end_time,status,timestamp, type,unit,name,student_id,email,tel,money,count,theme from mrbs_entry where type ='$p_type' and start_time>='$stime' and end_time<='$etime' order by status asc , start_time asc; ";
else
	$sql = "select room_id,start_time,end_time,status,timestamp, type,unit,name,student_id,email,tel,money,count,theme from mrbs_entry where type !='1' and (status=1 or status=0 or status=2) and start_time>='$stime' and end_time<='$etime' order by status asc , start_time asc; ";
$result = mysql_query($sql);
$col_key = array("举办地点","举办日期","举办开始时间","举办结束时间","审核状态","递交申请/导入时间","申请类型","机构名称","申请人","学号/工号","邮箱","联系电话","费用","人数","主题");

$status_str=array("未审核","同意","拒绝");

require_once 'Classes/PHPExcel/IOFactory.php';

 $objPHPExcel = new PHPExcel();                     //实例化一个PHPExcel()对象
 $objSheet = $objPHPExcel->getActiveSheet();        //选取当前的sheet对象

$title_str = "";
if($p_type==1)
	$title_str ="企业预约";
else
	$title_str ="学校预约";
$title_str = $title_str.$p_date1."~".$p_date2 ;
//echo $title_str;

 $objSheet->setTitle($title_str);                      //对当前sheet对象命名

$ARR = array();
array_push($ARR,$col_key);


while($row=mysql_fetch_row($result)) 
{
	$data_row=array();
	foreach($row as $key=>$value)
	{

		if($key==0)//会议室
			array_push($data_row ,$room_nameid[$value]);
		else if($key==1)
		{
			//echo $value;
			$date_t = date("Y-m-d",$value);
			array_push($data_row ,$date_t);
			$date_t = date("H:i",$value);
			array_push($data_row ,$date_t);
		}
		else if($key==2)
		{
			$date_t = date("H:i",$value);
			array_push($data_row ,$date_t);
		}
		else if($key==3)
		{	
			//echo ;
			array_push($data_row ,$status_str[$value]);
		}

		else if($key==4)
		{
			$date_t = date("Y-m-d H:i",$value);
			array_push($data_row ,$date_t);
		}
		else if($key==5)
		{
			if($value==1)
				$type_t="企业";
			else if($value==0)
				$type_t="部门";
			else if($value==2)
				$type_t="社团";
			array_push($data_row ,$type_t);
		}
		else {
			array_push($data_row ,$value);
		}	
	}
	array_push($ARR,$data_row);
	//$objSheet->fromArray($data_row); 
}

	$objSheet->fromArray($ARR); 
	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');   //设定写入excel的类型
	$file_path = "/var/www/html/export/$title_str.xls";
	$file_name=$title_str.".xls"; 

		//清除缓存，很关键，否则可能会导致输出乱码。
	ob_end_clean(); 
	header("Pragma: public");  
	header("Expires: 0");  
	header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");  
    header("Content-Type:application/force-download");  
    header("Content-Type:application/vnd.ms-execl");  
    header("Content-Type:application/octet-stream");  
    header("Content-Type:application/download");;  
    header('Content-Disposition:attachment;filename='.$file_name.'');  
    header("Content-Transfer-Encoding:binary");  
    $objWriter->save('php://output');  

exit(0);

	 $objWriter->save($file_path);      //保存文件

header("Content-type:text/html;charset=utf-8"); 

//用以解决中文不能显示出来的问题 
$file_name=$title_str.".xls"; 
$file_path="/var/www/html/export/".$file_name; 
$fp=fopen($file_path,"r"); 
$file_size=filesize($file_path); 

//下载文件需要用到的头 
Header("Content-type: application/octet-stream"); 
//Header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"); 
Header("Accept-Ranges: bytes"); 
Header("Accept-Length:".$file_size); 
Header("Content-Disposition: attachment; filename=".$file_name); 
$buffer=1024; 
$file_count=0; 
//向浏览器返回数据 
while(!feof($fp) && $file_count<$file_size){ 
$file_con=fread($fp,$buffer); 
$file_count+=$buffer; 
echo $file_con; 
} 
fclose($fp); 

//	echo $sql;
?>
