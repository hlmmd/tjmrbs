<!DOCTYPE html>
<html >
<head>
<title>登录</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style_login2.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript">

function check()
{
  var xmlhttp;
  if(window.XMLHttpRequest)
      xmlhttp=new XMLHttpRequest();
  else
      xmlhttp=new ActiveObject("Microsoft.XMLHTTP");
  xmlhttp.open("GET", "/Ajax/Check.php");
  xmlhttp.send();
  xmlhttp.onreadystatechange=function()
  {
      if(xmlhttp.readyState==4 && xmlhttp.status==200)
      {
          eval(xmlhttp.responseText);
      }
  }
}

function Validate()
{
  var xmlhttp;
  var name=document.getElementById("name").value;
  var password=document.getElementById("password").value;
  if(window.XMLHttpRequest)
      xmlhttp=new XMLHttpRequest();
  else
      xmlhttp=new ActiveObject("Microsoft.XMLHTTP");
  xmlhttp.open("GET", "/Ajax/Validate.php?name="+name+"&password="+password);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function()
  {
      if(xmlhttp.readyState==4 && xmlhttp.status==200)
      {
          eval(xmlhttp.responseText);
      }
  }
}

function Modify()
{
    document.getElementById('Validate').innerHTML="";
}

document.onkeydown = function(e){
    if((e||event).keyCode==13)
        Validate();
};
</script>
</head>
<body onload="check()">
<div class="registration admin_agile">
    <div class="signin-form profile admin">
        <h2>管理员登录</h2>
        <div class="login-form">
            <form action="#" method="post">
                <div style="font-size:24px;text-align:left;"><label >用户名</label></div>
                <input type="text"  maxlength="16" name="name"  id="name" onclick="Modify()" required>
                <div style="font-size:24px;text-align:left;"><label >密码</label></div>
                <input type="password" name="number" id="password" maxlength="16"  onclick="Modify()" required>
                <p id="Validate"></p>
                    <input type="button" name ="sub" id ="sub"  value="登录" onclick="Validate()">
            </form>
        </div>
         <h6><a href="root.php">返回</a><h6>
    </div>
</div>

</body>
</html>