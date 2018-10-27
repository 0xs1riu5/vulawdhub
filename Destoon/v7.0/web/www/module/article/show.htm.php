<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['show_html'] || !$itemid) return false;
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
if(!$item || $item['status'] < 3 || $item['islink'] > 0) return false;
$could_comment = in_array($moduleid, explode(',', $EXT['comment_module'])) ? 1 : 0;
extract($item);
$CAT = get_cat($catid);
$content_table = content_table($moduleid, $itemid, $MOD['split'], $table_data);
$t = $db->get_one("SELECT content FROM {$content_table} WHERE itemid=$itemid");
$content = $_content =  $t['content'];
$CP = $MOD['cat_property'] && $CAT['property'];
if($CP) {
	require_once DT_ROOT.'/include/property.func.php';
	$options = property_option($catid);
	$values = property_value($moduleid, $itemid);
}

$adddate = timetodate($addtime, 3);
$editdate = timetodate($edittime, 3);
if($voteid) $voteid = explode(' ', $voteid);
if($fromurl) $fromurl = fix_link($fromurl);
$fileurl = $linkurl;
$linkurl = $MOD['linkurl'].$linkurl;
$titles = array();
if($subtitle) {
	$titles = explode("\n", $subtitle);
	$titles = array_map('trim', $titles);
}
$keytags = $tag ? explode(' ', $tag) : array();
$fee = get_fee($item['fee'], $MOD['fee_view']);
if($fee) {
	$description = get_description($content, $MOD['pre_view']);
	$user_status = 4;
} else {
	$user_status = 3;
}
$pages = '';
$total = 1;
$subtitles = count($titles);
if(strpos($content, 'de-pagebreak') !== false) {
	$content = str_replace('"de-pagebreak" /', '"de-pagebreak"/', $content);
	$contents = explode('<hr class="de-pagebreak"/>', $content);
	$total = count($contents);
	if($total < $subtitles) $subtitles = $total;
}
$seo_file = 'show';
include DT_ROOT.'/include/seo.inc.php';
$template = $item['template'] ? $item['template'] : ($CAT['show_template'] ? $CAT['show_template'] : ($MOD['template_show'] ? $MOD['template_show'] : 'show'));
if($EXT['mobile_enable']) {
	include DT_ROOT.'/include/mobile.htm.php';	
	$back_link = $MOD['mobile'].$CAT['linkurl'];
	$head_name = $CAT['catname'];
	$foot = '';
}
for(; $page <= $total; $page++) {
	$subtitle = isset($titles[$page-1]) ? $titles[$page-1] : '';
	if($subtitle) $seo_title = $subtitle.$seo_delimiter.$seo_title;
	$destoon_task = "moduleid=$moduleid&html=show&itemid=$itemid&page=$page";
	if($EXT['mobile_enable']) $head_mobile = $MOD['mobile'].itemurl($item, $page > 1 ? $page : '');
	$filename = $total == 1 ? DT_ROOT.'/'.$MOD['moduledir'].'/'.$fileurl : DT_ROOT.'/'.$MOD['moduledir'].'/'.itemurl($item, $page);
	if($total > 1) {
		$pages = pages($total, $page, 1, $MOD['linkurl'].itemurl($item, '{destoon_page}'));
		if($pages) $pages = substr($pages, 0, strpos($pages, '<cite>'));
		$content = $contents[$page-1];
	}
	$_content = $content;
	$content = parse_video($content);
	if($lazy) $content = img_lazy($content);
	if($MOD['keylink']) $content = keylink($content, $moduleid);
	$DT_PC = $GLOBALS['DT_PC'] = 1;
	ob_start();
	include template($template, $module);
	$data = ob_get_contents();
	ob_clean();
	if($DT['pcharset']) $filename = convert($filename, DT_CHARSET, $DT['pcharset']);
	file_put($filename, $data);
	if($page == 1 && $total > 1) {
		$indexname = DT_ROOT.'/'.$MOD['moduledir'].'/'.itemurl($item, 0);
		if($DT['pcharset']) $indexname = convert($indexname, DT_CHARSET, $DT['pcharset']);
		file_copy($filename, $indexname);
	}
	if($EXT['mobile_enable']) {
		if($total > 1) $pages = mobile_pages($total, $page, 1, $MOD['mobile'].itemurl($item, '{destoon_page}'));
		$content = video5($_content);
		if($MOD['keylink']) $content = keylink($content, $moduleid, 1);
		$filename = str_replace(DT_ROOT, DT_ROOT.'/mobile', $filename);
		$DT_PC = $GLOBALS['DT_PC'] = 0;
		ob_start();
		include template($template, $module);
		$data = ob_get_contents();
		ob_clean();
		file_put($filename, $data);
		if($page == 1 && $total > 1) file_copy($filename, str_replace(DT_ROOT, DT_ROOT.'/mobile', $indexname));
	}
}
return true;
?>