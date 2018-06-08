<?php
if(isset($_COOKIE['username'])){
	$username = $_COOKIE['username'];
}else{
	$username="";
}
$finput=empty($username)?"username":"password";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台登陆</title>
<link href="images/css.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.font_12 {
	font-size: 12px;
	color: #574A17;
}
.font_121 {	font-size: 12px;
}
.login_gb {	background-image: url(images/login_img_02.gif);
	background-repeat: no-repeat;
	height: 164px;
	width: 492px;
	vertical-align: baseline;
}
body {
	background-color: #DAEEFE;
}
</style>
<script type="text/javascript">
function init(){
	document.getElementById('<?php echo $finput;?>').select();
	document.getElementById('<?php echo $finput;?>').focus();
}
</script>
</head>
<body onLoad="init()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100" align="center" valign="bottom"></td>
  </tr>
  <tr>
    <td height="10" align="center" valign="bottom"></td>
  </tr>
</table>
  <form action="login.action.php" method="post">
<table width="492" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="images/login_img_01.gif" width="492" height="134" /></td>
  </tr>
  <tr>
    <td class="login_gb"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="26%" height="40" align="right" valign="middle" class="font_121">用户名：</td>
        <td width="42%" valign="middle">
        <input name="username" type="text" class="form" id="username" value="<?php echo $username;?>" style="width:160px"></td>
        <td width="32%" rowspan="2" valign="middle">
        <input name="image" type="image" style="width:85px; height:64px; border:0px"  tabindex="4" src="images/login_img_06.gif"/></td>
      </tr>
      <tr>
        <td height="40" align="right" valign="middle"class="font_121">密&nbsp;&nbsp;码：</td>
        <td valign="middle"><input name="password" type="password" class="form" id="password" style="width:160px"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>
