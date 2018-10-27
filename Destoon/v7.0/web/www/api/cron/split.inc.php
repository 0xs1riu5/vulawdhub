<?php
defined('IN_DESTOON') or exit('Access Denied');
if(!function_exists('split_content')) {
	define('DT_ADMIN', true);
	require_once DT_ROOT.'/admin/global.func.php';
}
$files = glob(DT_CACHE.'/*.part');
if($files) {
	foreach($files as $f) {
		$mid = basename($f, '.part');
		if(!isset($MODULE[$mid])) continue;
		$fd = $mid == 4 ? 'userid' : 'itemid';
		$r = $db->get_one("SELECT MAX($fd) AS maxid FROM ".get_table($mid));
		$part = split_id($r['maxid']);
		split_content($mid, $part++);
		split_content($mid, $part++);
		split_content($mid, $part++);
	}
}
?>