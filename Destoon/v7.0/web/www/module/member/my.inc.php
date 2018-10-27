<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($DT_PC) {
	$menu_id = 1;
} else {
	$foot = 'my';
}
$head_title = $action == 'add' ? $L['info_add'] : $L['info_manage'];
include template('my', $module);
?>