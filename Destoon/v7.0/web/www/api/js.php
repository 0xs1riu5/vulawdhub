<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
$_SERVER['REQUEST_URI'] = '';
require '../common.inc.php';
header("Content-type:text/javascript");
check_referer() or exit('document.write("Invalid Referer");');
$tag = isset($auth) ? decrypt($auth) : '';
$tag or exit('document.write("Invalid Parameter");');
is_file(DT_ROOT.'/file/script/0'.md5($tag).'.js') or exit('document.write("Invalid Script");');
$tag = strip_sql($tag);
foreach(array($DT_PRE, '#', '$', '%', '&amp;', 'table', 'fields', 'password', 'payword', 'debug') as $v) {
	strpos($tag, $v) === false or exit('document.write("Invalid Tag");');
}
ob_start();
tag($tag);
$data = ob_get_contents();
ob_clean();
echo 'document.write(\''.dwrite($data ? $data : 'No Data').'\');';
?>