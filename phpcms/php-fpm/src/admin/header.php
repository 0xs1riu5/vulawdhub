<?php
include_once 'admin.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="images/css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function editPassword(){
	//alert('演示系统不能修改');	
	parent.mainFrame.location="user.editpwd.php?act=editpwd&hlink="+encodeURIComponent(parent.mainFrame.location);
}
</script>
<style type="text/css">
<!--
body {
	background-color: #E6F3FC;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
-->
</style><body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="page_top_bg">
  <tr>
    <td width="162" height="40" align="center" valign="top"><img src="images/logo.gif" style="cursor:pointer" onclick="parent.window.location='index.php'" /></td>
    <td align="right" style="padding-right:20px; padding-top:0px; font-size:12px">
		<script type="text/javascript">
        <!--
        function thisYear(){ return new Date().getYear();}
        function thisMonth(){ return new Date().getMonth();}
        function thisDay(){ return new  Date().getDate(); }
        function CurentTime(){  
            var  now =  new  Date();  
            var  hh  =  now.getHours();  
            var  mm  =  now.getMinutes();  
            var  ss  =  now.getTime()  %  60000;  
            ss  =  (ss - (ss  %  1000)) / 1000;  
            var  clock  =  hh+':';  
            if  (mm  <  10)  clock  +=  '0';  
            clock  +=  mm+':';  
            if  (ss  <  10)  clock  +=  '0';  
            clock  +=  ss;  
            return(clock);  
        }  
        function  refreshCalendarClock(){  
            document.all.calendarClock1.innerHTML  =  thisYear()+"-";  
            document.all.calendarClock2.innerHTML  =  thisMonth()+"-";  
            document.all.calendarClock3.innerHTML  =  thisDay()+"&nbsp;";  
            document.all.calendarClock4.innerHTML  =  CurentTime();  
        }
        document.write('<font id="calendarClock1"></font>');
        document.write('<font id="calendarClock2"></font>');
        document.write('<font id="calendarClock3"></font>');
        document.write('<font id="calendarClock4"></font>');
        setInterval('refreshCalendarClock()',200);
        //-->
        </SCRIPT>
        &nbsp;&nbsp;&nbsp;
      你好：<strong><?php echo $_COOKIE['username'];?></strong>&nbsp;，欢迎使用CMS&nbsp; 
      <a style="color:#25F" href="../index.php" target="_blank"><strong>网站主页</strong></a>&nbsp; 
      <a style="color:#25F" href="javascript:editPassword()"><strong>修改密码</strong></a>&nbsp; 
      <a style="color:#25F" href="login.out.php"><strong>注销系统</strong></a>&nbsp; 
    </td>
  </tr>
</table>
</body>
</html>

