<?php

//防止从url直接访问本文件
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}

require_once "functions.inc.php";
checkright();

print_admin("table");

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

    $sql = "SELECT room_id, start_time, end_time, status ,name ,unit FROM mrbs_entry
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
		if($i==$start_id){
			$day_map [$rid][$i]['name']=$row[4];
			$day_map [$rid][$i]['student_id']=$row[5];
		}
	}
      }
    }
} 


?>
<body>


<div class="h2_class">
<h2 class="h2_l"><a href="admin_table_day.php?<?php $g_1= $g_date - 3600*24;echo"room=$g_room&date=$g_1";?>"  ><<前一天</a></h2>
<h2 class="h2_c"><?php echo date("Y-m-d",$g_date);?> 日程表</h2>
<h2 class="h2_r"><a href="admin_table_day.php?<?php $g_2= $g_date + 3600*24;echo"room=$g_room&date=$g_2";?>"  >后一天>></a></h2>
</div>
<table class="bordered">

    <thead>

    <tr>
<th >时间:</th>
<?php 
foreach ($room_name as $rid=>$value)  
{	
echo "<th ><a href=\"admin_table.php?room="."$rid&date=$g_date\">".$value.'</a></th>'."\n";
}
?>
	</tr></thead>

<?php

 $start_first_slot = ($morningstarts*60) + $morningstarts_minutes;   // minutes
    $start_last_slot  = ($eveningends*60) + $eveningends_minutes;       // minutes
$step = 30; //30 m

for( $t =  $start_first_slot;$t< $start_last_slot ;$t = $t+$step)
{
	echo "<tr>\n";
	$ts = sprintf("%02d:%02d--%02d:%02d",$t/60,$t%60,($t+30)/60,($t+30)%60);
	echo '<td>'.$ts.'</td>';
	echo"\n";   
foreach ($room_name as $rid=>$value)  
{
	//not booked
	if(!isset($day_map[$rid][$t/30]['status']) )
	{
		echo "<td style=\"cursor:default;background: #FFFFFF;\"></td>"."\n";
	}
	else if($day_map[$rid][$t/30]['status']==0)//预约，待审核
	{
		$s_name=$day_map [$rid][$t/30]['name'];
		$s_id = $day_map [$rid][$t/30]['student_id'];
		echo " <td style=\"color:#FF0000;cursor:default; background:#00BFFF;\">$s_id<br>$s_name</td>\n";	
	}
	else if($day_map[$rid][$t/30]['status']==1)//已审核
	{		$s_name=$day_map [$rid][$t/30]['name'];
		$s_id = $day_map [$rid][$t/30]['student_id'];
		echo " <td style=\"color:#FF0000;cursor:default; background: #7FFFD4;\">$s_id<br>$s_name</td>\n";	
	}
}

echo "</tr>\n";

}

?>   

</table>

<br>
    

<?php
output_trailer();


?>