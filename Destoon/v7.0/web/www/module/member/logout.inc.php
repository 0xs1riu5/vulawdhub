<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/module/'.$module.'/member.class.php';
$do = new member;
$do->logout();
$session = new dsession();
session_destroy();
if($DT_PC) {
	$forward or $forward = DT_PATH;
} else {
	$forward = DT_MOB.'my.php';
}
$action = 'logout';
$api_msg = $api_url = '';
if($MOD['passport']) {
	include DT_ROOT.'/api/'.$MOD['passport'].'.inc.php';
	if($api_url) $forward = $api_url;
}
#if($MOD['sso']) include DT_ROOT.'/api/sso.inc.php';
if($api_msg) message($api_msg, $forward, -1);
message($api_msg, $forward);
?>