<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
require '../common.inc.php';
require DT_ROOT.'/include/mobile.inc.php';
if($moduleid > 3) m301($moduleid, $catid, $itemid, $page);
$ads = array();
$pid = intval($EXT['mobile_pid']);
if($pid > 0) {
	$result = $db->query("SELECT * FROM {$DT_PRE}ad WHERE pid=$pid AND status=3 AND totime>$DT_TIME ORDER BY listorder ASC,addtime ASC LIMIT 10", 'CACHE');
	while($r = $db->fetch_array($result)) {
		$r['image_src'] = linkurl($r['image_src']);
		$r['url'] = $r['stat'] ? DT_PATH.'api/redirect.php?aid='.$r['aid'] : linkurl($r['url']);
		$ads[] = $r;
	}
}
$MOD_MY = array();
$data = '';
$local = get_cookie('mobile_setting');
if($local) {
	$data = $local;
} else if($_userid) {
	$data = file_get(DT_ROOT.'/file/user/'.dalloc($_userid).'/'.$_userid.'/mobile-setting.php');
	if($data) {
		$data = substr($data, 13);
		set_cookie('mobile_setting', $data, $DT_TIME + 30*86400);
	}
}
if($data) {
	$MOB_MOD = array();
	foreach($MOB_MODULE as $m) {
		$MOB_MOD[$m['moduleid']] = $m;
	}
	foreach(explode(',', $data) as $id) {
		if(isset($MOB_MOD[$id])) $MOD_MY[] = $MOB_MOD[$id];
	}
}
if(count($MOD_MY) < 2) $MOD_MY = $MOB_MODULE;
$head_name = $EXT['mobile_sitename'] ? $EXT['mobile_sitename'] : $DT['sitename'];
$seo_title = $DT['seo_title'];
$head_keywords = $DT['seo_keywords'];
$head_description = $DT['seo_description'];
$foot = 'home';
include template('index');
?>