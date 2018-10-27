<?php
require '../../common.inc.php';
require DT_ROOT.'/include/mobile.inc.php';
$areaid = isset($areaid) ? intval($areaid) : 0;
if($areaid) {
	if($areaid == -2) {
		$iparea = ip2area($DT_IP);
		$result = $db->query("SELECT * FROM {$DT_PRE}city");
		while($r = $db->fetch_array($result)) {
			if(preg_match("/".$r['name'].($r['iparea'] ? '|'.$r['iparea'] : '')."/i", $iparea)) {
				set_cookie('city', $r['areaid'].'|'.$r['domain'], $DT_TIME + 30*86400);
				exit('ok');
			}
		}
	} else if($areaid == -1) {
		set_cookie('city', '0|', $DT_TIME + 30*86400);
		exit('ok');
	} else {
		$r = $db->get_one("SELECT areaid,name,domain,template FROM {$DT_PRE}city WHERE areaid=$areaid");
		if($r) {
			set_cookie('city', $r['areaid'].'|'.$r['domain'], $DT_TIME + 30*86400);
			exit('ok');
		}
	}
	exit('ko');
}
$lists = array();
$result = $db->query("SELECT areaid,name,style,domain,letter FROM {$DT_PRE}city ORDER BY letter,listorder");
while($r = $db->fetch_array($result)) {
	$r['linkurl'] = $r['domain'] ? $r['domain'] : '';
	$lists[strtoupper($r['letter'])][] = $r;
}
$head_title = $L['city_title'];
$foot = '';
include template('city', 'city');
?>