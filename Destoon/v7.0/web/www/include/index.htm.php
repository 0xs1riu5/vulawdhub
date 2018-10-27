<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
if($DT['city']) {
	$DT['index_html'] = 0;
	$C = cache_read('module-2.php');
	$M = $C['linkurl'];
} else {
	$M = $MODULE[2]['linkurl'];
}
$data = '';
$data .= 'var DTPath = "'.DT_PATH.'";';
$data .= 'var DTMob = "'.DT_MOB.'";';
$data .= 'var SKPath = "'.DT_SKIN.'";';
$data .= 'var MEPath = "'.$M.'";';
$data .= 'var DTEditor = "'.DT_EDITOR.'";';
$data .= 'var CKDomain = "'.$CFG['cookie_domain'].'";';
$data .= 'var CKPath = "'.$CFG['cookie_path'].'";';
$data .= 'var CKPrex = "'.$CFG['cookie_pre'].'";';
file_put(DT_ROOT.'/file/script/config.js', $data);
$filename = $CFG['com_dir'] ? DT_ROOT.'/'.$DT['index'].'.'.$DT['file_ext'] : DT_CACHE.'/index.inc.html';
if(!$DT['index_html']) {
	if(is_file($filename)) unlink($filename);
	return false;
}
if(!$db->linked) return false;
$destoon_task = "moduleid=1&html=index";
$AREA = cache_read('area.php');
if($EXT['mobile_enable']) $head_mobile = $EXT['mobile_url'];
$index = 1;
$seo_title = $DT['seo_title'];
$head_keywords = $DT['seo_keywords'];
$head_description = $DT['seo_description'];
$CSS = array('index');
ob_start();
include template('index');
$data = ob_get_contents();
ob_clean();
file_put($filename, $data);
return true;
?>