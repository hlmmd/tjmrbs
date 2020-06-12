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

//获取当前一周日期
$t_year=array();
$t_month=array();
$t_day=array();

$monday_date  = date('Y-m-d',($g_date-((date('w',$g_date)==0?7:date('w',$g_date))-1)*24*3600));

$monday_time  = strtotime( date('Y-m-d',($g_date-((date('w',$g_date)==0?7:date('w',$g_date))-1)*24*3600)));

for($i=0;$i<$num_of_days_admin;$i=$i+1)
{
	$t_year[$i]= date('Y',$monday_time+$i*24*3600);
	$t_month[$i]= date("m",($monday_time+$i*24*3600));
	$t_day[$i]= date('d',$monday_time+$i*24*3600);
}

//$today = date("Y-m-d");
//$Monday = date('Y-m-d',(time()-((date('w')==0?7:date('w'))-1)*24*3600))

//$Sunday = date('Y-m-d',(time()+(7-(date('w')==0?7:date('w')))*24*3600))

// $d1 =strtotime( date('Y-m-d',(time()-((date('w')==0?7:date('w'))-1)*24*3600)));
// $d2 =strtotime( date('Y-m-d',(time()+(7-(date('w')==0?7:date('w')))*24*3600)));

//获取预约信息
$am7 = array();
$pm7 = array();
for ($j = 0; $j<=($num_of_days_admin-1); $j++)
{

    $am7[$j]=mktime($morningstarts, $morningstarts_minutes, 0,
                    $t_month[$j], $t_day[$j], $t_year[$j]);

 $pm7[$j]=mktime($eveningends, $eveningends_minutes, 0,
                    $t_month[$j], $t_day[$j], $t_year[$j]);
}

$week_map = array();
for ($j = 0; $j<=($num_of_days_admin-1) ; $j++)
{
    $sql = "SELECT room_id, start_time, end_time, status ,name ,unit ,type,theme FROM mrbs_entry
             WHERE room_id = $g_room
               AND start_time <= $pm7[$j] AND end_time > $am7[$j]
          ORDER BY start_time;";   // necessary so that multiple bookings appear in the right order
//echo $sql."<br>";
    // Each row returned from the query is a meeting. Build an array of the
    // form:  $week_map[room][weekday][slot][x], where x = id, color, data, long_desc.
    // [slot] is based at 000 (HHMM) for midnight, but only slots within
    // the hours of interest (morningstarts : eveningends) are filled in.
    // [id], [data] and [long_desc] are only filled in when the meeting
    // should be labeled,  which is once for each meeting on each weekday.
    // Note: weekday here is relative to the $weekstarts configuration variable.
    // If 0, then weekday=0 means Sunday. If 1, weekday=0 means Monday.

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
		$week_map[$j][$i]['status']=$row[3];
		if($i==$start_id)
		{
		$week_map[$j][$i]['name']=$row[4];
		$week_map[$j][$i]['student_id']=$row[5];
		$week_map[$j][$i]['type']=$row[6];
		$week_map[$j][$i]['theme']=$row[7];
		}
	}
      }
    }
} 



?>
<div class = "timetable">
    <ul>
<?php
foreach($room_name as $rid=>$value)
{
	$url="admin_table.php?room=$rid&date=$g_date";
	echo "<li ><a href=\"$url\">$value</a></li>\n";
}
?>
    </ul>
</div>

<div class="h2_class">
<h2 class="h2_l"><a href="admin_table.php?<?php $g_1= $g_date - 3600*24*7 ;  echo"room=$g_room&date=$g_1";?>"  ><<上一周</a></h2>
<h2 class="h2_c"><?php echo $room_name[$g_room];?> 日程表</h2>
<h2 class="h2_r"><a href="admin_table.php?<?php $g_2= $g_date + 3600*24*7;echo"room=$g_room&date=$g_2";?>"  >下一周>></a></h2>
</div>
<table class="bordered">

    <thead>

    <tr>
<th >时间:</th>
<?php 
$temp= array("一", "二", "三", "四", "五", "六", "日");
for($i=0;$i< $num_of_days_admin;$i=$i+1)
{	
$tdate = mktime(8,0,0,$t_month[$i],$t_day[$i],$t_year[$i]);

	echo '<th >周' .$temp[$i]."<br><a href=\"admin_table_day.php?room=$g_room&date=$tdate\">".$t_month[$i].'月'.$t_day[$i].'日</a></th>'."\n";
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

for($i=0;$i <$num_of_days_admin  ;$i++)
{
	if(!isset($week_map[$i][$t/30]['status']) )
	{
		echo "<td style=\"cursor:default;background: #FFFFFF;\"></td>"."\n";
	}
	else if($week_map[$i][$t/30]['status']==0)//预约，待审核
	{
		$s_name=$week_map[$i][$t/30]['name'];
		$s_id = $week_map[$i][$t/30]['student_id'];
		$s_theme = $week_map[$i][$t/30]['theme'];
		echo " <td style=\"	overflow:hidden;color:#FF0000;padding:0px;font-size:14px;cursor:default; background: #00BFFF;\">$s_id<br>$s_theme<br>$s_name</td>\n";	
	}
	else if($week_map[$i][$t/30]['status']==1)//已审核
	{
		$s_name=$week_map[$i][$t/30]['name'];
		$s_id = $week_map[$i][$t/30]['student_id'];
		$s_theme = $week_map[$i][$t/30]['theme'];
		echo " <td style=\"	overflow:hidden;color:#FF0000;padding:0px;font-size:14px;cursor:default; background: #7FFFD4;\">$s_id<br>$s_theme<br>$s_name</td>\n";	
	
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