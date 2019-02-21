<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview();
if(empty($action))
{
	$action = '';
}


if($action=="delall")
{
	$dsql->ExecuteNoneQuery("UPDATE sea_type SET unionid=''");
	ShowMsg("清除所有采集分类绑定!","admin_delunionid.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>清除采集分类绑定</title>
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<link  href="img/style.css" rel="stylesheet" type="text/css" />
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<script type="text/JavaScript">if(parent.$('admincpnav')) parent.$('admincpnav').innerHTML='后台首页&nbsp;&raquo;&nbsp;管理员&nbsp;&raquo;&nbsp;资源库管理 ';</script>
<div class="r_main">
  <div class="r_content">
    <div class="r_content_1">
	

	
	
	
<form action="?action=delall" method="post">	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_style">
<tbody><tr class="thead">
<td colspan="5" class="td_title">清除采集分类绑定</td>
</tr>
<tr>
<td width="80%" align="left" height="30" class="td_border">
使用此功能可以一键清除所有资源库的分类绑定，请谨慎操作。清除绑定后，无法采集入库。
</td>
</tr>


<tr>
<td width="10%" align="left" height="30" class="td_border">
<input type="submit" value="清除所有采集分类绑定" class="btn" >
</td>
</tr>

</tbody></table>	
	
</form>
</div>
	</div>
</div>
<?php
viewFoot();
?>
</body>
</html>