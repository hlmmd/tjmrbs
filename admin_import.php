<?php

//防止从url直接访问本文件
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "nothing here.";
exit(0);
}
require_once "functions.inc.php";

checkright();

print_admin("import");


?>

<script type="text/javascript">
</script>
<?php
//	echo $new_num." ".$conflict_num." ".$wrongplace_num;
?>

<form action="upload_file.php" method="post"
enctype="multipart/form-data">
<label for="file">导入xls或者xlsx文件:</label>
<input type="file" name="file" id="file" /> 
<br />
<input type="submit" name="submit" value="提交" />
</form>


<?php
output_trailer();
 ?>