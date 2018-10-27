<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['show_html'] || !$itemid) return false;
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
if(!$item || $item['status'] < 3) return false;
$could_comment = in_array($moduleid, explode(',', $EXT['comment_module'])) ? 1 : 0;
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
$adddate = timetodate($addtime, 3);
$editdate = timetodate($edittime, 3);
$todate = $totime ? timetodate($totime, 3) : 0;
$expired = $totime && $totime < $DT_TIME ? true : false;
$fileurl = $linkurl;
$linkurl = $MOD['linkurl'].$linkurl;
$jsdate = $totime ? timetodate($totime, 'Y,').(timetodate($totime, 'n')-1).timetodate($totime, ',j,H,i,s') : '';
$iprice = file_ext($price) == '00' ? intval($price) : $price;
$fee = get_fee($item['fee'], $MOD['fee_view']);
$left = $minamount ? $minamount - $orders : 1 - $orders;
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
	$purchase = $process < 2 && $username && $username != $_username ? 1 : 0;
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