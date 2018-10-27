<?php
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$pass = $img;
if(strpos($img, DT_DOMAIN ? DT_DOMAIN : DT_PATH) !== false) {
	$pass = true;
} else {
	if($DT['remote_url'] && strpos($img, $DT['remote_url']) !== false) {
		$pass = true;
	} else {
		$pass = false;
	}
}
$pass or dheader($img);
$ext = file_ext($img);
in_array($ext, array('jpg', 'jpeg', 'gif', 'png', 'bmp')) or dheader($DT_PC ? DT_PATH : DT_MOB);
$img = str_replace(array('.thumb.'.$ext, '.middle.'.$ext), array('', ''), $img);
$template = 'view';
$head_title = $L['view_title'];
$head_keywords = $head_description = '';
if($DT_PC) {	
	$destoon_task = rand_task();
	if($EXT['mobile_enable']) $head_mobile = str_replace(DT_PATH, DT_MOB, $DT_URL);
} else {
	$foot = '';
	$head_name = $L['view_title'];
	$back_link = 'javascript:Dback();';
}
include template($template, $module);
?>