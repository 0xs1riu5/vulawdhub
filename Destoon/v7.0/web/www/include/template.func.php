<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function template_compile($from, $to) {
	$content = template_parse(file_get($from));
	file_put($to, $content);
}

function template_parse($str) {
	$str = preg_replace("/\<\!\-\-\[(.+?)\]\-\-\>/", "", $str);
	$str = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $str);
	$str = preg_replace("/\{template\s+([^\}]+)\}/", "<?php include template(\\1);?>", $str);
	$str = preg_replace("/\{php\s+(.+)\}/", "<?php \\1?>", $str);
	$str = preg_replace("/\{if\s+(.+?)\}/", "<?php if(\\1) { ?>", $str);
	$str = preg_replace("/\{else\}/", "<?php } else { ?>", $str);
	$str = preg_replace("/\{elseif\s+(.+?)\}/", "<?php } else if(\\1) { ?>", $str);
	$str = preg_replace("/\{\/if\}/", "<?php } ?>\r\n", $str);
	$str = preg_replace("/\{loop\s+(\S+)\s+(\S+)\}/", "<?php if(is_array(\\1)) { foreach(\\1 as \\2) { ?>", $str);
	$str = preg_replace("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>", $str);
	$str = preg_replace("/\{\/loop\}/", "<?php } } ?>", $str);
	$str = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str);
	$str = preg_replace_callback("/<\?php([^\?]+)\?>/s", "template_addquote1", $str);
	$str = preg_replace("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\+\-\x7f-\xff]*)\}/", "<?php echo \\1;?>", $str);
	$str = preg_replace_callback("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\+\-\x7f-\xff]+)\}/s", "template_addquote2", $str);
	$str = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $str);
	$str = preg_replace("/\'([A-Za-z]+)\[\'([A-Za-z\.]+)\'\](.?)\'/s", "'\\1[\\2]\\3'", $str);
	$str = preg_replace("/(\r?\n)\\1+/", "\\1", $str);
	$str = str_replace("\t", '', $str);
	$str = "<?php defined('IN_DESTOON') or exit('Access Denied');?>".trim($str);
	return $str;
}

function template_addquote1($matches) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $matches[0]));
}

function template_addquote2($matches) {
	return '<?php echo '.str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $matches[1])).';?>';
}
?>