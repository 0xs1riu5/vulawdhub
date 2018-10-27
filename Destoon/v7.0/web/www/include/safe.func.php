<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function dhtmlspecialchars($string) {
	if(is_array($string)) {
		return array_map('dhtmlspecialchars', $string);
	} else {
		$string = htmlspecialchars($string, ENT_QUOTES, DT_CHARSET == 'GBK' ? 'GB2312' : 'UTF-8');
		return str_replace('&amp;', '&', $string);
	}
}

function dsafe($string, $type = 1) {
	if(is_array($string)) {
		return array_map('dsafe', $string);
	} else {
		if($type) {
			$string = str_replace('<em></em>', '', $string);
			$string = preg_replace("/\<\!\-\-([\s\S]*?)\-\-\>/", "", $string);
			$string = preg_replace("/\/\*([\s\S]*?)\*\//", "", $string);
			$string = preg_replace("/&#([a-z0-9]{1,})/i", "<em></em>&#\\1", $string);
			$match = array("/s[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t/i","/d[\s]*a[\s]*t[\s]*a[\s]*\:/i","/b[\s]*a[\s]*s[\s]*e/i","/e[\\\]*x[\\\]*p[\\\]*r[\\\]*e[\\\]*s[\\\]*s[\\\]*i[\\\]*o[\\\]*n/i","/i[\\\]*m[\\\]*p[\\\]*o[\\\]*r[\\\]*t/i","/on([a-z]{2,})([\(|\=|\s]+)/i","/about/i","/frame/i","/link/i","/meta/i","/textarea/i","/eval/i","/alert/i","/confirm/i","/prompt/i","/cookie/i","/document/i","/newline/i","/colon/i","/<style/i","/\\\x/i");
			$replace = array("s<em></em>cript","da<em></em>ta:","ba<em></em>se","ex<em></em>pression","im<em></em>port","o<em></em>n\\1\\2","a<em></em>bout","f<em></em>rame","l<em></em>ink","me<em></em>ta","text<em></em>area","e<em></em>val","a<em></em>lert","/con<em></em>firm/i","prom<em></em>pt","coo<em></em>kie","docu<em></em>ment","new<em></em>line","co<em></em>lon","<sty1e","\<em></em>x");
			return str_replace(array('isShowa<em></em>bout', 'co<em></em>ntrols'), array('isShowAbout', 'controls'), preg_replace($match, $replace, $string));
		} else {
			return str_replace(array('<em></em>', '<sty1e'), array('', '<style'), $string);
		}
	}
}

function strip_sql($string, $type = 1) {
	if(is_array($string)) {
		return array_map('strip_sql', $string);
	} else {
		if($type) {
			$string = preg_replace("/\/\*([\s\S]*?)\*\//", "", $string);
			$string = preg_replace("/0x([a-f0-9]{2,})/i", '0&#120;\\1', $string);
			$string = preg_replace_callback("/(select|update|replace|delete|drop)([\s\S]*?)(".DT_PRE."|from)/i", 'strip_wd', $string);
			$string = preg_replace_callback("/(load_file|substring|substr|reverse|trim|space|left|right|mid|lpad|concat|concat_ws|make_set|ascii|bin|oct|hex|ord|char|conv)([^a-z]?)\(/i", 'strip_wd', $string);
			$string = preg_replace_callback("/(union|where|having|outfile|dumpfile|".DT_PRE.")/i", 'strip_wd', $string);
			return $string;
		} else {
			return str_replace(array('&#95;','&#100;','&#101;','&#103;','&#105;','&#109;','&#110;','&#112;','&#114;','&#115;','&#116;','&#118;','&#120;'), array('_','d','e','g','i','m','n','p','r','s','t','v','x'), $string);
		}
	}
}

function strip_wd($m) {
	if(is_array($m) && isset($m[1])) {
		$wd = substr($m[1], 0, -1).'&#'.ord(strtolower(substr($m[1], -1))).';';
		if(isset($m[3])) return $wd.$m[2].$m[3];
		if(isset($m[2])) return $wd.$m[2].'(';
		return $wd;
	}
	return '';
}

function strip_uri($uri) {
	if(strpos($uri, '%') !== false) {
		while($uri != urldecode($uri)) {
			$uri = urldecode($uri);
		}
	}
	if(strpos($uri, '<') !== false || strpos($uri, "'") !== false || strpos($uri, '"') !== false || strpos($uri, '0x') !== false) {
		dhttp(403, 0);
		dalert('HTTP 403 Forbidden - Bad URL', DT_PATH);
	}
}

function strip_kw($kw, $max = 0) {
	$kw = dhtmlspecialchars(trim(urldecode($kw)));
	if($kw) {
		if(strpos($kw, '%') !== false) return '';
		$kw = str_replace(array("'", '&'), array('', ''), $kw);
		$max = intval($max);
		if($max > 0 && strlen($kw) > $max) $kw = dsubstr($kw, $max);
	}
	return $kw;
}

function strip_key($array) {
	foreach($array as $k=>$v) {
		if(!preg_match("/^[a-z0-9_\-]{1,64}$/i", $k)) {
			dhttp(403, 0);
			dalert('HTTP 403 Forbidden - Bad Data', DT_PATH);
		}
		if(is_array($v)) strip_key($v);
	}
}

function strip_str($string) {
	return str_replace(array('\\','"', "'"), array('', '', ''), $string);
}
?>