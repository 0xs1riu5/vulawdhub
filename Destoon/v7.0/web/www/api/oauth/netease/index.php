<?php
require '../../../common.inc.php';
require 'init.inc.php';
$success = 0;
$DS = array();
if($_SESSION['ne_access_token']) {	
	$url = NE_USERINFO_URL.'?access_token='.$_SESSION['ne_access_token'];
	$rec = dcurl($url);
	if(strpos($rec, 'userId') !== false) {
		$success = 1;
		$arr = json_decode($rec, true);
		$openid = $arr['userId'];
		$nickname = isset($arr['username']) ? $arr['username'] : $arr['userId'];
		$avatar = '';
		$url = '';
		$DS = array('ne_access_token');
	}
}
require '../destoon.inc.php';
?>