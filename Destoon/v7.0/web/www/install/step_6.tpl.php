<?php
defined('IN_DESTOON') or exit('Access Denied');
include IN_ROOT.'/header.tpl.php';
?>
<div class="head">
	<div>
		<strong>系统安装成功</strong><br/>
		恭喜！您已经成功安装DESTOON B2B网站管理系统
	</div>
</div>
<div class="body">
<div>
	<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#DDDDDD">
	<tr>
	<td bgcolor="#F1F1F1" width="120">网站后台地址</td>
	<td bgcolor="#FFFFFF"><a href="<?php echo $url;?>admin.php"><?php echo $url;?>admin.php</a></td>
	</tr>
	<tr>
	<td bgcolor="#F1F1F1">管理员户名</td>
	<td bgcolor="#FFFFFF"><?php echo $username;?></td>
	</tr>
	<tr>
	<td bgcolor="#F1F1F1">管理员密码</td>
	<td bgcolor="#FFFFFF"><?php echo $password;?> <em>(请妥善保存)</em></td>
	</tr>
	</table>
	<br/><br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;非常感谢选择DESTOON B2B产品<br/><br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;更多产品相关信息，敬请关注 <a href="http://www.destoon.com" target="_blank">www.destoon.com</a>
</div>
</div>
<div class="foot">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width="220">
<div class="progress">
<div id="progress"></div>
</div>
</td>
<td id="percent"></td>
<td height="40" align="right">
<input type="button" value="登录后台" onclick="window.location='../admin.php';"/>
<input type="button" value="网站首页" onclick="window.location='../';"/>
<?php
include IN_ROOT.'/footer.tpl.php';
?>