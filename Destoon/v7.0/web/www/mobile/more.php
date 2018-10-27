<?php
require '../common.inc.php';
require DT_ROOT.'/include/mobile.inc.php';
$app = '';
if(!in_array($DT_MOB['browser'], array('app', 'b2b'))) {
	if($DT_MOB['os'] == 'ios') {
		if($EXT['mobile_ios']) $app = DT_PATH.'api/app.php';
	} else if($DT_MOB['os'] == 'android') {
		if($EXT['mobile_adr']) $app = DT_PATH.'api/app.php';
	}
}
$head_title = $head_name = $L['more_title'];
$foot = 'more';
include template('more', 'mobile');
?>