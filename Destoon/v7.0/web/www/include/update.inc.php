<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) return;
if($page == 1 && $MOD['hits']) {
	if($DT['cache_hits']) {
		 cache_hits($moduleid, $itemid);
	} else {
		$update .= ',hits=hits+1';
	}
}
if($update) $db->query("UPDATE LOW_PRIORITY {$table} SET ".substr($update, 1)." WHERE itemid=$itemid", 'UNBUFFERED');	
?>