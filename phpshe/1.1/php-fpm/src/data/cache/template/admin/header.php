<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $seo['title'] ?></title>
<meta name="keywords" content="<?php echo $seo['keywords'] ?>" />
<meta name="description" content="<?php echo $seo['description'] ?>" />
<link type="text/css" rel="stylesheet" href="<?php echo $pe['host_tpl'] ?>css/style.css" />
<script type="text/javascript" src="<?php echo $pe['host_root'] ?>include/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $pe['host_root'] ?>include/js/global.js"></script>
</head>
<body>
<div class="bgimg"></div>
<div class="pagetop">
	<div class="head">
		<div class="logo fl"><img src="<?php echo $pe['host_tpl'] ?>images/logo.gif" /></div>
		<div class="head_r fr">
			<a href="<?php echo $pe['host_root'] ?>" target="_blank" class="home">网站首页</a>
			<a href="admin.php?mod=do&act=logout" class="exit">注销</a>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="content">
	<div class="main">
		<div class="left">
			<?php foreach($adminmenu as $k=>$v):?>
			<div class="fenlei">
				<h3><?php echo $v['headnav'] ?></h3>
				<ul>
					<?php foreach($v['subnav'] as $vv):?>
					<li <?php if($vv['menumark']==$menumark):?>class="sel"<?php endif;?>><a href="<?php echo $vv['url'] ?>"><?php echo $vv['name'] ?></a></li>
					<?php endforeach;?>
				</ul>
			</div>
			<?php endforeach;?>
			<div class="fenlei c666">
				<h3>软件信息</h3>
				<div class="mat5" style="background:#f3f3f3; border:1px #eaeaea solid; padding:5px 0 5px 2px;">
					<p>软件版本：<u><a target="_blank" href="http://www.phpshe.com/phpshe" class="c666">phpshe1.1</a></u></p>
					<p>开发团队：<u><a target="_blank" href="http://www.phpshe.com" class="c666">简好技术</a></u></p>
					<p>邮箱：<u>admin@phpshe.com</u></p>
					<p>企鹅：<u>1318321、1517735</u></p>
				</div>
			</div>
		</div>