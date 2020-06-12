
<?php
//Header("Location: detail.php");
//if( $_SERVER['HTTP_REFERER'] == "" )
//{
//echo "由于涉及网络安全问题，请不要通过修改url跳转网页，谢谢！";
//exit(0);
//}
require_once "functions.inc.php";

// Check the user is authorised for this page
//checkAuthorised();

// print the page header
$pg="home";
print_header("home");
$sql = "SELECT COUNT(*)   FROM mrbs_room   WHERE disabled = 0 ";

$result = mysql_query($sql);

$row=mysql_fetch_row($result );
if($row[0]==0)
{
    echo "<center><h2><strong>会议室预约关闭</strong></h2></center>";
output_trailer();
  exit(0);
}
else{
   // echo "meeting room :$row[0]<br>";
}
?>

<script type="text/javascript">
    // function ChangeContent(var name, var capacity, var type, var equipment, var detail) {
function Change(name,capacity,type,equipment,detail,id,date) {
  document.getElementById("capacity").innerHTML = "人数:  "+ capacity;
  document.getElementById("name").innerHTML = name;
  document.getElementById("type").innerHTML = "类型:  "+type;
  document.getElementById("equipment").innerHTML = "设备:  "+equipment;
  document.getElementById("detail").innerHTML = "介绍:  "+detail;
  var href = "detail.php?room="+id+"&date="+date;
  document.getElementById("rooms").href = href;
  var image = "./img/slide/slide"+id+".jpg";
  document.getElementById("picture").setAttribute("src", image);
}

</script>



<?php
  $sql = "SELECT room_name,type,capacity,equipment ,id ,description FROM mrbs_room   WHERE disabled = 0 ";

  $result= mysql_query($sql);
  // $active = 1;
  $num = mysql_num_rows($result);
  $start = 0;
  $all = array();
  while($row=mysql_fetch_row($result)) {
    $all[$start] = array();
    $all[$start]["str"] = $row[0];
    $all[$start]["type"] = $row[1];
    $all[$start]["cap"] = $row[2];
    $all[$start]["eqp"] = $row[3];
    $all[$start]["id"] = $row[4];
    $all[$start]["detail"] = $row[5];
    $start = $start + 1;
  }
?>


<div class="content">
  <div class = "left">
    <div class = "left-text">
      <h4>
        <span class = "menu" > </span>
          &nbsp;&nbsp;&nbsp;会议室列表
      </h4>
      <br>
      <div class = "vertical-line">
    	   <ul>
           <?php
             for($start=0;$start<$num;$start++) {
               echo "<li class = 'list'>";
               echo "<span onMouseOver='this.className=\"liOver\"'
                           onMouseOut ='this.className=\"liout\"'
                           onMousedown ='this.className=\"lidown\"'
                           >";
               $name  = $all[$start]["str"];
               $type = $all[$start]["type"];
               $cap  = $all[$start]["cap"];
               $eqp  = $all[$start]["eqp"];
               $id   = $all[$start]["id"];
               $detail = $all[$start]["detail"];

               echo "<a onclick = 'Change(\"$name\",\"$cap\", \"$type\",\"$eqp\",\"$detail\",$id,$g_date)'>".
               $all[$start]["str"]."</a>";
               echo "</span>";
               echo "</li>";

             }
             ?>
        </ul>
      </div>
    </div>
  </div>

  <div class = "right">
    <div class = "right-text">
    <?php
    $start = 0;
    echo "<table>";
    echo"<tr>";
    echo "<td align='center'>";
    $image = "./img/slide/slide".$all[$start]["id"].".jpg";
    echo "<img id='picture' src = $image>";
    echo "</td>";
    echo "</tr>";
    echo"<tr>";
    echo "<td align='center'>";
    echo "<span id = 'name' class = 'name_capital'>".$all[$start]["str"]."
          </span>";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>";
    echo "<span id = 'capacity' class= 'lead'>人数:&nbsp;&nbsp;".$all[$start]["cap"]."
          </span>";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>";
    echo "<span id = 'type' class = 'lead'>类型:&nbsp;&nbsp;".$all[$start]["type"]."
          </span>";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>";
    echo "<span id = 'equipment' class = 'lead'>设备:&nbsp;&nbsp;".$all[$start]["equipment"]."
          </span>";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>";
    echo "<span id = 'detail' class = 'lead'>介绍:&nbsp;&nbsp;".$all[$start]["detail"]."
          </span>";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='center'>";
    $href = "detail.php?room=".$all[$start]["id"]."&date=".$g_date;
    echo "<a id = 'rooms' class='btn btn-large btn-primary' href=$href>查看会议室</a>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    ?>
    </div>
</div>
</div>

<?php
output_trailer();

?>
