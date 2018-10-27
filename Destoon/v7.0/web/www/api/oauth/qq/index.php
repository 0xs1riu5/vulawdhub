<?php
require '../../../common.inc.php';
require 'init.inc.php';
$success = 0;
$DS = array();
if($_SESSION['qq_access_token']) {
	$par = 'access_token='.$_SESSION['qq_access_token'];
	$rec = dcurl(QQ_ME_URL, $par);
	if(strpos($rec, 'client_id') !== false) {
		$rec = str_replace('callback(', '', $rec);
		$rec = str_replace(');', '', $rec);
		$rec = trim($rec);
		$arr = json_decode($rec, true);
		$openid = $arr['openid'];		
		if($OAUTH[$site]['sync']) set_cookie('qq_openid', encrypt($openid, DT_KEY.'QQID'), $DT_TIME + $_SESSION['qq_access_time']);
		$par = 'access_token='.$_SESSION['qq_access_token'].'&oauth_consumer_key='.QQ_ID.'&openid='.$openid;
		$rec = dcurl(QQ_USERINFO_URL, $par);
		if(strpos($rec, 'nickname') !== false) {
			$success = 1;
			$arr = json_decode($rec, true);
			$nickname = $arr['nickname'];
			$avatar = $arr['figureurl_2'];
			$url = '';
			$DS = array('qq_access_token', 'qq_access_time', 'state');
		}
	}
}
require '../destoon.inc.php';
?>