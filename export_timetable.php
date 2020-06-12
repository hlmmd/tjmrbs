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


	//A3总列宽按180计算

$rowheight = 32;

$dir = dirname(__FILE__); //找到当前脚本所在路径

require_once 'Classes/PHPExcel/IOFactory.php';

$objPHPExcel = new PHPExcel();                     //实例化一个PHPExcel()对象

$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight($rowheight);  
//$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);

$objSheet = $objPHPExcel->getActiveSheet();        //选取当前的sheet对象
$objSheet->setTitle('日程表'); //给当前活动sheet设置名称


$header_arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M', 'N','O','P','Q','R','S','T','U','V','W','X','Y','Z');  



$objActSheet = $objPHPExcel->getActiveSheet();  

$objActSheet->setCellValue('A1',  "日期");  

$sql = "SELECT room_name,id FROM mrbs_room where  disabled = 0  ";
$result = mysql_query($sql);
$sqlnum=mysql_num_rows($result);

$colwidth = 180/(2+$sqlnum);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth($colwidth);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth($colwidth);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight($rowheight*2);

$objActSheet->mergeCells('A1:B1');

$i=2;
//$room_col = array();
while($row=mysql_fetch_row($result)) 
{
//	echo  $row[1];
	
	$room_col[$row[1]]=$header_arr[$i];
	$objActSheet->setCellValue($header_arr[$i].'1', $row[0]);
	$objPHPExcel->getActiveSheet()->getColumnDimension($header_arr[$i])->setWidth($colwidth);
	$i = $i+1;
	
}

///border

$styleArray = array(  
	'borders' => array(  
		'allborders' => array(  
			//'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的  
			'style' => PHPExcel_Style_Border::BORDER_THIN,//细边框  
			
			//'color' => array('argb' => 'FFFF0000'),  
		),  
	),  
	'font' => array(
		'bold' => false,
		//'color' => array('rgb' => 'FF0000'),
		'size' => 10,
		'name' => '宋体'
	)
);  
$objActSheet->getStyle('A1:'.$header_arr[$i-1].'22')->applyFromArray($styleArray);

$objActSheet->getStyle('A1:'.$header_arr[$i-1].'22')->getAlignment()->setWrapText(True);


//date

//获取当前时间戳

$p_date = $_POST['date_timetable'];

$g_date =strtotime($p_date);
$year= date("Y",$g_date);
$month= date("m",$g_date);
$day= date("d",$g_date);

//获取当前一周日期
$t_year=array();
$t_month=array();
$t_day=array();


$monday_time  = strtotime( date('Y-m-d',($g_date-((date('w',$g_date)==0?7:date('w',$g_date))-1)*24*3600)));

for($i=0;$i<7;$i=$i+1)
{
	$t_year[$i]= date('Y',$monday_time+$i*24*3600);
	$t_month[$i]= date("m",($monday_time+$i*24*3600));
	$t_day[$i]= date('d',$monday_time+$i*24*3600);
}

$weekday_chinese = array("一", "二", "三", "四", "五", "六", "日");
for( $i = 0;$i<7; $i=$i+1)
{
	$rownum = $i*3+2;
	$objActSheet->setCellValue('A'.$rownum, $t_month[$i].'月'.$t_day[$i].'日'."\n".'周'.$weekday_chinese[$i] );

	$objActSheet->mergeCells('A'.$rownum.":".'A'.($rownum+2));
}

for( $i = 0;$i<7; $i=$i+1)
{
	$objActSheet->setCellValue('B'.($i*3+2), "上午");
	$objActSheet->setCellValue('B'.($i*3+3), "下午");
	$objActSheet->setCellValue('B'.($i*3+4), "晚上");
}





$stime = $monday_time;
$etime = $monday_time+7*24*3600;
$sql = "select room_id,start_time,end_time,unit,theme from mrbs_entry where status = 1 and start_time>='$stime' and end_time<='$etime' order by  start_time asc; ";
$result = mysql_query($sql);


//  return '星期'.$weekarray[date('w',$unixTime)];




while($row=mysql_fetch_row($result)) 
{
	$rid = $row[0];

	$columnletter =$room_col[$rid] ;

	if ($row[4] !=""){
		$content = $row[4];
	}
		else $content = $row[3];
	//	$content = $row[3]."\n".$row[4];
	//$content = $row[4];
	$starthour =  date('H',$row[1]);
	$endhour =  date('H',$row[2]);

	$weekday = date('w',$row[1]);
	$weekday = $weekday==0?7:$weekday;

	$rowbase= 3*$weekday -1  ;

	if ($starthour<12)		//早上
	{
		$rowpos = $rowbase;
		$objActSheet->setCellValue($columnletter.$rowpos, $content);
	}
	if ( ( $starthour>=12 && $starthour <=18) ||  ( $endhour>=12 && $endhour <=18)  || ( $starthour<12 &&  $endhour >18) ) //下午
	{
		$rowpos = $rowbase+1;
		$objActSheet->setCellValue($columnletter .$rowpos, $content);
	}
	
	if ($endhour>18)	//晚上
	{
		$rowpos = $rowbase+2;
		$objActSheet->setCellValue($columnletter .$rowpos,  $content);
	}



}


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5'); // 

$file_name = $t_month[0].'月'.$t_day[0].'日-'.$t_month[6].'月'.$t_day[6].'日'.'.xls';
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





?>
