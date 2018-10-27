<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
$AREA = cache_read('area.php');
$c = array();
$city = get_cookie('city');
$http_host = get_env('host');
if($city) {
	list($cityid, $city_domain) = explode('|', $city);
	$cityid = intval($cityid);
	if(strpos(DT_PATH, $http_host) === false && strpos($city_domain, $http_host) === false) {
		$c = $db->get_one("SELECT * FROM {$DT_PRE}city WHERE domain='http://".$http_host."/'");
		if($c) {
			set_cookie('city', $c['areaid'].'|'.$c['domain'], DT_TIME + 86400*30);
			$cityid = $c['areaid'];
		}
	}
	if($city_domain && substr($http_host, 0 ,4) == 'www.') {
		$cityid = 0;
		$city_domain = '';
		set_cookie('city', '');
	}
	if($city_domain && $DT_URL == DT_PATH) dheader($city_domain);
} else {
	$cityid = 0;
	if(strpos(DT_PATH, $http_host) === false) {
		$c = $db->get_one("SELECT * FROM {$DT_PRE}city WHERE domain='http://".$http_host."/'");
		if($c) {
			set_cookie('city', $c['areaid'].'|'.$c['domain'], $DT_TIME + 30*86400);
			$cityid = $c['areaid'];
		}
	}
	if($DT['city_ip'] && !defined('DT_ADMIN') && !$DT_BOT && !$cityid) {
		$iparea = ip2area($DT_IP);
		$result = $db->query("SELECT * FROM {$DT_PRE}city ORDER BY areaid");
		while($r = $db->fetch_array($result)) {
			if(preg_match("/".$r['name'].($r['iparea'] ? '|'.$r['iparea'] : '')."/i", $iparea)) {
				set_cookie('city', $r['areaid'].'|'.$r['domain'], $DT_TIME + 30*86400);
				$cityid = $r['areaid'];
				if($r['domain']) dheader($r['domain']);
				$c = $r;
				break;
			}
		}
	}
}
if($cityid) {
	$c or $c = $db->get_one("SELECT * FROM {$DT_PRE}city WHERE areaid=$cityid");
	if(!defined('DT_ADMIN')) {
		if($c['seo_title']) {		
			$DT['seo_title'] = $city_sitename = $c['seo_title'];
		} else {
			$citysite = lang($L['citysite'], array($c['name']));
			$DT['seo_title'] = $citysite.$DT['seo_delimiter'].$DT['seo_title'];
			$city_sitename = $citysite.$DT['seo_delimiter'].$DT['sitename'];
		}
		if($c['seo_keywords']) $DT['seo_keywords'] = $c['seo_keywords'];
		if($c['seo_description']) $DT['seo_description'] = $c['seo_description'];
	}
	$city_name = $c['name'];
	$city_domain = $c['domain'];
	$city_template = $c['template'];
}
if($city_domain) {
	foreach($MODULE as $k=>$v) {
		if($v['islink']) continue;
		$MODULE[$k]['linkurl'] = $k == 1 ? $city_domain : $city_domain.$v['moduledir'].'/';
		$MODULE[$k]['mobile'] = $k == 1 ? $city_domain.'mobile/' : $city_domain.'mobile/'.$v['moduledir'].'/';
	}
	$MOD['linkurl'] = $MODULE[$moduleid]['linkurl'];
	foreach($EXT as $k=>$v) {
		if(strpos($k, '_url') !== false) {
			$EXT[$k] = $city_domain.str_replace('_url', '', $k).'/';
		}
	}
}
?>