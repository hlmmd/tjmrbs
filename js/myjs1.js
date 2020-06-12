  function Accept(accept_id)
    {
	if(!confirm("确定要同意吗？")){  
	return false;   
	}   
	var xmlhttp;
        if(window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
        else
            xmlhttp=new ActiveObject("Microsoft.XMLHTTP");
        xmlhttp.open("GET", "/Ajax/Accept.php?accept_id="+accept_id);
        xmlhttp.send();
        xmlhttp.onreadystatechange=function()
        {
            if(xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                eval(xmlhttp.responseText);
            }
        }
    }

    function Reject(reject_id)
    {
	if(!confirm("确定要拒绝吗？")){  
	return false;   
	}   
        var xmlhttp;
        if(window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
        else
            xmlhttp=new ActiveObject("Microsoft XMLHTTP");
        xmlhttp.open("GET", "/Ajax/Reject.php?reject_id="+reject_id);
        xmlhttp.send();
        xmlhttp.onreadystatechange=function()
        {
            if(xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                eval(xmlhttp.responseText);
            }
        }
    }

    function Exit()
    {
        var xmlhttp;
        if(window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
        else
            xmlhttp=new ActiveObject("Microsoft XMLHTTP");
        xmlhttp.open("GET", "/Ajax/Exit.php");
        xmlhttp.send();
        xmlhttp.onreadystatechange=function()
        {
            if(xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                eval(xmlhttp.responseText);
            }
        }
    }

  function Disable(disable_id)
    {
	if(!confirm("确定要停用吗？")){  
	return false;   
	}   
	var xmlhttp;
        if(window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
        else
            xmlhttp=new ActiveObject("Microsoft.XMLHTTP");
        xmlhttp.open("GET", "/Ajax/Disable.php?disable_id="+disable_id);
        xmlhttp.send();
        xmlhttp.onreadystatechange=function()
        {
            if(xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                eval(xmlhttp.responseText);
            }
        }
    }

  function Enable(enable_id)
    {
	if(!confirm("确定要启用吗？")){  
	return false;   
	}   
	var xmlhttp;
        if(window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
        else
            xmlhttp=new ActiveObject("Microsoft.XMLHTTP");
        xmlhttp.open("GET", "/Ajax/Enable.php?enable_id="+enable_id);
        xmlhttp.send();
        xmlhttp.onreadystatechange=function()
        {
            if(xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                eval(xmlhttp.responseText);
            }
        }
    }

  function Delete(delete_id)
    {
	if(!confirm("确定要删除吗？")){  
	return false;   
	}   
	var xmlhttp;
        if(window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
        else
            xmlhttp=new ActiveObject("Microsoft.XMLHTTP");
        xmlhttp.open("GET", "/Ajax/Delete.php?delete_id="+delete_id);
        xmlhttp.send();
        xmlhttp.onreadystatechange=function()
        {
            if(xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                eval(xmlhttp.responseText);
            }
        }
    }

    function Opentime(room_id,starttime,endtime)
    {
	if(!confirm("确定要开放该时段吗？")){  
	return false;   
	}   
	var xmlhttp;
        if(window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
        else
            xmlhttp=new ActiveObject("Microsoft.XMLHTTP");
        xmlhttp.open("GET", "/Ajax/Opentime.php?room_id="+room_id+"&starttime="+starttime+"&endtime="+endtime);
        xmlhttp.send();
        xmlhttp.onreadystatechange=function()
        {
            if(xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                eval(xmlhttp.responseText);
            }
        }
    }

    function Closetime(room_id,starttime,endtime)
    {
	if(!confirm("确定要关闭该时段吗？")){  
	return false;   
	}   
	var xmlhttp;
        if(window.XMLHttpRequest)
            xmlhttp=new XMLHttpRequest();
        else
            xmlhttp=new ActiveObject("Microsoft.XMLHTTP");
        xmlhttp.open("GET", "/Ajax/Closetime.php?room_id="+room_id+"&starttime="+starttime+"&endtime="+endtime);
        xmlhttp.send();
        xmlhttp.onreadystatechange=function()
        {
            if(xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                eval(xmlhttp.responseText);
            }
        }
    }
