<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
require '../../common.inc.php';
require DT_ROOT.'/include/mobile.inc.php';
if($moduleid < 4) $moduleid = 4;
$AREA = cache_read('area.php');
$pid = isset($pid) ? intval($pid) : 0;
$back_link = $pid ? DT_MOB.'api/area.php?moduleid='.$moduleid.'&pid='.$AREA[$pid]['parentid'] : $MODULE[$moduleid]['mobile'];;
$lists = array();
foreach($AREA as $a) {
	if($a['parentid'] == $pid) $lists[] = $a;
}
$head_title = $MOD['name'];
include template('area', 'mobile');
?>