<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('重名检测', '?file='.$file),
);
$mid or $mid = 21;
$key = isset($key) ? trim($key) : 'title';
$num = isset($num) ? intval($num) : 100;
$status = isset($status) ? intval($status) : 3;
$lists = array();
if(isset($ok)) {
	$submit = 1;
	$act = '';
	if($status == 4) $act = 'expire';
	if($status == 2) $act = 'check';
	if($status == 1) $act = 'reject';
	if($status == 0) $act = 'recycle';
	$condition = "status=$status";
	if($keyword) $condition .= " AND `$key` LIKE '%$keyword%'";
	$result = $db->query("SELECT COUNT(`$key`) AS num,`$key` FROM ".get_table($mid)." WHERE $condition GROUP BY `$key` ORDER BY num DESC LIMIT 0,$num");
	while($r = $db->fetch_array($result)) {
		if($r['num'] < 2) continue;
		$r['kw'] = urlencode($r[$key]);
		$lists[] = $r;
	}
}
include tpl('repeat');
?>