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

//获取当前一周日期
$t_year=array();
$t_month=array();
$t_day=array();

$monday_date  = date('Y-m-d',($g_date-((date('w',$g_date)==0?7:date('w',$g_date))-1)*24*3600));

$monday_time  = strtotime( date('Y-m-d',($g_date-((date('w',$g_date)==0?7:date('w',$g_date))-1)*24*3600)));

for($i=0;$i<$num_of_days;$i=$i+1)
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
for ($j = 0; $j<=($num_of_days-1); $j++)
{

    $am7[$j]=mktime($morningstarts, $morningstarts_minutes, 0,
                    $t_month[$j], $t_day[$j], $t_year[$j]);

 $pm7[$j]=mktime($eveningends, $eveningends_minutes, 0,
                    $t_month[$j], $t_day[$j], $t_year[$j]);
}

$week_map = array();
for ($j = 0; $j<=($num_of_days-1) ; $j++)
{
    $sql = "SELECT room_id, start_time, end_time, status ,name ,unit ,type,theme FROM mrbs_entry
             WHERE room_id = $g_room
               AND start_time <= $pm7[$j] AND end_time > $am7[$j] AND status!=2
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
	if($row[3]!=0 && $row[3]!=1  && $row[3]!=100)
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
		$week_map[$j][$i]['name']=$row[4];
		$week_map[$j][$i]['student_id']=$row[5];
		$week_map[$j][$i]['type']=$row[6];
		$week_map[$j][$i]['theme']=$row[7];
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
	$url="detail.php?room=$rid&date=$g_date";
	echo "<li ><a href=\"$url\">$value</a></li>\n";
}
?>
    </ul>
</div>

<div class="h2_class">
<h2 class="h2_l"><a href="detail.php?<?php $g_1= $g_date - 3600*24*7 ;  echo"room=$g_room&date=$g_1";?>"  ><<上一周</a></h2>
<h2 class="h2_c" ><?php echo $room_name[$g_room];?> </h2>
<h2 class="h2_r"><a href="detail.php?<?php $g_2= $g_date + 3600*24*7;echo"room=$g_room&date=$g_2";?>"  >下一周>></a></h2>
</div>
<table class="bordered">

    <thead>

    <tr>
<th >时间:</th>
<?php 
$temp= array("一", "二", "三", "四", "五", "六", "日");
for($i=0;$i< $num_of_days;$i=$i+1)
{	
$tdate = mktime(8,0,0,$t_month[$i],$t_day[$i],$t_year[$i]);
//if($tdate<(strtotime(date("Y-m-d",time()) )) || $tdate>(strtotime(   date("Y-m-d",time()) )+$max_forward_time+$eveningends*3600+$eveningends_minutes*60))
	echo '<th >周' .$temp[$i]."<br>".$t_month[$i].'月'.$t_day[$i].'日</th>'."\n";
//else
//	echo '<th >周' .$temp[$i]."<br><a href=\"detail_day.php?room=$g_room&date=$tdate\">".$t_month[$i].'月'.$t_day[$i].'日</a></th>'."\n";
}
?>
	</tr></thead>

<?php

 $start_first_slot = ($morningstarts*60) + $morningstarts_minutes;   // minutes
    $start_last_slot  = ($eveningends*60) + $eveningends_minutes;       // minutes
$start_time=array(9*60,14*60,18*60);
$end_time = array(11*60+30,17*60,20*60);
$step = 30; //30 m
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

for($i=0;$i < $num_of_days  ;$i++)
{

	$vt = 0;
	for($f = $start_time[$j]/30;$f<=$end_time[$j]/30;$f=$f+1)
	{	
		
		//echo $week_map[$i][$f]['status'];
		if( isset($week_map[$i][$f]['status'])   &&    $week_map[$i][$f]['status'] ==0      ){
		
		$s_name=$week_map[$i][$f]['name'];
		$s_id = $week_map[$i][$f]['student_id'];
		$s_theme = $week_map[$i][$f]['theme'];
		$s_type = $week_map[$i][$f]['type'];	
			$vt=1;
			break;
		}
		else if (isset($week_map[$i][$f]['status'])   &&    $week_map[$i][$f]['status'] ==1      ){
		$s_name=$week_map[$i][$f]['name'];
		$s_id = $week_map[$i][$f]['student_id'];
		$s_theme = $week_map[$i][$f]['theme'];
		$s_type = $week_map[$i][$f]['type'];	
			$vt=2;
			break;
		}
		else if(  ( isset($week_map[$i][$f]['status'])   &&    $week_map[$i][$f]['status'] ==100)      ){
			
			$vt=100;		 //时段不开放
			break;
		}
		else if ($room_number==0)
		{
			$vt=100;		 //时段不开放
			break;
		}

	}	
	//echo $vt;
	

	$tempdate = mktime($t/60,$t%60,0,$t_month[$i],$t_day[$i],$t_year[$i]);
	if($vt==100 || $tempdate<time() || $tempdate>(strtotime(   date("Y-m-d",time()) )+$max_forward_time+$eveningends*3600+$eveningends_minutes*60))
	{
		echo " <td style=\"cursor:default; background:	#aab2bd;\"></td>\n";	
	}//not booked
	else if($vt==0&& $tempdate<(strtotime(date("Y-m-d",time()))+$min_forward_time)        ) //提前预定
	{
		echo " <td style=\"cursor:default; background:	#aab2bd;\" title = \"请提前一周预定！\"></td>\n";	
	}
	else if($vt==0)//	else if(!isset($week_map[$i][$t/30]['status']))
	{
		//echo date("Y-m-d h:i:sa",$tempdate);
		$href ="location.href=\"booking.php?room=".$g_room."&date=".$tempdate."\"";
		//echo $href;
		echo '<td onclick='.$href.' ></td>'."\n";
	}
	else if($vt==1)//	else if($week_map[$i][$t/30]['status']==0)//预约，待审核
	{
	
		if($s_type==0 ||$s_type==2)
	//	if(1)
			echo " <td style=\"	overflow:hidden;color:#FF0000;padding:0px;font-size:14px;cursor:default; background:	#00BFFF;\">$s_id<br>$s_theme<br>已预约，待提交申请表</td></td>\n";	
		else if($s_type==1)
	//	echo " <td style=\"	overflow:hidden;color:#FF0000;padding:0px;font-size:14px;cursor:default; background:	#00BFFF;\">$s_id</td></td>\n";	
			echo " <td style=\"	overflow:hidden;color:#FF0000;padding:0px;font-size:14px;cursor:default; background:	#00BFFF;\">招聘工作<br>就业中心</td></td>\n";	

	}
	else if($vt==2)//	else if($week_map[$i][$t/30]['status']==1)//已审核
	{
	//	$s_name=$week_map[$i][$t/30]['name'];
	//	$s_id = $week_map[$i][$t/30]['student_id'];
	//	$s_theme = $week_map[$i][$t/30]['theme'];
		if($s_type==0 ||$s_type==2)
			echo " <td style=\"	overflow:hidden;color:#FF0000;padding:0px;font-size:14px;cursor:default; background: #7FFFD4;\">$s_id<br>$s_theme<br>预约成功</td>\n";
		else if($s_type==1)
	//		echo " <td style=\"	overflow:hidden;color:#FF0000;padding:0px;font-size:14px;cursor:default; background:	#00BFFF;\">$s_id</td></td>\n";	
			echo " <td style=\"	overflow:hidden;color:#FF0000;padding:0px;font-size:14px;cursor:default; background: #7FFFD4;\">招聘工作<br>就业中心</td>\n";
//		echo " <td style=\"color:#FF0000;cursor:default; background: #228B22;\"></td>\n";	
	
	}

	
}

echo "</tr>\n";

}

?>   

</table>

<br>
<div class="h2_class">
<p width="1000"  > <font size="5"> 附件：<a href="file/大学生活动中心场地使用申请表.doc">大学生活动中心场地使用申请表</a><br>具体细节请阅读<a href = http://meeting91.tongji.edu.cn/qa.php>流程说明</a></font></p>

</div>


<?php
output_trailer();


?>
