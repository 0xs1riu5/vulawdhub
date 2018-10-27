<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
$pstr = '';
if(isset($_SERVER['UNENCODED_URL']) && strpos($_SERVER['QUERY_STRING'], '-htm-') !== false) $_SERVER['QUERY_STRING'] = substr($_SERVER['UNENCODED_URL'], strpos($_SERVER['UNENCODED_URL'], '-htm-') + 5);//IIS7+
if($_SERVER['QUERY_STRING']) {
	if(preg_match("/^(.*)\.html(\?(.*))*$/", $_SERVER['QUERY_STRING'], $_match)) {
		$pstr = $_match[1];
	} else if(preg_match("/^(.*)\/$/", $_SERVER['QUERY_STRING'], $_match)) {
		$pstr = $_match[1];
	}
} else if($_SERVER["REQUEST_URI"] != $_SERVER["SCRIPT_NAME"]) {
	$string = str_replace($_SERVER["SCRIPT_NAME"], '', $_SERVER["REQUEST_URI"]);
	if($string && preg_match("/^\/(.*)\/$/", $string, $_match)) $pstr = $_match[1];
}
if($pstr && strpos($pstr, '-') !== false) {
	$_GET = array();
	$pstr = explode('-', $pstr);
	$pstr_count = count($pstr);
	if($pstr_count%2 == 1) --$pstr_count;
	for($i = 0; $i < $pstr_count; $i++) { $_GET[$pstr[$i]] = $MQG ? addslashes($pstr[++$i]) : $pstr[++$i]; }
}
?>