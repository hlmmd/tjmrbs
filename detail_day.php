<?php
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "由于涉及网络安全问题，请不要通过修改url跳转网页，谢谢！";
exit(0);
}

require_once "functions.inc.php";
$pg="detail";
print_header("detail");

/*
global $g_room,$g_date,$g_start,$g_end;
if(!isset($g_room))
	$g_room=1;
if(!isset($g_date))
{
	$g_date=time();
	$g_date=$g_date+1800-$g_date%1800;
}


//处理Url
$str= $_SERVER["QUERY_STRING"];
if(!empty($str)){
	 parse_str($str, $output);
	if( isset($output['room']))
		$g_room=$output['room'];
	if( isset($output['date']))
		$g_date=$output['date'];
}
*/
//获取会议室名称
$room_name=array();
$sql = "SELECT id,room_name FROM mrbs_room   WHERE disabled = 0 ";
$result = mysql_query($sql);
$room_number=mysql_num_rows($result);

while($row=mysql_fetch_row($result)) 
{

	$room_name[$row[0]]=$row[1];
}

//获取当前时间戳
$year= date("Y",$g_date);
$month= date("m",$g_date);
$day= date("d",$g_date);


//获取预约信息
$am7=mktime($morningstarts, $morningstarts_minutes, 0,$month, $day, $year);

$pm7=mktime($eveningends, $eveningends_minutes, 0,$month, $day, $year);


$day_map = array();
foreach ($room_name as $rid=>$value)  
{  
  //  echo $key.'=>'.$value.', ';  

    $sql = "SELECT room_id, start_time, end_time, status ,name ,student_id  FROM mrbs_entry
             WHERE room_id = $rid
               AND start_time <= $pm7 AND end_time > $am7
          ORDER BY start_time;";   // necessary so that multiple bookings appear in the right order
//echo $sql."<br>";

   $result = mysql_query($sql);
$resnum = mysql_num_rows(  $result );

    if ( $resnum==0)
    {
	continue;
    } 
    else
    {
      while($row=mysql_fetch_row($result )) 
      {
	if($row[3]!=0 && $row[3]!=1)
		continue;
	$shour = date("H", $row[1]);
	$sminutes = date("i", $row[1]);
     	 $start_id = $shour*2+$sminutes/30;

	$ehour = date("H", $row[2]);
	$eminutes = date("i", $row[2]);
	$end_id =$ehour*2+$eminutes/30;
	for($i=$start_id;$i<$end_id;$i=$i+1)
	{
		$day_map [$rid][$i]['status']=$row[3];
		$day_map [$rid][$i]['name']=$row[4];
		$day_map [$rid][$i]['student_id']=$row[5];
	}
      }
    }
} 


?>
<body>


<div class="h2_class">
<h2 class="h2_l"><a href="detail_day.php?<?php $g_1= $g_date - 3600*24;echo"room=$g_room&date=$g_1";?>"  ><<前一天</a></h2>
<h2 class="h2_c"><?php echo date("Y-m-d",$g_date);?> 日程表</h2>
<h2 class="h2_r"><a href="detail_day.php?<?php $g_2= $g_date + 3600*24;echo"room=$g_room&date=$g_2";?>"  >后一天>></a></h2>
</div>
<table class="bordered">

    <thead>

    <tr>
<th >时间:</th>
<?php 
foreach ($room_name as $rid=>$value)  
{	
echo "<th ><a href=\"detail.php?room="."$rid&date=$g_date\">".$value.'</a></th>'."\n";
}
?>
	</tr></thead>

<?php

 $start_first_slot = ($morningstarts*60) + $morningstarts_minutes;   // minutes
    $start_last_slot  = ($eveningends*60) + $eveningends_minutes;       // minutes
$step = 30; //30 m

$start_time=array(9*60,14*60,18*60);


for( $j =  0;$j< 3 ;$j = $j+1)
{
$t = $start_time[$j];
	echo "<tr>\n";
	if($j==0)
		$ts = sprintf("%02d:%02d--%02d:%02d",$t/60,$t%60,($t+120)/60,($t+30)%60);
	else if($j==1)
		$ts = sprintf("%02d:%02d--%02d:%02d",$t/60,$t%60,($t+180)/60,($t)%60);
	else
		$ts = sprintf("%02d:%02d--%02d:%02d",$t/60,$t%60,($t+120)/60,($t)%60);
	echo '<td>'.$ts.'</td>';
	echo"\n";   
foreach ($room_name as $rid=>$value)  
{
	$tempdate = mktime($t/60,$t%60,0,$month,$day,$year);
	//not booked
	if($tempdate<time())
	{
		echo " <td style=\"cursor:default; background:	#aab2bd;\"></td>\n";	
	}//not booked
	else if(!isset($day_map[$rid][$t/30]['status']) && $tempdate<(strtotime(date("Y-m-d",time()))+$min_forward_time) )
	{
		echo " <td style=\"cursor:default; background:	#FFFFFF;\"></td>\n";	
	}
	else if(!isset($day_map[$rid][$t/30]['status']) )
	{
		$tempdate = mktime($t/60,$t%60,0,$month,$day,$year);
		//echo date("Y-m-d h:i:sa",$tempdate);
		$href ="location.href=\"booking.php?room=".$rid."&date=".$tempdate."\"";
		//echo $href;
		echo '<td onclick='.$href.' ></td>'."\n";
	}
	else if($day_map[$rid][$t/30]['status']==0)//预约，待审核
	{
		echo " <td style=\"cursor:default; background:	#00BFFF;\"></td>\n";	
	}
	else if($day_map[$rid][$t/30]['status']==1)//已审核
	{
		echo " <td style=\"cursor:default; background: #7FFFD4;\"></td>\n";	
	}
}

echo "</tr>\n";

}

?>   

</table>

<br>
    <div class="h2_class">
<p width="1000">绿色：已审核<br>蓝色：待审核<br>单击空白处申请<br>单击会议室切换到按周显示</p>
</div>

<?php
output_trailer();


?>