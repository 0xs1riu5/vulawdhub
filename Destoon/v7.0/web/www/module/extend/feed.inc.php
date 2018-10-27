<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MOD['feed_enable'] or dheader(DT_PATH);
require DT_ROOT.'/include/post.func.php';
$ext = 'feed';
$url = $EXT[$ext.'_url'];
$mob = $EXT[$ext.'_mob'];
$FD = array();
foreach($MODULE as $m) {
	if($m['islink'] || !$m['ismenu'] || $m['moduleid'] < 5) continue;
	$m['rssurl'] = $MOD['feed_url'].'rss.php?mid='.$m['moduleid'];
	$FD[] = $m;
}
if($action == 'diy') {
	$areaid = isset($areaid) ? intval($areaid) : 0;
	$feed_code = '';
	$category_select = '';
	$area_select = '';
	if($mid && $mid > 4 && isset($MODULE[$mid]) && !$MODULE[$mid]['islink']) {
		$feed_code .= $MOD['feed_url'].'rss.php?mid='.$mid;
		if($kw == $L['keyword']) $kw = '';
		if($kw && strlen($kw) > 2 && strlen($kw) < 30) $feed_code .= '&kw='.urlencode($kw);
		if($catid) $feed_code .= '&catid='.urlencode($catid);
		if($areaid) $feed_code .= '&areaid='.urlencode($areaid);
		$category_select = category_select('catid', $L['category'], $catid, $mid);
		if(in_array($MODULE[$mid]['module'], array('sell','buy', 'exhibit', 'info', 'job', 'mall', 'group'))) $area_select = ajax_area_select('areaid', $L['rss_area'], $areaid);
	}
} else {
	//
}
$template = $ext;
$head_title = $L['rss_title'];
$head_keywords = $head_description = '';
if($DT_PC) {	
	$destoon_task = rand_task();
	if($EXT['mobile_enable']) $head_mobile = str_replace($url, $mob, $DT_URL);
} else {
	$foot = '';
	if($action == 'diy') {
		$back_link = $mob;
	} else {
		$back_link = DT_MOB.'more.php';
	}
}
include template($template, $module);
?>