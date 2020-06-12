<?php

//防止从url直接访问本文件
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}
require_once "functions.inc.php";

checkright();
print_admin("grant");


?>


<form action="admin_accept.php" method="post">

    <h1><center>会议室申请记录</center></h1>

    <table class="grant">
        <thead>
        <tr>

<?php
$str=array("序号","会议室","单位","学号/工号","姓名","预约日期","开始","结束","主题","人数","电话","邮箱","申请时间","费用","状态","同意","拒绝");

foreach ($str as $i=>$value)
{
	echo "<th>$value</th>\n";
}

?>
        </tr>
        </thead>
        

  <?php
	$room_name = array();
	$sql = "SELECT id,room_name FROM mrbs_room ";
	$result = mysql_query($sql);

	while($row=mysql_fetch_row($result)) 
	{
		$room_name[$row[0]]=$row[1];
	}
	$time_now=time();
            $sql="SELECT id, room_id, unit,student_id, name, start_time, end_time, theme,count ,tel,email, timestamp,money,status   FROM mrbs_entry where start_time>$time_now AND status!=100 and type!='1' order by status asc , start_time asc";//，timestamp desc
            $result= mysql_query($sql);
	    $sqlnum=mysql_num_rows($result);
	    if($sqlnum!=0)
	    {
		$sqlcount=0;
                while($row=mysql_fetch_row($result))
                {
		$sqlcount = $sqlcount +1;
	/*	
                    $detail= $row[0];
                    $room_id= $row[1];
                    $student_id= $row[2];
                    $name= $row[3];
                    $start_time= $row[4];
                    $end_time= $row[5];
                    $theme =$row[6];
                    $count =$row[7];
                    $tel =$row[8];
                    $email =$row[9];
                    $timestamp =$row[10];
                    $status =$row[11];
	*/
		$data=array();
	     for($i=0;$i<=13;$i=$i+1)
	     {
		$data[$i]=$row[$i];
	    }
	
                    echo "<tr>";
	      foreach($row as $id=>$value)
	{
		if($id==1)
			echo "<td>$room_name[$value]</td>\n";
		else if($id==13)
		{
			if ($value==0)
				 echo "<td style='background:#FFFF00'>未审核</td>\n";
			else if ($value==1)
				 echo "<td style='background:#7CFC00'>已同意</td>\n";
			else if ($value==2)
				 echo "<td style='background:#FF0000'>已拒绝</td>\n";
		}
		else if($id==5)
		{		 echo "<td>".date("Y-m-d",$value)."</td>\n";
                    			echo "<td>".date("H:i",$value)."</td>\n";
		}
		else if($id==6)
		{		 echo "<td>".date("H:i",$value)."</td>\n";
		}
		else if($id==11)
		{		 echo "<td>".date("Y-m-d H:i:s",$value)."</td>\n";
		}
		else  if($id==0)
			echo "<td>$sqlcount</td>\n";
		else if($id!=14)
		 	 echo "<td>$value</td>\n";
	}
	      if($row[13]!=1)
	                    echo "<td><input type='button' value='同意' id ='$row[0]' onclick='Accept(id)'></td>\n";
	    else
		echo "<td></td>";
	   if($row[13]!=2)
                   	 echo "<td><input type='button' value='拒绝' id ='$row[0]' onclick='Reject(id)'></td>\n";
	    else if($row[13]!=1)
		 echo "<td><input type='button' value='删除' id ='$row[0]' onclick='Delete(id)'></td>\n";
	   else
		echo "<td></td>";
                    echo "</tr>\n";
	//	$t1 = sizeof($str)-1;
	//	 echo "<tr><td>详情:</td><td colspan=\"$t1\" style=\"text-align:left;\">$row[14]</td></tr>";
            		}
	    }
	echo "</table></form>";
	   if($sqlnum==0)
	echo "<p><center>暂无记录！</center></p>";
output_trailer();
 ?>

