<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
$_COOKIE = array();
require '../common.inc.php';
if($DT_BOT) dhttp(403);
$URL = DT_PATH;
if($moduleid > 3 && !$MODULE[$moduleid]['islink']) {
	if($kw) {
		$qstr = str_replace('moduleid='.$moduleid.'&', '', $_SERVER['QUERY_STRING']);
		$qstr = str_replace('moduleid='.$moduleid, '', $qstr);
		$spread = isset($spread) ? intval($spread) : 0;
		if($spread) {
			$r = $db->get_one("SELECT tid FROM {$DT_PRE}spread WHERE mid=$moduleid AND word='$kw' AND fromtime<$DT_TIME AND totime>$DT_TIME ORDER BY price DESC,itemid ASC");
			if($r) {
				$id = $moduleid == 4 ? 'userid' : 'itemid';
				$t = $db->get_one("SELECT linkurl FROM ".get_table($moduleid)." WHERE `{$id}`=$r[tid]");
				if($t) dheader(strpos($t['linkurl'], '://') !== false ? $t['linkurl'] : $MOD['linkurl'].$t['linkurl']);
			}
			dheader($EXT['spread_url'].rewrite('index.php?kw='.urlencode($kw)));
		} else {
			$qstr = str_replace('spread=0', '', $qstr);
		}
		if($qstr) {
			if(substr($qstr, 0, 1) == '&') $qstr = substr($qstr, 1);
			$URL = $MOD['linkurl'].'search.php?'.$qstr;
		} else {
			$URL = $MOD['linkurl'].'search.php';
		}
	} else {
		$URL = $MOD['linkurl'].'search.php';
	}
}
if(substr($URL, -1, 1) == '&') $URL = substr($URL, 0, -1);
dheader($URL);
?>