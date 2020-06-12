<?php

//防止从url直接访问本文件
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}
require_once "functions.inc.php";

checkright();

print_admin("room");
?>

<script type="text/javascript">
function checkform(){ 
	if(!confirm("确定要修改吗？")){  
	return false;   
	}  
      if(document.getElementById('room').value.length==0){    
        alert('会议室名称输入为空！');
document.getElementById('form').action="#"; 
      //document.getElementById('room').focus();
        return false;
    }




	document.getElementById('form').action="modify_room.php"; 
	
}

  function modify(node,modify_id)
{
// 按钮的父节点的父节点是tr。  
            var tr1 = node.parentNode.parentNode;  
	var room=tr1.cells[1].innerText;
	var type = tr1.cells[2].innerText;
	var equip=tr1.cells[3].innerText;
	var people =tr1.cells[4].innerText;
	

	document.getElementById('id').readonly=false;
	document.getElementById('id').value = modify_id;
	document.getElementById('id').readonly=true;

	document.getElementById('room').value = room;
	document.getElementById('type').value = type;
	
	document.getElementById('equip').value = equip;
	document.getElementById('people').value =  people;
	document.getElementById('end').value = end;
	

	//alert(modify_id);
}
</script>


<form action="#" method="post">

    <h1><center>会议室管理</center></h1>

    <table class="grant" id="tb_room">
        <thead>
        <tr>
<?php
$str=array("序号","会议室名称","会议室类型","设备","人数","状态","停用/启用","修改");

foreach ($str as $i=>$value)
{
	echo "<th>$value</th>\n";
}

?>
        </tr>
        </thead>
   

  <?php
            $sql="SELECT id, room_name, type, equipment, capacity,disabled, description FROM mrbs_room  order by disabled asc ,id asc";
   
	  $result= mysql_query($sql);
	    $sqlnum=mysql_num_rows($result);
	    if($sqlnum!=0)
	    {
		$sqlcount=0;
                while($row=mysql_fetch_row($result))
                {
		$sqlcount = $sqlcount +1;
                    echo "<tr>";
	      foreach($row as $id=>$value)
	{
		if($id==5)
		{
			if ($value==0)
				 echo "<td style='background:#7CFC00' onclick=''>启用</td>\n";
			else if ($value==1)
				 echo "<td style='background:#FF0000' onclick=''>停用</td>\n";
		}
		else  if($id==0)
			echo "<td>$sqlcount</td>\n";
		else if($id!=6)
		 	 echo "<td>$value</type></td>\n";
	}
	     if($row[5]==0)
	                    echo "<td><input type='button' value='停用' id ='$row[0]' onclick='Disable(id)'></td>\n";
	    else
                   	 echo "<td><input type='button' value='启用' id ='$row[0]' onclick='Enable(id)'></td>\n";
	
		echo "<td><input type='button' value='修改' id ='$row[0]' onclick='modify(this,id)'></td>\n";

         //         echo "</tr>\n";
	///   $t1 = sizeof($str)-1;
	//	 echo "<tr><td>详情:</td><td colspan=\"$t1\" style=\"text-align:left;\">$row[6]</td></tr>";
            		}
	    }
	echo "</table></form>";
	   if($sqlnum==0)
	echo "<p><center>暂无记录！</center></p>";

?>
<div style="width:300px;;margin:0px auto">
	<form  method="post" id="form"> 
<label for="rid" >编号:</label>
            	<input type="text" id="id" name="rid"  readonly value="" /> 
<label for="room" >会议室名称:</label>
            	<input type="text" id="room"   name="room" value="" /> 
			<label for="type" >会议室类型:</label>
            	<input type="text" id="type"   name="type" value="" /> 
	<label for="equip" >设备:</label>
            	<input type="text" id="equip"   name="equip" value="" /> 
            <label for="people" >人数:</label>
			<input type="tel" id="people" name="people" value="" /> 
  

   	<br>
            <input type="submit" name="sub" value="提交" id="sub"  onclick="checkform()"  /> 
</div>

<?php

output_trailer();
 ?>