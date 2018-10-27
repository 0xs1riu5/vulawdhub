<?php
require '../../../common.inc.php';
require 'init.inc.php';
$success = 0;
$DS = array();
if($_SESSION['tb_access_token']) {
	if($_SESSION['tb_openid'] && $_SESSION['tb_nickname']) {
		$success = 1;
		$openid = $_SESSION['tb_openid'];
		$nickname = $_SESSION['tb_nickname'];
		$avatar = '';
		$url = '';
		$DS = array('tb_access_token', 'tb_openid', 'tb_nickname');
	}
}
require '../destoon.inc.php';
?>