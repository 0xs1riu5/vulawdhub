<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
$_COOKIE = array();
require '../common.inc.php';
$url = DT_PATH;
if($wd) $url = 'http://xin.baidu.com/s?q='.urlencode(strip_tags($wd));
dheader($url);
?>