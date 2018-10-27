<?php
require '../../common.inc.php';
require DT_ROOT.'/include/mobile.inc.php';
if($moduleid < 4) $moduleid = 4;
$pid = isset($pid) ? intval($pid) : 0;
if($pid) {
	$P = get_cat($pid);
	$back_link = DT_MOB.'api/category.php?moduleid='.$moduleid.'&pid='.$P['parentid'];
} else {
	$back_link = $MODULE[$moduleid]['mobile'];
}
$lists = get_maincat($pid, $moduleid);
$head_title = $MOD['name'].$DT['seo_delimiter'].$head_title;
include template('category', 'mobile');
?>