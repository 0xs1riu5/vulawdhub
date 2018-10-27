<?php
defined('IN_DESTOON') or exit('Access Denied');
$ips = glob(DT_CACHE.'/ban/*.php');
if($ips) {
	$M = cache_read('module-2.php');
	$time = $DT_TIME - $M['lock_hour']*3600;
	foreach($ips as $k=>$v) {
		if(filemtime($v) < $time) file_del($v);
	}
}
$db->query("DELETE FROM {$DT_PRE}banip WHERE totime>0 and totime<$DT_TIME");
if($db->affected_rows()) {
	if(!function_exists('cache_banip')) require_once DT_ROOT.'/include/cache.func.php';
	cache_banip();
}
?>