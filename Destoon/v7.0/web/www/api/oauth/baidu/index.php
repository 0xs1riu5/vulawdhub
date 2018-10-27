<?php
require '../../../common.inc.php';
require 'init.inc.php';
$success = 0;
$DS = array();
if($_SESSION['bd_access_token']) {
	$par = 'access_token='.$_SESSION['bd_access_token'];
	$rec = dcurl(BD_USERINFO_URL, $par);
	if(strpos($rec, 'uname') !== false) {
		$success = 1;
		$arr = json_decode($rec, true);
		$openid = $arr['uid'];
		$nickname = $arr['uname'];
		$avatar = '';
		$url = '';
		$DS = array('bd_access_token');
	}
}
require '../destoon.inc.php';
?>