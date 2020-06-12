<?php
if( $_SERVER['HTTP_REFERER'] == "" )
{
echo "由于涉及网络安全问题，请不要通过修改url跳转网页，谢谢！";
exit(0);
}
require_once "functions.inc.php";

// Check the user is authorised for this page
//checkAuthorised();

// print the page header
$pg="qa";
print_header("qa");
?>
<div style="width:1000px;margin:0px auto;">
<h3 >预约流程</h3>



<p style="margin-left:40px;">1、推荐使用Chrome浏览器进行场地预约；</p>
<p style="margin-left:40px;">2、预约后相应区域显示蓝色状态为“已预约未审核”状态；</p>
<p style="margin-left:40px;">3、下载<a href="file/大学生活动中心场地使用申请表.doc">大学生活动中心场地使用申请表</a>，单位负责人签字盖章后，请至学生事务中心（大学生活动中心100室）就业中心审核，审核后相应区域会显示为绿色“预约成功”状态；</p>
<p style="margin-left:40px;">4、就业中心审核盖章后，请将下联交至经纬楼三楼办公室（演讲厅斜对面），作为场地使用的依据。</p>


<h3 >使用规定：</h3>
<p style="margin-left:40px;">1、请提前7天申请预约，仅开放一个月之内的场地申请；</p>
<p style="margin-left:40px;">2、请遵循合理场地使用原则，如实际到场人数少于申报人数50% ;</p>
<p style="margin-left:40px;">3、建议使用人数：100-200人/演讲厅（报告厅），20-50人/第三会议室，30-60人/多功能厅；</p>
<p style="margin-left:40px;">4、请爱护场地内设施，场地内不可食用食物，墙面不可张贴海报等宣传与布置物品；</p>
<p style="margin-left:40px;">5、请根据预约时间使用场地，确保活动符合学校管理规定，确保活动安全有序；</p>
<p style="margin-left:40px;">6、如场地预约后与学校重要活动冲突，请服从场地管理方安排；</p>
<p style="margin-left:40px;">7、单位负责人签字并盖章后，请至大学生活动中心212室就业中心审核。</p>


<br>



</div>

<?php

output_trailer();

?>