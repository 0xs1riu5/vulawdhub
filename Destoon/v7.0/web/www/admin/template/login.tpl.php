<?php 
defined('DT_ADMIN') or exit('Access Denied');
$edition = edition(1);
?>
<!doctype html>
<html lang="<?php echo DT_LANG;?>">
<head>
<meta charset="<?php echo DT_CHARSET;?>"/>
<meta name="robots" content="noindex,nofollow"/>
<title>管理员登录 - Powered By DESTOON B2B <?php echo $edition;?></title>
<meta name="generator" content="DESTOON B2B,www.destoon.com"/>
<link rel="stylesheet" href="admin/image/login.css" type="text/css" />
<script type="text/javascript" src="<?php echo DT_STATIC;?>lang/<?php echo DT_LANG;?>/lang.js"></script>
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/config.js"></script>
<!--[if lte IE 9]><!-->
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/jquery-1.5.2.min.js"></script>
<!--<![endif]-->
<!--[if (gte IE 10)|!(IE)]><!-->
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/jquery-2.1.1.min.js"></script>
<!--<![endif]-->
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/common.js"></script>
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/keyboard.js"></script>
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/md5.js"></script>
</head>
<body>
<noscript><br/><br/><br/><center><h1>您的浏览器不支持JavaScript,请更换支持JavaScript的浏览器</h1></center></noscript>
<noframes><br/><br/><br/><center><h1>您的浏览器不支持框架,请更换支持框架的浏览器</h1></center></noframes>
<form method="post" action="?"  onsubmit="return Dcheck();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input name="forward" type="hidden" value="<?php echo $forward;?>"/>
<div class="login">
	<div id="msgs"></div>
	<div class="head">管理登录</div>
	<div class="main">
		<div><input name="username" type="text" id="username" placeholder="户名" value="<?php echo $username;?>"/></div>
		<div><input name="password" type="password" id="password" placeholder="密码" value="" ondblclick="kb_s('password', 'kb');" title="双击弹出密码键盘"/></div>
		<?php if($DT['captcha_admin']) { ?>
		<div><?php include template('captcha', 'chip');?></div>
		<?php } ?>
		<div><input type="submit" name="submit" value="登 录" tabindex="4" id="sbm"/><input type="button" id="btn" value="退 出" onclick="top.Go('<?php echo DT_PATH;?>');"/></div>
	</div>
</div>
</form>
<div id="tips"></div>
<div id="kb" style="display:none;"></div>
<script type="text/javascript">
function Dmsgs(msg) {
	$('#tips').hide();
	$('#sbm').attr('disabled', true);
	$('#msgs').html(msg);
	$('#msgs').slideDown(100, function() {
		setTimeout(function() {$('#msgs').fadeOut(300);$('#sbm').attr('disabled', false);}, 3000);
	});
}
function Dcheck() {
	if(Dd('username').value.length < 2) {
		Dmsgs('请填写会员名');
		Dd('username').focus();
		return false;
	}
	if(Dd('password').value.length < 6) {
		Dmsgs('请填写密码');
		Dd('password').focus();
		return false;
	}
	<?php if($DT['captcha_admin']) { ?>
	if($('#ccaptcha').html().indexOf('ok.png') == -1) {
		Dmsgs('请填写验证码');
		Dd('captcha').focus();
		return false;
	}
	<?php } ?>
	return true;
}
$(function(){
	if(Dd('username').value == '') {
		Dd('username').focus();
	} else {
		Dd('password').focus();
	}
	init_md5();
	if(window.screen.width < 1200) {
		setTimeout(function() {
			$('#tips').hide();
			$('#tips').html(window.screen.width+'px屏幕无法获得最佳体验，建议1200px以上');
			$('#tips').slideDown(600);
		}, 5000);
	}
<?php if($DT['captcha_admin']) { ?>
	$('#captcha').css({'margin':'0 10px 0 0'});
<?php } ?>
<?php if(strpos(get_env('self'), '/admin.php') !== false) { ?>
	$('#tips').html('提示：为了系统安全，请修改后台地址 &nbsp;<a href="https://www.destoon.com/doc/use/34.html" target="_blank">帮助&#187;</a>');
	$('#tips').slideDown(600);
<?php } ?>
});
</script>
</body>
</html>