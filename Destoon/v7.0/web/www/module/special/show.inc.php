<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$could_comment = in_array($moduleid, explode(',', $EXT['comment_module'])) ? 1 : 0;
$modurl = $DT_PC ? $MOD['linkurl'] : $MOD['mobile'];
$itemid or dheader($modurl);
if(!check_group($_groupid, $MOD['group_show'])) include load('403.inc');
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
if($item && $item['status'] > 2) {
	if($item['islink']) dheader($item['linkurl']);
	if($MOD['show_html'] && is_file(DT_ROOT.'/'.$MOD['moduledir'].'/'.$item['linkurl'])) d301($modurl.$item['linkurl']);
	extract($item);
} else {
	include load('404.inc');
}
$CAT = get_cat($catid);
if(!check_group($_groupid, $CAT['group_show'])) include load('403.inc');
$content_table = content_table($moduleid, $itemid, $MOD['split'], $table_data);
$t = $db->get_one("SELECT content FROM {$content_table} WHERE itemid=$itemid");
$content = $t['content'];
$TYPE = array();
foreach(get_type('special-'.$itemid) as $v) {
	$v['linkurl'] = $modurl.rewrite('type.php?tid='.$v['typeid']);
	$TYPE[] = $v;
}
$adddate = timetodate($addtime, 3);
$editdate = timetodate($edittime, 3);
$linkurl = $modurl.$linkurl;
if($DT_PC) {
	$content = parse_video($content);
	if($MOD['keylink']) $content = keylink($content, $moduleid);
	if($lazy) $content = img_lazy($content);
	$CP = $MOD['cat_property'] && $CAT['property'];
	if($CP) {
		require DT_ROOT.'/include/property.func.php';
		$options = property_option($catid);
		$values = property_value($moduleid, $itemid);
	}
	$update = '';
	$user_status = 3;
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $linkurl);
} else {
	$content = video5($content);
	if($MOD['keylink']) $content = keylink($content, $moduleid, 1);
	if($share_icon) $share_icon = share_icon($thumb, $content);
	$update = '';
	$user_status = 3;
	$back_link = $MOD['mobile'].$CAT['linkurl'];
	$head_name = $CAT['catname'];
	$foot = '';
}
if(!$DT_BOT) include DT_ROOT.'/include/update.inc.php';
$seo_file = 'show';
include DT_ROOT.'/include/seo.inc.php';
$template = $item['template'] ? $item['template'] : ($CAT['show_template'] ? $CAT['show_template'] : ($MOD['template_show'] ? $MOD['template_show'] : 'show'));
include template($template, $module);
?>