<?php
defined('DT_ADMIN') or exit('Access Denied');
$tab = isset($tab) ? intval($tab) : 0;
$all = isset($all) ? intval($all) : 0;
if($submit) {
	foreach($setting as $k=>$v) {
		if(strpos($k, '_domain') !== false && $v) $setting[$k] = fix_domain($v);
	}
	update_setting($moduleid, $setting);
	cache_module($moduleid);
	if($setting['show_url'] != $MOD['show_url']) {
		msg('设置保存成功，开始更新地址', '?moduleid='.$moduleid.'&file=html');
	}
	dmsg('设置保存成功', '?moduleid='.$moduleid.'&file='.$file.'&tab='.$tab);
} else {
	$GROUP = cache_read('group.php');
	extract(dhtmlspecialchars($MOD));
	if($kw) {
		$all = 1;
		ob_start();
	}
	include tpl('setting', $module);
	if($kw) {
		$data = $content = ob_get_contents();
		ob_clean();
		$data = preg_replace('\'(?!((<.*?)|(<a.*?)|(<strong.*?)))('.$kw.')(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</strong>))\'si', '<span class=highlight>'.$kw.'</span>', $data);
		$data = preg_replace('/<span class=highlight>/', '<a name=high></a><span class=highlight>', $data, 1);
		echo $data ? $data : $content;
	}
}
?>