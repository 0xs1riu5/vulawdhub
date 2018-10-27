<?php
require '../../../common.inc.php';
require 'init.inc.php';
$success = 0;
$DS = array();
if($_SESSION['wx_access_token']) {
	$par = 'access_token='.$_SESSION['wx_access_token']
		 . '&openid='.$_SESSION['wx_openid'];
	$rec = dcurl(WX_USERINFO_URL, $par);
	if(strpos($rec, 'nickname') !== false) {
		$success = 1;
		$arr = json_decode($rec, true);
		$openid = $arr['openid'];
		$nickname = $arr['nickname'];
		$avatar = $arr['headimgurl'];
		$url = '';
		$DS = array('wx_access_token', 'wx_openid');
	}
}
require '../destoon.inc.php';
?>