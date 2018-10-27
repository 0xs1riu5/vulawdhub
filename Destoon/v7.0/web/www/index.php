<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
require 'common.inc.php';
$username = $domain = '';
if(isset($homepage) && check_name($homepage)) {
	$username = $homepage;
} else if(!$cityid) {
	$host = get_env('host');
	if(substr($host, 0, 4) == 'www.') {
		$whost = $host;
		$host = substr($host, 4);
	} else {
		$whost = $host;
	}
	if($host && strpos(DT_PATH, $host) === false) {
		if(substr($host, -strlen($CFG['com_domain'])) == $CFG['com_domain']) {
			$www = substr($host, 0, -strlen($CFG['com_domain']));
			if(check_name($www)) {
				$username = $homepage = $www;
			} else {
				include load('company.lang');
				$head_title = $L['not_company'];
				if($DT_BOT) dhttp(404, $DT_BOT);
				include template('com-notfound', 'message');
				exit;
			}
		} else {
			if($whost == $host) {//301 xxx.com to www.xxx.com
				$w3 = 'www.'.$host;
				$c = $db->get_one("SELECT userid FROM {$DT_PRE}company WHERE domain='$w3'");
				if($c) d301('http://'.$w3);
			}
			$c = $db->get_one("SELECT username,domain FROM {$DT_PRE}company WHERE domain='$whost'".($host == $whost ? '' : " OR domain='$host'"), 'CACHE');
			if($c) {
				$username = $homepage = $c['username'];
				$domain = $c['domain'];
			}
		}
	}
}
if($username) {
	$moduleid = 4;
	$module = 'company';
	$MOD = cache_read('module-'.$moduleid.'.php');
	include load('company.lang');
	require DT_ROOT.'/module/'.$module.'/common.inc.php';
	include DT_ROOT.'/module/'.$module.'/init.inc.php';
} else {
	if($DT['safe_domain']) {
		$safe_domain = explode('|', $DT['safe_domain']);
		$pass_domain = false;
		foreach($safe_domain as $v) {
			if(strpos($DT_URL, $v) !== false) { $pass_domain = true; break; }
		}
		$pass_domain or dhttp(404);
	}
	if($DT['index_html']) {
		$html_file = $CFG['com_dir'] ? DT_ROOT.'/'.$DT['index'].'.'.$DT['file_ext'] : DT_CACHE.'/index.inc.html';
		if(!is_file($html_file)) tohtml('index');		
		if(is_file($html_file)) exit(include($html_file));
	}
	$AREA or $AREA = cache_read('area.php');
	if($EXT['mobile_enable']) $head_mobile = DT_MOB;
	$index = 1;
	$seo_title = $DT['seo_title'];
	$head_keywords = $DT['seo_keywords'];
	$head_description = $DT['seo_description'];
	$CSS = array('index');
	if($city_template) {
		include template($city_template, 'city');
	} else {		
		include template('index');
	}
}
?>