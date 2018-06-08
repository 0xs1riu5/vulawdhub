<?php
require_once ("admin.inc.php");
$userid		 = trim($_GET ['userid'])?trim($_GET ['userid']):0;
$act			 = trim($_GET ['act'])?trim($_GET ['act']):'add';
$actName = $act == 'add'?'添加':'修改';
$users = $db->getOneRow ( "select * from cms_users where userid=" . $userid );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="images/css.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="user.action.php" method="post">
  <input type="hidden" name="act" value="<?php echo $act;?>">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="form_title">
    <tr>
      <td height="31"><strong><?php echo $actName;?>账号</strong></td>
    </tr>
  </table>
  <table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0" class="table_list">
    <tr>
      <td width="200" align="right" class="form_list">用户名：</td>
      <td height="26" class="form_list"><?php
	if (empty ($users['username'] )) {
		?>
        <input name="username" type="text" style="width: 200px">
        <?php
	} else {
		echo $users['username'];
	}
	?></td>
    </tr>
    <tr>
      <td align="right" class="form_list">密码：</td>
      <td height="26" class="form_list"><input name="password" type="password"
			style="width: 200px"></td>
    </tr>
    <tr>
      <td align="right" class="form_list">重复密码：</td>
      <td height="26" class="form_list"><input name="password2" type="password"
			style="width: 200px"></td>
      </td>
    </tr>
  </table>
  <table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0">
    <tr>
      <td height="29" class="form_footer" style="text-align: left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input
			type="submit" name="button" id="button" value="<?php echo $actName;?>用户">
        <input
			type="button" onClick="window.history.go(-1)" value="返回" />
        <input
			type="hidden" name="userid" value="<?php echo $userid;?>"></td>
    </tr>
    <tr>
      <td height="3" background="admin/images/20070907_03.gif"></td>
    </tr>
  </table>
</form>
</body>
</html>