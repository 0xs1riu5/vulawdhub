<?php
defined('DT_ADMIN') or exit('Access Denied');
?>
<div class="menu" onselectstart="return false" id="destoon_menu">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td valign="bottom">
<table cellpadding="0" cellspacing="0">
<tr>
<?php echo $menu;?>
</tr>
</table>
</td>
<td>
<div>
<a href="?action=home&job=<?php echo $mid ? $mid : $moduleid;?>-<?php echo $file?>" target="_blank"><img src="admin/image/tool-home.png" width="16" height="16" title="前台" alt=""/></a>
<img src="admin/image/tool-reload.png" width="16" height="16" title="刷新" onclick="window.location.reload();" alt=""/>
<img src="admin/image/tool-search.png" width="16" height="16" title="搜索" onclick="Dwidget('?file=search', '后台搜索');" alt=""/>
<img src="admin/image/tool-help.png" width="16" height="16" title="帮助" onclick="Dwidget('?file=cloud&action=doc&mfa=<?php echo $module;?>-<?php echo $file?>-<?php echo $action?>', '帮助文档');" alt=""/>
<script type="text/javascript">
if(parent.location == window.location) {
	document.write('<img src="admin/image/tool-close.png" width="16" height="16" title="关闭" onclick="window.close();" alt=""/>');
} else {
	document.write('<img src="admin/image/tool-full.png" width="16" height="16" title="全屏" onclick="window.open(window.location.href);" alt=""/>');
}
</script>
</div>
</td>
</tr>
</table>
</div>