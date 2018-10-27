<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
if($DT['close']) {
	if($DT_BOT) dhttp(503);
	message($DT['close_reason'].'&nbsp;');
}
if($DT['defend_cc']) {
	if(!DT_WIN && file_exists('/proc/loadavg')) {
		if($fp = @fopen('/proc/loadavg', 'r')) {
			list($loadaverage) = explode(' ', fread($fp, 6));
			fclose($fp);
			if($loadaverage > $DT['defend_cc']) {
				if(defined('DT_TASK')) exit;
				header("HTTP/1.0 503 Service Unavailable");
				exit(include(DT_ROOT.'/api/503.php'));
			}
		}
	}
}
if($DT['defend_reload'] && !$DT_BOT) {
	$lastvisit = intval(decrypt(get_cookie('lastvisit'), DT_KEY.'LAST'));
	set_cookie('lastvisit', encrypt(DT_TIME, DT_KEY.'LAST'));
	if(DT_TIME - $lastvisit < $DT['defend_reload']) {
		if(defined('DT_TASK')) exit;
		message(lang('include->defend_reload', array($DT['defend_reload'])).'<script>setTimeout("this.location.reload();", '.($DT['defend_reload']*3000).');</script>');
	}
}
if($DT['defend_proxy']) {
	if((isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) || 
		(isset($_SERVER['HTTP_VIA']) && $_SERVER['HTTP_VIA']) || 
		(isset($_SERVER['HTTP_PROXY_CONNECTION']) && $_SERVER['HTTP_PROXY_CONNECTION']) || 
		(isset($_SERVER['HTTP_USER_AGENT_VIA']) && $_SERVER['HTTP_USER_AGENT_VIA']) || 
		(isset($_SERVER['HTTP_CACHE_INFO']) && $_SERVER['HTTP_CACHE_INFO']) || 
		(isset($_SERVER['HTTP_PROXY_CONNECTION']) && $_SERVER['HTTP_PROXY_CONNECTION'])) {
		if(defined('DT_TASK')) exit;
		message(lang('include->defend_proxy'));
	}
}
?>