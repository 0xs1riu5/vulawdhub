<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function del_token($arr) {
	if($arr) {
		foreach($arr as $v) {
			$_SESSION[$v] = '';
		}
	}
}
if($success) {
	$U = $db->get_one("SELECT * FROM {$DT_PRE}oauth WHERE openid='$openid' AND site='$site'");
	if($U) {
		$update = "logintimes=logintimes+1,logintime=$DT_TIME";
		if($_username && $U['username'] != $_username) $update .= ",username='$_username'";
		if($U['nickname'] != $nickname) $update .= ",nickname='".addslashes($nickname)."'";
		if($U['avatar'] != $avatar) $update .= ",avatar='".addslashes($avatar)."'";
		if($U['url'] != $url) $update .= ",url='".addslashes($url)."'";
		$db->query("UPDATE {$DT_PRE}oauth SET {$update} WHERE itemid=$U[itemid]");
	} else {
		$db->query("INSERT INTO {$DT_PRE}oauth (username,site,openid,nickname,avatar,url,addtime,logintime,logintimes) VALUES ('$_username','$site','".addslashes($openid)."','".addslashes($nickname)."','".addslashes($avatar)."','".addslashes($url)."','$DT_TIME','$DT_TIME','1')");
		$U = array();
		$U['itemid'] = $db->insert_id();
		$U['username'] = $_username;
		$U['site'] = $site;
		$U['openid'] = $openid;
		$U['nickname'] = $nickname;
		$U['avatar'] = $avatar;
		$U['url'] = $url;
		$U['addtime'] = $DT_TIME;
		$U['logintime'] = $DT_TIME;
		$U['logintimes'] = 1;
	}
	if($_userid) {
		del_token($DS);
		dheader($MODULE[2]['linkurl'].'oauth.php');
	} else {
		if($U['username']) {
			include load('member.lang');
			$MOD = cache_read('module-2.php');
			include DT_ROOT.'/include/module.func.php';
			include DT_ROOT.'/module/member/member.class.php';
			$do = new member;
			$user = $do->login($U['username'], '', 0, true);
			if($user) {
				if(strpos($forward, 'api/oauth') !== false) $forward = '';
				$forward or $forward = $DT_PC ? $MODULE[2]['linkurl'] : $MODULE[2]['mobile'];
				del_token($DS);
				$api_msg = '';
				if($MOD['passport'] == 'uc') {				
					$action = 'oauth';
					$passport = $user['passport'];
					include DT_ROOT.'/api/'.$MOD['passport'].'.inc.php';
				}
				if($api_msg) message($api_msg, $forward, -1);
				dheader($forward);
			} else {
				message($do->errmsg, $MODULE[2]['linkurl'].$DT['file_login']);
			}
		} else {
			set_cookie('bind', encrypt($U['itemid'].'|'.$site, DT_KEY.'BIND'));
			if(DT_TOUCH) {
				dheader($MODULE[2]['mobile'].'oauth.php?action=bind');
			} else {
				if(!get_cookie('oauth_site')) {
					set_cookie('oauth_user', $nickname);
					set_cookie('oauth_site', $site);
					dheader(DT_PATH);
				}				
				dheader($MODULE[2]['linkurl'].'oauth.php?action=bind');
			}
		}
	}
} else {
	del_token($DS);
	set_cookie('oauth_user', '');
	set_cookie('oauth_site', '');
	dheader($MODULE[2]['linkurl'].$DT['file_login'].'?error=oauth&step=userinfo&site='.$site);
}
?>