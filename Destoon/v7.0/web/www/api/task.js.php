<?php
@set_time_limit(0);
#@ignore_user_abort(true);
define('DT_TASK', true);
require '../common.inc.php';
check_referer() or exit;
if($DT_BOT) exit;
#header("Content-type:text/javascript");	
include template('line', 'chip');
$db->linked or exit;
isset($html) or $html = '';
if($html) {
	$task_index = intval($DT['task_index']);
	$task_index > 60 or $task_index = 300;
	$task_list = intval($DT['task_list']);
	$task_list > 300 or $task_list = 1800;
	$task_item = intval($DT['task_item']);
	$task_item > 1800 or $task_item = 3600;
	if($moduleid == 1) {
		if($DT['index_html'] && $DT_TIME - @filemtime(DT_ROOT.'/'.$DT['index'].'.'.$DT['file_ext']) > $task_index) tohtml('index');
	} else {
		include DT_ROOT.'/module/'.$module.'/common.inc.php';
		include DT_ROOT.'/module/'.$module.'/task.inc.php';
	}
}
include DT_ROOT.'/api/cron.inc.php';
$db->close();
?>