<?php

//防止从url直接访问本文件
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}
require_once "functions.inc.php";

checkright();
print_admin("modify");

?>
<script type="text/javascript">
function checkform(){ 
	if(!confirm("确定要修改吗？")){  
	return false;   
	}  
      if(document.getElementById('name').value.length==0){    
        alert('姓名输入为空！');
document.getElementById('form').action="#"; 
      //document.getElementById('name').focus();
        return false;
    }

 if(document.getElementById('unit').value.length==0){    
        alert('单位输入为空！');
document.getElementById('form').action="#"; 
      //document.getElementById('unit').focus();
        return false;
    }

 if(document.getElementById('money').value.length==0){    
        alert('费用输入为空！');
document.getElementById('form').action="#"; 
      //document.getElementById('money').focus();
        return false;
    }


    if(document.getElementById('number').value.length==0){    
        alert('学号输入为空！');
document.getElementById('form').action="#"; 
// document.getElementById('number').focus();
        return false;
    }

    if(document.getElementById('date').value.length==0){    
        alert('日期输入为空！');
document.getElementById('form').action="#"; 
  //      document.getElementById('date').focus();
        return false;
    }

    if(document.getElementById('start').value.length==0){    
        alert('开始时间输入为空！');
document.getElementById('form').action="#"; 
   //     document.getElementById('start').focus();
        return false;
    }

    if(document.getElementById('end').value.length==0){    
        alert('结束时间输入为空！');
document.getElementById('form').action="#"; 
  //      document.getElementById('end').focus();
        return false;
    }
	var t1 =document.getElementById('start').value;
	var t2 =document.getElementById('end').value;
	if(t1>=t2)
{    
        alert('结束时间需大于结束时间！');
document.getElementById('form').action="#"; 
  //      document.getElementById('end').focus();
        return false;
    }
	document.getElementById('form').action="modify_entry.php"; 
	
}

  function modify(node,modify_id)
{
// 按钮的父节点的父节点是tr。  
            var tr1 = node.parentNode.parentNode;  
	var room_name=tr1.cells[1].innerText;
	var unit = tr1.cells[2].innerText;
	var student_id=tr1.cells[3].innerText;
	var name =tr1.cells[4].innerText;
	var date=tr1.cells[5].innerText;
	var start=tr1.cells[6].innerText;
	var end=tr1.cells[7].innerText;
	var money=tr1.cells[8].innerText;

	document.getElementById('id').readonly=false;
	document.getElementById('id').value = modify_id;
	document.getElementById('id').readonly=true;

	document.getElementById('select').value = room_name;
	document.getElementById('unit').value = unit;
	
	document.getElementById('number').readonly=false;
	document.getElementById('number').value = student_id;
	document.getElementById('number').readonly=true;

	document.getElementById('name').readonly=false;
	document.getElementById('name').value = name;
	document.getElementById('name').readonly=true;

	document.getElementById('date').value = date;
	document.getElementById('start').value = start;
	document.getElementById('end').value = end;
	
	document.getElementById('money').value = money;
         //   alert(tr1.rowIndex);  
         //   alert(tr1.cells[0].childNodes[0].value); //获取的方法一  
//alert(tr1.cells[1].innerText);  
/*
// 通过以下方式找到table对象，在获取tr，td。然后获取td的html内容  
                var table = document.getElementById("tb1");//获取第一个表格  
          
                var child = table.getElementsByTagName("tr")[rowIndex - 1];//获取行的第一个单元格  
                  
                var text = child.firstChild.innerHTML;  
                window.alert("表格第" + rowIndex + "的内容为: " + text);  
*/
	//alert(modify_id);
}
</script>

<form action="admin_accept.php" method="post">

    <h1><center>申请修改</center></h1>

    <table class="grant">
        <thead>
        <tr>

<?php
$str=array("序号","会议室","单位","学号/工号","姓名","预约日期","开始","结束","费用","修改");

foreach ($str as $i=>$value)
{
	echo "<th>$value</th>\n";
}

?>
        </tr>
        </thead>
        

  <?php
	$time_now = time();

	$room_name = array();
	$sql = "SELECT id,room_name FROM mrbs_room ";
	$result = mysql_query($sql);

	while($row=mysql_fetch_row($result)) 
	{
		$room_name[$row[0]]=$row[1];
	}

            $sql="SELECT id, room_id,unit, student_id, name, start_time, end_time,money FROM mrbs_entry where status=1 and start_time>$time_now order by timestamp desc";
//	echo $sql;          
  $result= mysql_query($sql);
	    $sqlnum=mysql_num_rows($result);
	    if($sqlnum!=0)
	    {
		$sqlcount=0;
                while($row=mysql_fetch_row($result))
                {
		$sqlcount = $sqlcount +1;
	/*	
                    $room_id= $row[1];
                    $student_id= $row[2];
                    $name= $row[3];
                    $start_time= $row[4];
                    $end_time= $row[5];
	*/
                    echo "<tr>";
	      foreach($row as $id=>$value)
	{
		 if($id==1)
			echo "<td>$room_name[$value]</td>\n";
		else  if($id==5)
		{		 echo "<td>".date("Y-m-d",$value)."</td>\n";
                    			echo "<td>".date("H:i",$value)."</td>\n";
		}
		else if($id==6)
		{		 echo "<td>".date("H:i",$value)."</td>\n";
		}
		else  if($id==0)
			echo "<td>$sqlcount</td>\n";
		else 
			echo "<td>$value</td>";
	}
	                    echo "<td><input type='button' value='修改' id ='$row[0]' onclick='modify(this,id)'></td>\n";
	  
                    echo "</tr>\n";
	
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
        	<label for="name">会议室:</label>
        <select name="select" id="select" >

<?php
$sql = "SELECT id,room_name FROM mrbs_room   WHERE disabled = 0 ";
$result = mysql_query($sql);
while($row=mysql_fetch_row($result)) 
{
	echo "<option>$row[1]</option>";
}

?>

    </select>
    <br>
	<label for="unit" >单位:</label>
            	<input type="text" id="unit"   name="unit" value="" /> 
			<label for="name" >姓名:</label>
            	<input type="text" id="name" readonly  value="" /> 
            <label for="number" >学号:</label>
			<input type="tel" id="number" readonly value="" /> 
         <label for="date">日期:</label> 
            <input type="date" value=""  id="date" name="date"/>  
            <br> 
            <label for="start">开始时间:</label> 
            <input type="time" min ="06:00" max ="23:30" step="1800" id="start"  name="start"value=""  /> 
            <label for="end">结束时间:</label> 
            <input type="time" min ="06:00" max ="23:30" step="1800"  id="end"  name="end"  value=""  /> 
 	   <label for="money" >费用:</label>
			<input type="tel" id="money" name="money" value="" /> 

   	<br>
            <input type="submit" name="sub" value="提交" id="sub"  onclick="checkform()"  /> 
</div>




<?php

output_trailer();
 ?>