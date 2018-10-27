<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$GROUP = cache_read('group.php');
$UP = array();
if($_groupid > 2) {
	foreach($GROUP as $k=>$v) {
		if($v['listorder'] > $MG['listorder']) $UP[$k] = $v;
	}
}
$GROUPS = array();
foreach($GROUP as $k=>$v) {
	if($k > 4) {
		$G = cache_read('group-'.$k.'.php');
		$G['moduleids'] = isset($G['moduleids']) ? explode(',', $G['moduleids']) : array();
		if($G['grade']) $GROUPS[$k] = $G;
	}
}
$cols = count($GROUPS) + 1;
$percent = dround(100/$cols).'%';
$head_title = $L['grade_title'];
if($DT_PC) {
	//
} else {
	$foot = '';
	$back_link = 'index.php';
}
include template('grade', $module);
?>