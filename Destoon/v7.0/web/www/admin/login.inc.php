<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$DT_LICENSE = md5(file_get(DT_ROOT.'/license.txt'));
$DT_LICENSE == '0f974f89aa216d38ed232b0ccb957614' or msg('网站根目录license.txt不允许修改或删除，请检查');
$forward or $forward = '?action=dashboard';
if($_destoon_admin && $_userid && $_destoon_admin == $_userid) dheader($forward);
if($DT['admin_area']) {
	$AA = explode("|", trim($DT['admin_area']));
	$A = ip2area($DT_IP);
	$pass = false;
	foreach($AA as $v) {
		if(strpos($A, $v) !== false) { $pass = true; break; }
	}
	if(!$pass) dalert('未被允许的地区', $MODULE[2]['linkurl'].'logout.php?forward='.urlencode(DT_PATH));
}
if($DT['admin_ip']) {
	$IP = explode("|", trim($DT['admin_ip']));
	$pass = false;
	foreach($IP as $v) {
		if($v == $DT_IP) { $pass = true; break; }
		if(preg_match("/^".str_replace('*', '[0-9]{1,3}', $v)."$/", $DT_IP)) { $pass = true; break; }
	}
	if(!$pass) dalert('未被允许的IP段', $MODULE[2]['linkurl'].'logout.php?forward='.urlencode(DT_PATH));
}
$LOCK = cache_read($DT_IP.'.php', 'ban');
if($LOCK && $DT_TIME - $LOCK['time'] < 3600 && $LOCK['times'] >= 1) $DT['captcha_admin'] = 1;
if($DT['close']) $DT['captcha_admin'] = 0;
if($submit) {
	$msg = captcha($captcha, $DT['captcha_admin'], true);
	if($msg) msg('验证码填写错误');
	if(!check_name($username)) msg('请输入正确的用户名');
	if(strlen($password) < 6 || strlen($password) > 32) msg('请输入正确的密码');
	include load('member.lang');
	$MOD = cache_read('module-2.php');
	require DT_ROOT.'/include/module.func.php';
	require DT_ROOT.'/module/member/member.class.php';
	$do = new member;
	$user = $do->login($username, $password);
	if($user) {
		if($user['groupid'] != 1 || $user['admin'] < 1) dalert('您无权限访问后台', $MODULE[2]['linkurl'].'logout.php?forward='.urlencode(DT_PATH));
		if(!is_founder($user['userid'])) {
			if(($DT['admin_week'] && !check_period(','.$DT['admin_week'])) || ($DT['admin_hour'] && !check_period($DT['admin_hour']))) dalert('未被允许的管理时间', $MODULE[2]['linkurl'].'logout.php?forward='.urlencode(DT_PATH));
		}
		if($CFG['authadmin'] == 'cookie') {
			set_cookie($secretkey, $user['userid']);
		} else {
			$_SESSION[$secretkey] = $user['userid'];
		}
		require DT_ROOT.'/admin/admin.class.php';
		$admin = new admin;
		$admin->cache_right($user['userid']);
		$admin->cache_menu($user['userid']);
		if($DT['login_log']) $do->login_log($username, $password, $user['passsalt'], 1);
		dheader($forward);
	} else {
		if($DT['login_log']) $do->login_log($username, $password, $user['passsalt'], 1, $do->errmsg);
		msg($do->errmsg, '?file=login&forward='.urlencode($forward));
	}
} else {
	if(strpos($DT_URL, DT_PATH) === false) dheader(DT_PATH.basename(get_env('self')));
	$username = isset($username) ? $username : $_username;
	include tpl('login');
}
?>