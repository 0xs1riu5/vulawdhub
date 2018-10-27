<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
$_COOKIE = array();
require '../common.inc.php';
if($DT_BOT) dhttp(403);
$url = isset($url) ? trim($url) : '';
$name = isset($name) ? trim($name) : '';
strlen($url) > 15 or dheader(DT_PATH);
$ext = file_ext($url);
$ext or dheader(DT_PATH);
$name or dheader($url);
$ext == file_ext($name) or dheader(DT_PATH);
in_array($ext, array('rar', 'zip', 'gz', 'tar', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx')) or dheader($url);
strpos($url, DT_PATH.'file/upload/') === 0 or dheader($url);
$file = substr($url, strlen(DT_PATH.'file/upload/'));
$filename = substr($file, 0, -strlen($ext)-1);
preg_match("/^[0-9\-\/]{21,}$/", $filename) or dheader($url);
$localfile = DT_ROOT.'/file/upload/'.$file;
is_file($localfile) or dheader($url);
$title = substr($name, 0, -strlen($ext)-1);
$title = file_vname($title);
$title or dheader($url);
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false) $title = str_replace(' ', '_', $title);
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) $title = convert($title, DT_CHARSET, 'GBK');
$title or dheader($url);
file_down($localfile, $title.'.'.$ext);
?>