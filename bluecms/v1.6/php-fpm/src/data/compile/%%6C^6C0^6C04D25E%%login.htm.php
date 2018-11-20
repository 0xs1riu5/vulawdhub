<?php /* Smarty version 2.6.22, created on 2018-11-20 09:50:02
         compiled from login.htm */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.gor/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
" />
<title><?php echo $this->_tpl_vars['current_act']; ?>
 - <?php echo $this->_tpl_vars['site_name']; ?>
 - Powered by BlueCMS</title>
<meta name="Description" content="<?php echo $this->_tpl_vars['description']; ?>
" />
<meta name="Keywords" content="<?php echo $this->_tpl_vars['keywords']; ?>
" />
<link rel="shortcut icon" href="/images/favicon.ico" />
<link href="templates/default/css/main.css" rel="stylesheet" type="text/css" />
<script src="templates/default/css/common.js" type="text/javascript"></script>
</head>
<body>
<div id="top_nav">
	<div id="top_nav_left">
		<ul>
		<li>您好,欢迎您的访问!</li>
		<?php if ($this->_tpl_vars['user_name']): ?>
		<li><font style="color:#ff6600;font-weight:bold;"><?php echo $this->_tpl_vars['user_name']; ?>
</font></li>
		<li><a href="user.php?act=logout">退出</a></li>
		<?php else: ?>
		<li><a href="user.php?act=login">登录</a></li>
		<li><a href="user.php?act=reg">免费注册</a></li>
		<?php endif; ?>
		</ul>
	</div>
	<div id="top_nav_right">
	<ul><li><a href="JavaScript:" onClick="var strHref=window.location.href;this.style.behavior='url(#default#homepage)';this.setHomePage('<?php echo $this->_tpl_vars['site_url']; ?>
');">设为首页</a></li><li><a href="javascript:window.external.AddFavorite(location.href, document.title)">加入收藏</a></li></ul>
	</div>
</div>
<div class="wapper">
	<div id="top"><a href="./" target="_self" class="logo"><img src="templates/default/images/logo.gif" alt="<?php echo $this->_tpl_vars['site_name']; ?>
" border="0" /></a>
	</div>
	<div class="clear"></div>
	<div class="active_act">
		您所在的位置：<a href="./">首页</a> &raquo; <?php echo $this->_tpl_vars['current_act']; ?>
	</div>
	<div class="clear"></div>

	<div id="content">
           <form name="login_form" method="post" action="user.php" target="_self" onsubmit="return check_form(this)" id="reg_form" >
		   		<h2>填写登录信息<span>如果您还没有注册，点这里<a href="user.php?act=reg">注册</a>!</span></h2>
		   		<input type="hidden" name="referer" value="" />
					  <table height="200">
						<tr>
							<td style="text-align:right;width:130px;">用户名：</td>
							<td><input type="text" id="user_name" name="user_name" class="inputbox" maxlength="20" /></td>
						</tr>
						<tr>
							<td style="text-align:right;width:130px;">密码：</td>
							<td><input type="password" name="pwd" class="inputbox" /><span id="check_pwd_warning" ></span></td>
						</tr>
						<tr>
							<td style="text-align:right;width:130px;">验证码：</td>
							<td><input type="text" name="safecode" class="inputbox"/> <img id="safecode" onclick="this.src=this.src+'?'" src="include/safecode.php" border=0  height=24 alt="看不清? 点击更换验证码" style="vertical-align:middle" /></td>
						</tr>
						<tr>
						<td style="text-align:right;width:130px;">有效期：</td>
						<td><input type="radio" name="useful_time" value="86400" id="t1" /><label for="t1">一天</label>
							<input type="radio" name="useful_time" value="604800" id="t2" checked="checked" /><label for="t2">一个星期</label>
							<input type="radio" name="useful_time" value="2592000" id="t3" /><label for="t3">一个月</label>
							<input type="radio" name="useful_time" value="0" id="t4" /><label for="t4">浏览器进程</label>
							</td>
						</tr>
						<tr style="height:25px;">
							<td style="text-align:right;width:130px;padding-top:10px;">
							<input type="submit" name="submit" value="登录" />
							<input type="hidden" name="from" value="<?php echo $this->_tpl_vars['from']; ?>
" />
							<input type="hidden" name="act" value="do_login" /></td>
						</tr>
					</table>
		 	</form>
	</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
function checkform(){

}
</script>
</div>
</body>
</html>