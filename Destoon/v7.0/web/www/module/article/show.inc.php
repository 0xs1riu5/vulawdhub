<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$could_comment = in_array($moduleid, explode(',', $EXT['comment_module'])) ? 1 : 0;
if($DT_PC) {
	$itemid or dheader($MOD['linkurl']);
	if(!check_group($_groupid, $MOD['group_show'])) include load('403.inc');
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	if($item && $item['status'] == 3) {
		if($item['islink']) dheader($item['linkurl']);
		if($MOD['show_html'] && is_file(DT_ROOT.'/'.$MOD['moduledir'].'/'.$item['linkurl'])) d301($MOD['linkurl'].$item['linkurl']);
		extract($item);
	} else {
		include load('404.inc');
	}
	$CAT = get_cat($catid);
	if(!check_group($_groupid, $CAT['group_show'])) include load('403.inc');
	$content_table = content_table($moduleid, $itemid, $MOD['split'], $table_data);
	$t = $db->get_one("SELECT content FROM {$content_table} WHERE itemid=$itemid");
	$content = $t['content'];
	$CP = $MOD['cat_property'] && $CAT['property'];
	if($CP) {
		require DT_ROOT.'/include/property.func.php';
		$options = property_option($catid);
		$values = property_value($moduleid, $itemid);
	}
	$adddate = timetodate($addtime, 3);
	$editdate = timetodate($edittime, 3);
	if($voteid) $voteid = explode(' ', $voteid);
	if($fromurl) $fromurl = fix_link($fromurl);
	$linkurl = $MOD['linkurl'].$linkurl;
	$titles = array();
	if($subtitle) {
		$titles = explode("\n", $subtitle);
		$titles = array_map('trim', $titles);
	}
	$subtitle = isset($titles[$page-1]) ? $titles[$page-1] : '';
	$keytags = $tag ? explode(' ', $tag) : array();
	$update = '';
	$fee = get_fee($item['fee'], $MOD['fee_view']);
	if($fee) {
		$user_status = 4;
		$destoon_task = "moduleid=$moduleid&html=show&itemid=$itemid&page=$page";
		$description = get_description($content, $MOD['pre_view']);
	} else {
		$user_status = 3;
	}
	$pages = '';
	$subtitles = count($titles);
	$total = 1;
	if(strpos($content, 'de-pagebreak') !== false) {
		$content = str_replace('"de-pagebreak" /', '"de-pagebreak"/', $content);
		$contents = explode('<hr class="de-pagebreak"/>', $content);
		$total = count($contents);
		$pages = pages($total, $page, 1, $MOD['linkurl'].itemurl($item, '{destoon_page}'));
		if($pages) $pages = substr($pages, 0, strpos($pages, '<cite>'));
		$content = isset($contents[$page-1]) ? $contents[$page-1] : '';
		if($total < $subtitles) $subtitles = $total;
	}
	if($page > $total) include load('404.inc');
	$content = parse_video($content);
	if($MOD['keylink']) $content = keylink($content, $moduleid);
	if($lazy) $content = img_lazy($content);
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $linkurl);
} else {
	$itemid or dheader($MOD['mobile']);
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	($item && $item['status'] == 3) or message($L['msg_not_exist']);
	extract($item);
	$CAT = get_cat($catid);
	if(!check_group($_groupid, $MOD['group_show']) || !check_group($_groupid, $CAT['group_show'])) message($L['msg_no_right']);
	$description = '';
	$user_status = 3;
	$fee = get_fee($item['fee'], $MOD['fee_view']);
	include DT_ROOT.'/mobile/api/content.inc.php';
	$content_table = content_table($moduleid, $itemid, $MOD['split'], $table_data);
	$t = $db->get_one("SELECT content FROM {$content_table} WHERE itemid=$itemid");
	$content = video5($t['content']);
	if($MOD['keylink']) $content = keylink($content, $moduleid, 1);
	$titles = array();
	if($subtitle) {
		$titles = explode("\n", $subtitle);
		$titles = array_map('trim', $titles);
	}
	$subtitle = isset($titles[$page-1]) ? $titles[$page-1] : '';
	$pages = '';
	$subtitles = count($titles);
	$total = 1;
	if(strpos($content, 'de-pagebreak') !== false) {
		$content = str_replace('"de-pagebreak" /', '"de-pagebreak"/', $content);
		$contents = explode('<hr class="de-pagebreak"/>', $content);
		$total = count($contents);
		$pages = mobile_pages($total, $page, 1, $MOD['mobile'].itemurl($item, '{destoon_page}'));
		$content = isset($contents[$page-1]) ? $contents[$page-1] : '';
		if($total < $subtitles) $subtitles = $total;
	}
	if($share_icon) $share_icon = share_icon($thumb, $content);
	if($user_status == 2) $description = get_description($content, $MOD['pre_view']);
	$editdate = timetodate($addtime, 5);
	$update = '';
	$back_link = $MOD['mobile'].$CAT['linkurl'];
	$head_name = $CAT['catname'];
	$foot = '';
}
if(!$DT_BOT) include DT_ROOT.'/include/update.inc.php';
$seo_file = 'show';
include DT_ROOT.'/include/seo.inc.php';
if($subtitle) $seo_title = $subtitle.$seo_delimiter.$seo_title;
$template = $item['template'] ? $item['template'] : ($CAT['show_template'] ? $CAT['show_template'] : ($MOD['template_show'] ? $MOD['template_show'] : 'show'));
include template($template, $module);
?>