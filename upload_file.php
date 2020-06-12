<?php
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}
require_once "functions.inc.php";
checkright();
sql_mysql_default_connect();
if($_FILES["file"]["size"]>=1024*1024*2)
{	
	echo "请上传小于2M的文件";
}

if (
(    ($_FILES["file"]["type"] == "application/vnd.ms-excel") ||  ($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") )  
)
  {
  if   ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
  //  echo "文件名: " . $_FILES["file"]["name"] . "<br />";
 //   echo "类型: " . $_FILES["file"]["type"] . "<br />";
   // echo "大小: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
//   echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

$filename =  "/var/www/html/upload/" .  date("Y-m-d_H:i:s",time())."_".$_FILES["file"]["name"];

 move_uploaded_file($_FILES["file"]["tmp_name"], $filename);
 //     echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
     


//*.csv *.xls  *.xlsx
require_once 'Classes/PHPExcel/IOFactory.php';


 $objPHPExcelReader = PHPExcel_IOFactory::load($filename);  //加载excel文件
$col_name=array();
$bookdata=array();


$meeting_room_prefix = "四平校区-经纬楼";


$col_name_insert = array("招聘单位", "举办地点", "举办日期", "举办开始时间", "举办结束时间");//注意！日期必须要在时间前面（计算用）
if(in_array("举办地点",$col_name_insert ) )
	$room_key = array_keys($col_name_insert,"举办地点" )[0];

//$col_name_insert = array("unit"=>"招聘单位", "room_id"=>"举办地点","unit"=> "举办日期", "unit"=>"举办开始时间","unit"=> "举办结束时间");

$p_unit;$p_room_id;$p_start_time;$p_end_time;$p_date;
//unit,name,student_id,room_id,tel,email,count,theme,detail,start_time,end_time,timestamp

$room_nameid=array();
$room_nameid=array();

$conflict_num = 0;
$new_num = 0;
$wrongplace_num = 0;
$sql = "SELECT id,room_name FROM mrbs_room   WHERE disabled = 0 ";
$result = mysql_query($sql);
while($row=mysql_fetch_row($result)) 
{
	$room_nameid[$row[0]]=$row[1];
}

/*
foreach($room_nameid as $room_name=>$room_id)
{
	echo $room_name.":".$room_id;
}
*/

 foreach(  $objPHPExcelReader->getWorksheetIterator() as $sheet  )  //循环读取sheet
 {
     foreach($sheet->getRowIterator() as $row)  //逐行处理
     {

      if($row->getRowIndex()==1)  //第一行，读取列名
         {
	  foreach($row->getCellIterator() as $key=>$cell)  //逐列读取
    	   {
          		$data = $cell->getValue(); //获取cell中数据
		//array_push($col_name,$data);
		$col_name[$key]=$data;
     	    }
	foreach($col_name_insert as $value)		//确定excel中包含每个关键字
	{
		if(!in_array($value,$col_name))
		{echo "未找到".$value."!";exit(0);}
	}

             continue;
         }

	$choose_key = array();
	foreach($col_name as $key=>$cname)
	{
		if( in_array($cname,$col_name_insert))
			array_push($choose_key,$key);
	}
	
        $row_data = array();

         foreach($row->getCellIterator() as $key=>$cell)  //逐列读取
         {	
	
	if( in_array($key,$choose_key ) ){
		$i = array_keys($choose_key,$key )[0];
		$row_data[$i]= $cell->getValue();
	}
         }
	$flag_wrongplace = 0;
	foreach($row_data as $key=>$data)
	{
		//获取unit
		if($col_name_insert[$key]=="招聘单位" )
		{	
			$p_unit = $data;
		}
		//获取room_id
		else if($col_name_insert[$key]=="举办地点" )
		{
			if(!strstr($data,$meeting_room_prefix))
			{
				$flag_wrongplace = 1;
				$wrongplace_num++;
				break;
			}
			$start = strlen($meeting_room_prefix);
			$roomname = substr($data,$start);
			
			if( !in_array( $roomname,$room_nameid)){
				echo $roomname ."已停用<br>";
				$wrongplace_num++;
				$flag_wrongplace = 1;
				break;
			}
			else
				$p_room_id = array_keys($room_nameid,$roomname)[0];
			
		}
		
		else if($col_name_insert[$key]=="举办日期" )
		{	
			$p_date = $data;
		}
		else if($col_name_insert[$key]=="举办开始时间" )
		{	
			$p_start_time = strtotime($p_date." ".$data); 
		}
		else if($col_name_insert[$key]=="举办结束时间" )
		{	
			$p_end_time = strtotime($p_date." ".$data); 
		}
	}
	if( $flag_wrongplace != 0)
		continue;
	//检查记录是否已经被插入过
	$sql = "select count(*) from mrbs_entry where  room_id=$p_room_id and  status!=2  and ( start_time >= $p_start_time and start_time < $p_end_time or end_time > $p_start_time and end_time <= $p_end_time  )" ;

	$result = mysql_query($sql);
	$row=mysql_fetch_row($result);
	if(isset($row) && $row[0]>=1){
		$conflict_num++;
		continue;
	}

	//插入
	$today_time = strtotime(date("Y-m-d",time())) ;
	$sql = "INSERT into mrbs_entry (type,unit,room_id,start_time,end_time,timestamp) values ( 
	'1','$p_unit','$p_room_id','$p_start_time','$p_end_time',    '$today_time'  );";
	$result = mysql_query($sql);
	if($result==1)
		$new_num++;
	else
		$conflict_num++;
     }
 }



echo "新导入：".$new_num." 重复".$conflict_num." 无效数据".$wrongplace_num;

unlink($filename);

    }
  }
else
  {
  echo "Invalid file";
  }
?>