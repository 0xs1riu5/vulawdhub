<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['show_html'] || !$itemid) return false;
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
if(!$item || $item['status'] < 3) return false;
extract($item);
$CAT = get_cat($catid);
$content_table = content_table($moduleid, $itemid, $MOD['split'], $table_data);
$t = $db->get_one("SELECT content FROM {$content_table} WHERE itemid=$itemid");
$content = $_content =  $t['content'];
$content = parse_video($content);
if($MOD['keylink']) $content = keylink($content, $moduleid);
if($lazy) $content = img_lazy($content);
$CP = $MOD['cat_property'] && $CAT['property'];
if($CP) {
	require_once DT_ROOT.'/include/property.func.php';
	$options = property_option($catid);
	$values = property_value($moduleid, $itemid);
}
$RL = $item['relate_id'] ? get_relate($item) : array();
$P1 = get_nv($n1, $v1);
$P2 = get_nv($n2, $v2);
$P3 = get_nv($n3, $v3);
if($step) {
	extract(unserialize($step));
} else {
	$a1 = 1;
	$p1 = $item['price'];
	$a2 = $a3 = $p2 = $p3 = '';
}
$unit or $unit = $L['unit'];
$adddate = timetodate($addtime, 3);
$editdate = timetodate($edittime, 3);
$fileurl = $linkurl;
$linkurl = $MOD['linkurl'].$linkurl;
$thumbs = get_albums($item);
$albums =  get_albums($item, 1);
$promos = get_promos($username);
$fee = get_fee($item['fee'], $MOD['fee_view']);
$user_status = 4;
$seo_file = 'show';
include DT_ROOT.'/include/seo.inc.php';
$template = $item['template'] ? $item['template'] : ($CAT['show_template'] ? $CAT['show_template'] : ($MOD['template_show'] ? $MOD['template_show'] : 'show'));
$destoon_task = "moduleid=$moduleid&html=show&itemid=$itemid";
if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $linkurl);
$DT_PC = $GLOBALS['DT_PC'] = 1;
ob_start();
include template($template, $module);
$data = ob_get_contents();
ob_clean();
$filename = DT_ROOT.'/'.$MOD['moduledir'].'/'.$fileurl;
if($DT['pcharset']) $filename = convert($filename, DT_CHARSET, $DT['pcharset']);
file_put($filename, $data);
if($EXT['mobile_enable']) {
	include DT_ROOT.'/include/mobile.htm.php';	
	$back_link = $MOD['mobile'].$CAT['linkurl'];
	$head_name = $CAT['catname'];
	$foot = '';
	$content = video5($_content);
	if($MOD['keylink']) $content = keylink($content, $moduleid, 1);
	ob_start();
	include template($template, $module);
	$data = ob_get_contents();
	ob_clean();
	file_put(str_replace(DT_ROOT, DT_ROOT.'/mobile', $filename), $data);
}
return true;
?>