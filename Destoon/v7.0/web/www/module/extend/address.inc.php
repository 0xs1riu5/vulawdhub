<?php
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
isset($auth) or $auth = '';
$auth = $auth ? decrypt($auth, DT_KEY.'MAP') : '';
list($addr, $name) = explode('|', $auth);
include DT_ROOT.'/api/map/baidu/config.inc.php';
$map_key or $map_key = 'waKl9cxyGpfdPbon7PXtDXIf';
$template = 'address';
$head_title = $L['address_title'];
$head_keywords = $head_description = '';
if($DT_PC) {	
	$destoon_task = rand_task();
	if($EXT['mobile_enable']) $head_mobile = str_replace(DT_PATH, DT_MOB, $DT_URL);
} else {
	$foot = '';
	$head_name = $L['address_title'];
	$back_link = 'javascript:Dback();';
}
include template($template, $module);
?>