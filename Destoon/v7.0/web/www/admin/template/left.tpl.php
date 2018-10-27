<?php
defined('DT_ADMIN') or exit('Access Denied');
?>
<!doctype html>
<html lang="<?php echo DT_LANG;?>">
<head>
<meta charset="<?php echo DT_CHARSET;?>"/>
<title>管理中心 - <?php echo $DT['sitename']; ?> - Powered By DESTOON B2B V<?php echo DT_VERSION; ?> R<?php echo DT_RELEASE;?></title>
<meta name="robots" content="noindex,nofollow"/>
<meta name="generator" content="DESTOON B2B - www.destoon.com"/>
<meta http-equiv="x-ua-compatible" content="IE=8"/>
<link rel="stylesheet" href="admin/image/style.css" type="text/css"/>
<?php if(!DT_DEBUG) { ?><script type="text/javascript">window.onerror= function(){return true;}</script><?php } ?>
<script type="text/javascript" src="<?php echo DT_STATIC;?>lang/<?php echo DT_LANG;?>/lang.js"></script>
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/config.js"></script>
<!--[if lte IE 9]><!-->
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/jquery-1.5.2.min.js"></script>
<!--<![endif]-->
<!--[if (gte IE 10)|!(IE)]><!-->
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/jquery-2.1.1.min.js"></script>
<!--<![endif]-->
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/common.js"></script>
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/admin.js"></script>
<base target="main"/>
<style type="text/css">
html{overflow-x:hidden;overflow-y:auto;}
::-webkit-scrollbar{width:6px;height:6px;overflow:auto;}::-webkit-scrollbar-thumb{background-color:#E6E6E6;min-height:25px;min-width:25px;border:1px solid #E0E0E0;}::-webkit-scrollbar-track{background-color:#F7F7F7;border:1px solid #EFEFEF;}
</style>
</head>
<body>
<?php
include DT_ROOT.'/admin/menu.inc.php';
if($_admin == 2) {
?>
<table cellpadding="0" cellspacing="0" width="<?php echo $DT['admin_left'];?>" height="100%">
<tr>
<td valign="top" class="barmain" id="menu">
<div id="m_1">
	<dl>
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">我的面板</dt>
	<dd onclick="c(this);"><a href="?action=main">系统首页</a></dd>
	<dd onclick="c(this);"><a href="?file=mymenu">定义面板</a></dd>
	<?php
		foreach($mymenu as $menu) {
	?>
	<dd onclick="c(this);"><a href="<?php echo substr($menu['url'], 0, 1) == '?' ? $menu['url'] : DT_PATH.'api/redirect.php?url='.$menu['url'].'" target="_blank';?>"><?php echo set_style($menu['title'], $menu['style']);?></a></dd>
	<?php
		}
	?>
	</dl>
</div>
</td>
</tr>
</table>
<?php } else { ?>
<table cellpadding="0" cellspacing="0" width="<?php echo $DT['admin_left'];?>" height="100%">
<tr>
<td id="bar" class="bar" valign="top">
<div class="barfix">
<div onclick="sideshow(1);"><img src="admin/image/bar1-on.png" id="b_1"/><span>我的面板</span></div>
<div onclick="sideshow(2);"><img src="admin/image/bar2.png" id="b_2"/><span>系统维护</span></div>
<div onclick="sideshow(3);"><img src="admin/image/bar3.png" id="b_3"/><span>功能模块</span></div>
<div onclick="sideshow(4);"><img src="admin/image/bar4.png" id="b_4"/><span>会员管理</span></div>
<div onclick="sideshow(5);"><img src="admin/image/bar5.png" id="b_5"/><span>扩展功能</span></div>
</div>
</td>
<td valign="top" class="barmain" id="menu">
	<div id="m_1">
	<dl>
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">我的面板</dt>
	<dd onclick="c(this);" class="dd_on"><a href="?action=main">后台首页</a></dd>
	<dd onclick="c(this);"><a href="?file=mymenu">定义面板</a></dd>
	<?php
		foreach($mymenu as $m) {
	?>
	<dd onclick="c(this);"><a href="<?php echo substr($m['url'], 0, 1) == '?' ? $m['url'] : DT_PATH.'api/redirect.php?url='.$m['url'].'" target="_blank';?>"><?php echo set_style($m['title'], $m['style']);?></a></dd>
	<?php
		}
	?>
	</dl>
	<dl>
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">快速链接</dt>
	<dd onclick="c(this);"><a href="./" target="_blank">网站首页</a></dd>
	<dd onclick="c(this);"><a href="<?php echo $MODULE[2]['linkurl'];?>" target="_blank">商务中心</a></dd>
	<dd onclick="c(this);"><a href="?file=logout" target="_top" onclick="return confirm('确定要退出管理后台吗');">安全退出</a></dd>
	</dl>
	<dl>
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">使用帮助</dt>
	<?php
		foreach($menu_help as $m) {
			echo '<dd onclick="c(this);" style="display:none;"><a href="'.$m[1].'">'.$m[0].'</a></dd>';
		}
	?>
	</dl>
	</div>
	<div id="m_2" style="display:none;">
	<?php if($_founder) { ?>
	<dl> 
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">系统维护</dt> 
	<?php
		foreach($menu_system as $m) {
			echo '<dd onclick="c(this);"><a href="'.$m[1].'">'.$m[0].'</a></dd>';
		}
	?>
	</dl>
	<?php } ?>
	<dl> 
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">系统工具</dt>
	<?php
		foreach($menu as $m) {
			echo '<dd onclick="c(this);"><a href="'.$m[1].'">'.$m[0].'</a></dd>';
		}
	?>
	</dl>
	</div>
	<div id="m_3" style="display:none;">
	<?php
		$k = 0;
		foreach($MODULE as $v) {
			if($v['moduleid'] > 4) {
				$menuinc = DT_ROOT.'/module/'.$v['module'].'/admin/menu.inc.php';
				if(is_file($menuinc)) {
					extract($v);
					include $menuinc;
					echo '<dl id="dl_'.$moduleid.'">';
					echo '<dt onclick="m('.$moduleid.');" onmouseover="this.className=\'dt_on\';" onmouseout="this.className=\'\';">'.$name.'管理</dt>';
					foreach($menu as $m) {
						echo '<dd onclick="c(this);"'.($k ? ' style="display:none;"' : '').'><a href="'.$m[1].'">'.$m[0].'</a></dd>';
					}
					echo '</dl>';
					$k++;
				}
			}
		}
	?>
	</div>
	<div id="m_4" style="display:none;">
	<?php
		$menuinc = DT_ROOT.'/module/'.$MODULE[2]['module'].'/admin/menu.inc.php';
		if(is_file($menuinc)) {
			extract($MODULE[2]);
			include $menuinc;
			echo '<dl id="dl_'.$moduleid.'">';
			echo '<dt id="dt_'.$moduleid.'" onclick="s(this);" onmouseover="this.className=\'dt_on\';" onmouseout="this.className=\'\';">'.$name.'管理</dt>';
			foreach($menu as $m) {
				echo '<dd onclick="c(this);"><a href="'.$m[1].'">'.$m[0].'</a></dd>';
			}
			echo '</dl>';
		}
	?>
	<?php
		$menuinc = DT_ROOT.'/module/'.$MODULE[4]['module'].'/admin/menu.inc.php';
		if(is_file($menuinc)) {
			extract($MODULE[4]);
			include $menuinc;
			echo '<dl id="dl_'.$moduleid.'">';
			echo '<dt id="dt_'.$moduleid.'" onclick="s(this);" onmouseover="this.className=\'dt_on\';" onmouseout="this.className=\'\';">'.$name.'管理</dt>';
			foreach($menu as $m) {
				echo '<dd onclick="c(this);" style="display:none;"><a href="'.$m[1].'">'.$m[0].'</a></dd>';
			}
			echo '</dl>';
		}
	?>
	<dl id="dl_pay"> 
	<dt id="dt_pay" onclick="s(this);" onmouseover="this.className='dt_on';" onmouseout="this.className='';">财务管理</dt>
	<?php
		foreach($menu_finance as $m) {
			echo '<dd onclick="c(this);"><a href="'.$m[1].'">'.$m[0].'</a></dd>';
		}
	?>
	</dl>
	<dl id="dl_oth"> 
	<dt id="dt_oth" onclick="s(this);" onmouseover="this.className='dt_on';" onmouseout="this.className='';">会员相关</dt> 
	<?php
		foreach($menu_relate as $m) {
			echo '<dd onclick="c(this);"><a href="'.$m[1].'">'.$m[0].'</a></dd>';
		}
	?>
	</dl>
	</div>
	<div id="m_5" style="display:none;">
	<?php
		$menuinc = DT_ROOT.'/module/'.$MODULE[3]['module'].'/admin/menu.inc.php';
		if(is_file($menuinc)) {
			extract($MODULE[3]);
			include $menuinc;
			echo '<dl id="dl_'.$moduleid.'">';
			echo '<dt onclick="m('.$moduleid.');" onmouseover="this.className=\'dt_on\';" onmouseout="this.className=\'\';">扩展功能</dt>';
			foreach($menu as $m) {
				echo '<dd onclick="c(this);"><a href="'.$m[1].'">'.$m[0].'</a></dd>';
			}
			echo '</dl>';
		}
	?>
	</div>
</td>
</tr>
</table>
<script type="text/javascript">
function sideshow(ID) {
	for(i=1;i<6;i++) {
		if(i==ID) {
			Dd('b_'+i).src = 'admin/image/bar'+i+'-on.png';
			Ds('m_'+i);
		} else {
			Dd('b_'+i).src = 'admin/image/bar'+i+'.png';
			Dh('m_'+i);
		}
	}
}
</script>
<?php } ?>
<script type="text/javascript">
function c(o) {
	var dds = Dd('menu').getElementsByTagName('dd');
	for(var i=0;i<dds.length;i++) {
		dds[i].className = dds[i] == o ? 'dd_on' : '';
		if(dds[i] == o) o.firstChild.blur();
	}
}
function s(o) {
	var dds = o.parentNode.getElementsByTagName('dd');
	for(var i=0;i<dds.length;i++) {
		dds[i].style.display = dds[i].style.display == 'none' ? '' : 'none';
	}
}
function h(o) {
	var dds = o.parentNode.getElementsByTagName('dd');
	for(var i=0;i<dds.length;i++) {
		dds[i].style.display = 'none';
	}
}
function m(ID) {
	var dls = Dd('m_3').getElementsByTagName('dl');
	for(var i=0;i<dls.length;i++) {
		var dds = Dd(dls[i].id).getElementsByTagName('dd');
		for(var j=0;j<dds.length;j++) {
			dds[j].style.display = dls[i].id == 'dl_'+ID ? dds[j].style.display == 'none' ? '' : 'none' : 'none';
		}
	}
}
</script>
<?php if($_admin == 1 && !is_file(DT_ROOT.'/file/md5/'.DT_VERSION.'.php')) { ?>
<script type="text/javascript" src="?file=md5&action=add&js=1"></script>
<?php } ?>
<script type="text/javascript" src="?action=cron"></script>
</body>
</html>