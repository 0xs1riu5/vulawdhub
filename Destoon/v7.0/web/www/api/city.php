<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
require '../common.inc.php';
$DT['city'] or dheader(DT_PATH);
if($DT_BOT) dhttp(403);
if($action == 'go') {
	if(isset($auto)) {
		if($DT['city_ip']) {
			set_cookie('city', '');
		} else {
			$iparea = ip2area($DT_IP);
			$result = $db->query("SELECT * FROM {$DT_PRE}city");
			while($r = $db->fetch_array($result)) {
				if(preg_match("/".$r['name'].($r['iparea'] ? '|'.$r['iparea'] : '')."/i", $iparea)) {
					if($r['domain']) {
						dheader($r['domain']);
					} else {
						set_cookie('city', $r['areaid'].'|'.$r['domain'], $DT_TIME + 30*86400);
					}
					break;
				}
			}
		}
		dheader(DT_PATH);
	}
	$areaid = isset($areaid) ? intval($areaid) : 0;
	if($areaid) {
		$r = $db->get_one("SELECT areaid,name,domain,template FROM {$DT_PRE}city WHERE areaid=$areaid");
		if($r) {
			set_cookie('city', $r['areaid'].'|'.$r['domain'], $DT_TIME + 30*86400);
			$url = '';
			if($forward) {
				if(strpos($forward, DT_PATH) !== false) {
					if($r['domain']) {
						$url = str_replace(DT_PATH, $r['domain'], $forward);
					} else {
						$url = $forward;
					}
				} else if($city_domain && strpos($forward, $city_domain) !== false) {
					if($r['domain']) {
						$url = str_replace($city_domain, $r['domain'], $forward);
					} else {
						//$url = str_replace($city_domain, DT_PATH, $forward); For Module Subdomain
					}
				}
			}
			if(strpos($url, 'city.php') !== false) $url = '';
			dheader($url ? $url : DT_PATH);
		}
	}
	set_cookie('city', '0|', $DT_TIME + 30*86400);
	dheader(DT_PATH);
}
$lists = array();
$result = $db->query("SELECT areaid,name,style,domain,letter FROM {$DT_PRE}city ORDER BY letter,listorder");
while($r = $db->fetch_array($result)) {
	$r['linkurl'] = $r['domain'] ? $r['domain'] : '';
	$lists[strtoupper($r['letter'])][] = $r;
}
$head_title = $L['citytitle'];
if($EXT['mobile_enable']) $head_mobile = str_replace(DT_PATH, DT_MOB, $DT_URL);
include template('city', 'city');
?>