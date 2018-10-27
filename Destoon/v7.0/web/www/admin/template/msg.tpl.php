<?php 
defined('DT_ADMIN') or exit('Access Denied');
?>
<!doctype html>
<html lang="<?php echo DT_LANG;?>">
<head>
<meta charset="<?php echo DT_CHARSET;?>"/>
<meta name="robots" content="noindex,nofollow"/>
<title>提示信息 - Powered By Destoon <?php echo DT_VERSION; ?></title>
<link rel="stylesheet" href="admin/image/msg.css" type="text/css" />
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/config.js"></script>
</head>
<body onkeydown="if(event.keyCode==13) window.history.back();">
<div class="msg">
	<div class="head">提示信息</div>
	<div class="main"><?php echo $msg;?></div>	
	<?php if($forward == "goback") { ?>
	<a href="javascript:window.history.back();"><div class="foot">点这里返回上一页</div></a>
	<?php } elseif ($forward) {?>
	<a href="<?php echo $forward;?>"><div class="foot">如果您的浏览器没有自动跳转，请点击这里</div></a>
	<meta http-equiv="refresh" content="<?php echo $time;?>;URL=<?php echo $forward;?>">
	<?php } ?>
</div>
</body>
</html>