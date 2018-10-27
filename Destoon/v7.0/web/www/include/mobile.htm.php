<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
require_once DT_ROOT.'/include/mobile.func.php';
include load('mobile.lang');
$UA = strtoupper($_SERVER['HTTP_USER_AGENT']);
$back_link = $head_link = $head_name = $pages = '';
$areaid = isset($areaid) ? intval($areaid) : 0;
$site_name = $EXT['mobile_sitename'] ? $EXT['mobile_sitename'] : $DT['sitename'].$L['mobile_version'];
$DT['sitename'] = $site_name;
$DT_PC = $GLOBALS['DT_PC'] = 0;
$MURL = $MODULE[2]['linkurl'];
if($DT_MOB['browser'] == 'screen' && $_username) $MURL = DT_PATH.'api/mobile.php?action=sync&auth='.encrypt($_username.'|'.$DT_IP.'|'.$DT_TIME, DT_KEY.'SCREEN').'&goto=';
$_cart = 0;
$share_icon = ($DT_MOB['browser'] == 'weixin' || $DT_MOB['browser'] == 'qq') ? DT_PATH.'apple-touch-icon-precomposed.png' : '';
$MOB_MODULE = array();
foreach($MODULE as $v) {
	if($v['moduleid'] > 3 && $v['ismenu'] && !$v['islink']) $MOB_MODULE[] = $v;
}
$foot = 'channel';
?>