<?php if(!defined('HDWIKI_ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" id="html">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo WIKI_CHARSET?>" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?php echo $navtitle?> <?php echo $setting['site_name']?> <?php echo $setting['seo_title']?> - Powered by HDWiki!</title>
<?php echo $setting['seo_headers']?>

<meta name="keywords" content="<?php echo $setting['seo_keywords']?>" />
<meta name="description" content="<?php echo $setting['seo_description']?>" />
<meta name="generator" content="HDWiki <?php echo HDWIKI_VERSION?>" />
<meta name="author" content="HDWiki Team and Hudong UI team" />
<meta name="copyright" content="2005-2010 Hudong.com" />
<?php if($docrewrite=='1') { ?><base href="<?php echo WIKI_URL?>/" /><?php } ?>
<link href="style/<?php echo $theme?>/hdwiki.css" rel="stylesheet" type="text/css" media="all"/>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="lang/<?php echo $setting['lang_name']?>/front.js"></script>
<script type="text/javascript" src="js/jquery.dialog-0.8.min.js"></script>
<script type="text/javascript" src="js/login.js"></script>
<script type="text/javascript">
$.dialog.setConfig('base', '<?php echo WIKI_URL?>/style/default');
var g_isLogin, g_isUcenter=false, g_forward = '',g_api_url='', g_regulars = '', g_uname_minlength, g_uname_maxlength;
<?php if($user['groupid']=='1') { ?>
g_regulars = "<?php echo $header_regulars?>";
g_isLogin = false;
<?php } else { ?>
g_isLogin = true;
<?php } ?>
<?php if(isset($pp_api)) { ?>
g_api_url = '<?php echo $pp_api?>';
<?php } ?>
<?php if($isUcenter) { ?>
g_isUcenter = true;
<?php } ?>
g_seo_prefix = "<?php echo $setting['seo_prefix']?>";
g_seo_suffix = "<?php echo $setting['seo_suffix']?>";
g_uname_minlength = "<?php echo $setting['name_min_length']?>"||3;
g_uname_maxlength = "<?php echo $setting['name_max_length']?>"||15;
<?php if($newpms[0]) { ?>
	var titlestate = 0, clock, flashingtime = 20;
	var oldtitle = "<?php echo $navtitle?> <?php echo $setting['site_name']?> <?php echo $setting['seo_title']?> - Powered by HDWiki!";
	function changeTitle(){
		if(titlestate%2 == 0){
			document.title='【新消息】'+oldtitle;
		}else{
			document.title='【　　　】'+oldtitle;
		}
		titlestate++;
		if(titlestate == flashingtime){
			clearInterval(clock);
			document.title = oldtitle;
		}
	}
	clock = setInterval("changeTitle()", 1000);

<?php } ?>
</script>
</head>
<body>
<!--编辑异常中断，下次登录提示-->
<?php if($unpubdoc) { ?>
<div class="edit_tips a-r" id="unpubdoc">
<span class="bold l">你上次编辑的词条“<label class="red"><?php echo $unpubdoc['title']?></label>”还未发布，赶快去处理吧！
	<input name="Button1" type="button" value="继续编辑" onclick="location.href='index.php?doc-edit-<?php echo $unpubdoc['did']?>'"/></span>
	<span class="close r" onclick='$("#unpubdoc").hide()'>×</span>
</div>
<?php } ?>

<ul id="login" class="w-950 bor_b-ccc"> 
<?php if(count($channellist[1])!=0) { ?>
<?php foreach((array)$channellist[1] as $channel) {?>
<li class="l bor_no"><a href="<?php echo $channel['url']?>" target="_blank"><?php echo $channel['name']?></a></li>
<?php } ?>
<?php } ?>
<?php if($user['groupid']=='1') { ?>
<li name="login"><a href="index.php?user-login">登录</a></li>
<li name="register" class="bor_no"><a href="index.php?user-register" >注册</a></li>
<?php } else { ?>
	<li class="bor_no pad10">欢迎你，<a href="index.php?user-space-<?php echo $user['uid']?>"><?php echo $user['username']?></a></li>
	<?php if($user['password']!='') { ?>
	<li><a href="
	<?php if($newpms[3]) { ?>
		index.php?pms-box-inbox-system
	<?php } else { ?>
		index.php?pms
	<?php } ?>
	" id="header-pms">
	<?php if($newpms[0]) { ?>
	<span class="h_msg">（<?php echo $newpms[0]?>）</span>
	<?php } else { ?>
	<img alt="HDWiki" src="style/default/noshine.gif"/>
	<?php } ?></a></li>
	<li><a  href="index.php?user-profile">个人管理</a></li>
	<?php if($adminlogin ) { ?><li><a href="index.php?admin_main">系统设置</a></li><?php } ?>
	<li class="bor_no"><a href="index.php?user-logout<?php echo $referer?>" >退出</a></li>
	<?php } else { ?>
	<li><a href="index.php?user-login" >待激活</a></li>
	<li class="bor_no"><a href="index.php?user-logout<?php echo $referer?>" >退出</a></li>
	<?php } ?>
<?php } ?>
<li class="bor_no help"><a href="index.php?doc-innerlink-<?php echo urlencode('帮助')?>">帮助</a></li>
</ul>
<div class="bg_book">
	<a href="<?php echo WIKI_URL?>" id="logo"><img alt="HDWiki" width="<?php echo $setting['logowidth']?>" src="style/default/logo.gif"/></a>
	<?php if($isimage ) { ?>
	<form name="searchform" method="post" action="index.php?pic-search">
	<p id="search">
	<input name="searchtext" type="text" class="btn_txt"  maxlength="80" size="42"  value="<?php if(isset($searchtext)) { ?><?php echo $searchtext?><?php } ?>"/>
	<input name="searchfull" type="submit" value="图片搜索"class="btn_inp img_sea_inp" />
	</p>
	</form>
	<?php } else { ?>
	<form name="searchform" method="post" action="index.php?search-kw">
	<p id="search">
	<?php if($cloudsearchhead) { ?>
	<input name="searchtext" class="btn_txt" maxlength="80" size="42" value="<?php if(isset($searchtext)) { ?><?php echo $searchtext?><?php } ?>" type="text"/>
	<input name="search" value="搜 索" tabindex="1" class="btn_inp sea_doc"  type="submit"/>
	<?php } else { ?>
	<input name="searchtext" class="btn_txt" maxlength="80" size="42" value="<?php if(isset($searchtext)) { ?><?php echo $searchtext?><?php } ?>" type="text"/>
	<input name="default" value="进入词条" tabindex="2" class="btn_inp enter_doc" onclick="document.searchform.action='index.php?search-default';document.searchform.submit();" type="button"/>
	<input name="full" value="1" tabindex="1"   type="hidden"/>
	<input name="search" value="搜 索" tabindex="1" class="btn_inp sea_doc" type="submit"/>
	<a href="index.php?search-fulltext" class="sea_advanced link_black">高级搜索</a>
	<?php } ?>
	
	<label>热门搜索：
		<?php foreach((array)$hotsearch as $hotname) {?>
			<?php if($hotname['name']) { ?>
				<a href="<?php if($hotname['url']) { ?><?php echo $hotname['url']?><?php } else { ?>index.php?doc-innerlink-<?php echo urlencode($hotname['name'])?><?php } ?>" target="_blank"><?php echo $hotname['name']?></a>
			<?php } ?>
		<?php } ?>
	</label>
	</p>
	</form>
	<?php } ?>
	<div id="nav" class="w-950 bor_b-ccc">
	<ul>
	<?php if(count($channellist[2])!=0) { ?>
	<?php foreach((array)$channellist[2] as $channel) {?>
	<li><a href="<?php echo $channel['url']?>"><?php echo $channel['name']?></a></li>
	<?php } ?>
	<?php } ?>
	
		<?php foreach((array)$pluginlist as $plugin) {?>
			<?php if($plugin['type']) { ?>
				<li><a href="index.php?plugin-<?php echo $plugin['identifier']?>"><?php echo $plugin['name']?></a></li>
			<?php } ?>
		<?php } ?>
	</ul>
	<label><a href="index.php?doc-create">创建词条</a><a href="index.php?doc-sandbox">编辑实验</a></label>
	</div>
</div>

<!--ad start -->

<?php if(isset($advlist[0]) && isset($setting['advmode']) && '1'==$setting['advmode']) { ?>
<div class="ad" id="advlist_0">
<?php echo $advlist[0][code]?>
</div>
<?php } elseif(isset($advlist[0]) && (!isset($setting['advmode']) || !$setting['advmode'])) { ?>
<div class="ad" id="advlist_0">
</div>
<?php } ?>

<!--ad end -->