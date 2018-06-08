<?php
require_once ("admin.inc.php");
$act = $_GET ['act'];
$hlink = $_GET ['hlink'];//历史链接
$actName = '修改密码';
$users = $db->getOneRow ( "select * from cms_users where userid=" . $_COOKIE['userid'] );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="images/css.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="document.getElementById('oldpassword').select()">
<form action="user.action.php" method="post">
<input type="hidden" name="act" value="<?php echo $act;?>">
<input type="hidden" name="hlink" value="<?php echo $hlink;?>">
<table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0" class="table_head">
	<tr>
		<td height="30"><?php echo $actName;?></td>
    </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0" class="table_list">
  <tr class="row">
		<td width="200" align="right" class="table_form">用户名：</td>
	  <td height="26" class="table_form">
	<?php
	echo $users['username'];
	?>	  </td>
	</tr>
	<tr class="row">
	  <td align="right" class="table_form">原密码：</td>
	  <td height="26" class="table_form"><input name="oldpassword" type="password" style="width: 200px"></td>
    </tr>
	<tr class="row">
		<td align="right" class="table_form">新密码：</td>
	  <td height="26" class="table_form"><input name="password" type="password"
			style="width: 200px"></td>
	</tr>
	<tr class="row">
		<td align="right" class="table_form">重复新密码：</td>
	  <td height="26" class="table_form"><input name="password2" type="password"
			style="width: 200px"></td>
		</td>
	</tr>
</table>

<table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0">
	<tr>
		<td height="29" class="table_footer" style="text-align: left">
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input
			type="submit" name="button" id="button" value="<?php echo $actName;?>"> <input
			type="button" onClick="window.history.go(-1)" value="返回" /> 
			</td>
	</tr>
	<tr>
		<td height="3" background="admin/images/20070907_03.gif"></td>
	</tr>
</table>
</form>
</body>
</html>