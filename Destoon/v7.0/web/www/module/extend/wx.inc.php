<?php
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$wid = isset($wid) ? trim($wid) : '';
is_wx($wid) or $wid = '';
$username = isset($username) ? trim($username) : '';
check_name($username) or $username = '';
$wx = $wid;
$wxqr = '';
$user = $username ? userinfo($username) : array();
if($user) {
	if($user['wx']) $wx = $user['wx'];
	if($user['wxqr']) $wxqr = $user['wxqr'];
}
$template = 'wx';
$head_title = $L['wx_title'];
$head_keywords = $head_description = '';
if($DT_PC) {	
	$destoon_task = rand_task();
	if($EXT['mobile_enable']) $head_mobile = str_replace(DT_PATH, DT_MOB, $DT_URL);
} else {
	$foot = '';
	$head_name = $L['wx_title'];
	$back_link = 'javascript:Dback();';
}
include template($template, $module);
?>