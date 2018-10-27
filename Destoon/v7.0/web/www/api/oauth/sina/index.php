<?php
require '../../../common.inc.php';
require 'init.inc.php';
$success = 0;
$DS = array();
if($_SESSION['token']) {
	$c = new SaeTClientV2(WB_AKEY, WB_SKEY, $_SESSION['token']['access_token']);
	$ms  = $c->home_timeline();
	$uid_get = $c->get_uid();
	$uid = $uid_get['uid'];
	$me = $c->show_user_by_id($uid);
	if(isset($me['error'])) dalert('API Error:'.$me['error'], $MODULE[2]['linkurl'].$DT['file_login']);
	if($me && isset($me['screen_name'])) {
		$success = 1;
		$openid = $me['id'];
		$nickname = $me['screen_name'];
		$avatar = $me['profile_image_url'];
		$url = $me['url'];
		$DS = array('token');
	}
}
require '../destoon.inc.php';
?>