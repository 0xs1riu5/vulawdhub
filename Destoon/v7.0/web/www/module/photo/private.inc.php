<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
$modurl = $DT_PC ? $MOD['linkurl'] : $MOD['mobile'];
$itemid or dheader($modurl);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
$item or dheader($modurl);
$item['status'] == 3 or dheader($modurl);
if($item['open'] == 3) dheader($modurl.$item['linkurl']);
extract($item);
$pass = false;
$_key = $open == 2 ? $password : $answer;
$error = '';
if($submit) {
	if(isset($key) && $key == $_key) {
		$pass = true;
		set_cookie('photo_'.$itemid, md5(md5(DT_IP.$open.$_key.DT_KEY.'PHOTO')), $DT_TIME + 86400);
	} else {
		$error = $open == 2 ? $L['error_password'] : $L['error_answer'];
	}
} else {
	$str = get_cookie('photo_'.$itemid);
	if($str && $str == md5(md5(DT_IP.$open.$_key.DT_KEY.'PHOTO'))) $pass = true;
	if($_username && $_username == $username) $pass = true;
}
if($pass == true) dheader($modurl.'show.php?itemid='.$itemid.'&page='.$page.'#p');
$CAT = get_cat($catid);
$seo_file = 'show';
include DT_ROOT.'/include/seo.inc.php';
$seo_title = $L['private_title'].$seo_delimiter.$seo_title;
$template = $MOD['template_private'] ? $MOD['template_private'] : 'private';
if($DT_PC) {	
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	$foot = '';
	$back_link = $modurl.$item['linkurl'];
}
include template($template, $module);
?>