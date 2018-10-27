<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$could_comment = in_array($moduleid, explode(',', $EXT['comment_module'])) ? 1 : 0;
$modurl = $DT_PC ? $MOD['linkurl'] : $MOD['mobile'];
$itemid or dheader($modurl);
if(!check_group($_groupid, $MOD['group_show'])) include load('403.inc');
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
if($item && $item['status'] > 2) {
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
$adddate = timetodate($addtime, 3);
$editdate = timetodate($edittime, 3);
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
	$linkurl = $MOD['linkurl'].$linkurl;
	$maincat = get_maincat(0, $moduleid);
	$keytags = $tag ? explode(' ', $tag) : array();
	$UA = strtolower($_SERVER['HTTP_USER_AGENT']);
	$video_i = (strpos($UA, 'ipad') !== false || strpos($UA, 'ipod') !== false || strpos($UA, 'iphone') !== false || strpos($UA, 'android') !== false) ? 1 : 0;
	$video_s = $video;
	$video_w = $width;
	$video_h = $height;
	$video_a = $MOD['autostart'] ? 'true' : 'false';
	$video_p = 0;
	$video_e = file_ext($video);
	$video_d = cutstr($video, '://', '/');
	if(in_array($video_e, array('flv', 'mp4'))) {
		$video_p = 1;
	} else if(in_array($video_e, array('wma', 'wmv'))) {
		$video_p = 2;
	} else if(in_array($video_e, array('rm', 'rmvb', 'ram'))) {
		$video_p = 3;
	} else if(in_array($video_d, array('player.youku.com', 'v.qq.com', 'm.iqiyi.com', 'liveshare.huya.com'))) {
		$video_p = 4;
	} else if($video_d == 'staticlive.douyucdn.cn') {
		$video_p = 5;
	}
	$update = '';
	$fee = get_fee($item['fee'], $MOD['fee_view']);
	if($fee) {
		$user_status = 4;
		$destoon_task = "moduleid=$moduleid&html=show&itemid=$itemid&page=$page";
		$description = '';
	} else {
		$user_status = 3;
	}
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $linkurl);
} else {
	$member = array();
	$fee = get_fee($item['fee'], $MOD['fee_view']);
	include DT_ROOT.'/mobile/api/content.inc.php';
	$content = video5($content);
	if($MOD['keylink']) $content = keylink($content, $moduleid, 1);
	if($share_icon) $share_icon = share_icon($thumb, $content);
	$update = '';
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