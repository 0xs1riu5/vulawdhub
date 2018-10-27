<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function dcloud($url) {
	$arr = explode('->', $url);
	$url = 'http://cloud.destoon.com/'.$arr[0].'/';
	$par = $arr[1].'&version='.DT_VERSION.'&release='.DT_RELEASE.'&charset='.DT_CHARSET.'&domain='.(DT_DOMAIN ? DT_DOMAIN : DT_PATH).'&uid='.DT_CLOUD_UID.'&auth='.encrypt($arr[1], DT_CLOUD_KEY);
	return dcurl($url, $par);
}

function mobile2area($mobile) {
	if(!is_mobile($mobile)) return 'Unknown';
	$cid = DT_ROOT.'/file/cloud/mobile/'.substr($mobile, 0, 3).'/'.substr($mobile, 3, 4).'/'.$mobile.'.php';
	if(is_file($cid) && DT_TIME - filemtime($cid) < 86400*90) {
		$rec = substr(file_get($cid), 13);
	} else {
		$rec = dcloud('mobile->mobile='.$mobile);
		if(substr($rec, 0, 4) !== 'ERR:') file_put($cid, '<?php exit;?>'.$rec);
	}
	return $rec ? $rec : 'Unknown';
}
?>