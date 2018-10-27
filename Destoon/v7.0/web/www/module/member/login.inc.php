<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($_userid && !$MOD['passport']) dheader($DT_PC ? $MOD['linkurl'] : DT_MOB.'my.php');
require DT_ROOT.'/include/post.func.php';
require DT_ROOT.'/module/'.$module.'/member.class.php';
$do = new member;
$forward or $forward = $DT_PC ? $MOD['linkurl'] : DT_MOB.'my.php';
$_forward = $forward ? urlencode($forward) : '';
$OAUTH = cache_read('oauth.php');
$could_sms = ($MOD['login_sms'] && $DT['sms']) ? 1 : 0;
$could_scan = ($MOD['login_scan'] && $OAUTH['wechat']['enable'] && $DT_PC) ? 1 : 0;
switch($action) {
	case 'sms':
		$could_sms or dheader('?action=login&forward='.$_forward);
		if($submit) {
			$session = new dsession();
			(is_mobile($mobile) && preg_match("/^[0-9]{6}$/", $code) && isset($_SESSION['mobile_code']) && $_SESSION['mobile_code'] == md5($mobile.'|'.$code)) or message('验证失败');
			$user = $db->get_one("SELECT username,groupid FROM {$DT_PRE}member WHERE mobile='$mobile' AND vmobile=1 ORDER BY userid");
			($user && !in_array($user['groupid'], array(2,3,4))) or message($L['login_msg_bad_mobile']);
			$_SESSION['mobile_code'] = '';
			$cookietime = $MOD['login_time'] >= 86400 ? $MOD['login_time'] : 0;
			$username = $user['username'];
			$password = $code;
			$user = $do->login($username, $password, $cookietime, true);
			if($user) {
				dheader($forward);
			} else {
				if($DT['login_log'] == 2) $do->login_log($username, $password, $user['passsalt'], 0, $do->errmsg);
				message($do->errmsg);
			}
		}
	break;
	case 'send':
		$could_sms or exit('close');
		is_mobile($mobile) or exit('format');
		$msg = captcha($captcha, 1, true);
		if($msg) exit('captcha');
		$user = $db->get_one("SELECT groupid FROM {$DT_PRE}member WHERE mobile='$mobile' AND vmobile=1 ORDER BY userid");
		($user && !in_array($user['groupid'], array(2,3,4))) or exit('exist');
		$session = new dsession();
		isset($_SESSION['mobile_send']) or $_SESSION['mobile_send'] = 0;
		isset($_SESSION['mobile_time']) or $_SESSION['mobile_time'] = 0;
		if($_SESSION['mobile_send'] > 4) exit('max');
		if($_SESSION['mobile_time'] && (($DT_TIME - $_SESSION['mobile_time']) < 180)) exit('fast');
		if(max_sms($mobile)) exit('max');
		$mobilecode = random(6, '0123456789');
		$_SESSION['mobile_code'] = md5($mobile.'|'.$mobilecode);
		$_SESSION['mobile_time'] = $DT_TIME;
		$_SESSION['mobile_send'] = $_SESSION['mobile_send'] + 1;
		$content = lang('sms->sms_code', array($mobilecode, $MOD['auth_days']*10)).$DT['sms_sign'];
		send_sms($mobile, $content);
		#log_write($content, 'sms', 1);
		exit('ok');
	break;
	case 'scan':
		$could_scan or dheader('?action=login&forward='.$_forward);
		require DT_ROOT.'/api/oauth/wechat/init.inc.php';
	break;
	default:
		$LOCK = cache_read($DT_IP.'.php', 'ban');
		if($LOCK && $DT_TIME - $LOCK['time'] < 3600 && $LOCK['times'] >= 2) $MOD['captcha_login'] = 1;
		isset($auth) or $auth = '';
		if($_userid) $auth = '';
		if($auth) {
			$auth = decrypt($auth, DT_KEY.'LOGIN');
			$_auth = explode('|', $auth);
			if($_auth[0] == 'LOGIN' && check_name($_auth[1]) && strlen($_auth[2]) >= $MOD['minpassword'] && $DT_TIME >= intval($_auth[3]) && $DT_TIME - intval($_auth[3]) < 30) {
				$submit = 1;
				$username = $_auth[1];
				$password = $_auth[2];
				$MOD['captcha_login'] = $captcha = 0;
			}
		}
		$action = 'login';
		if($submit) {
			captcha($captcha, $MOD['captcha_login']);
			$username = trim($username);
			$password = trim($password);
			if(strlen($username) < 3) message($L['login_msg_username']);
			if(strlen($password) < 5) message($L['login_msg_password']);
			$goto = isset($goto) ? true : false;
			if($goto) $forward = $MOD['linkurl'];
			$api_msg = $api_url = '';
			$cookietime = $MOD['login_time'] >= 86400 ? $MOD['login_time'] : 0;
			$option = 'username';
			if(is_email($username)) {
				$option = 'email';
			} else if(is_mobile($username)) {
				$option = 'mobile';
			} else if(!check_name($username)) {
				$option = 'passport';
			}
			in_array($option, array('username', 'passport', 'email', 'mobile', 'company', 'userid')) or $option = 'username';
			$r = $db->get_one("SELECT username,passport FROM {$DT_PRE}member WHERE `$option`='$username'");
			if($r) {
				$username = $r['username'];
				$passport = $r['passport'];
			} else {
				if($option == 'username' || $option == 'passport') {
					$passport = $username;
					if($option == 'username' && $MOD['passport']) {
						$r = $db->get_one("SELECT username FROM {$DT_PRE}member WHERE `passport`='$username'");
						if($r) $username = $r['username'];
					}
				} else {
					message($L['login_msg_not_member']);
				}
			}
			if($MOD['passport'] == 'uc') include DT_ROOT.'/api/'.$MOD['passport'].'.inc.php';
			$user = $do->login($username, $password, $cookietime);
			if($user) {
				if($MOD['passport'] && $MOD['passport'] != 'uc') {
					$api_url = '';
					$user['password'] = is_md5($password) ? $password : md5($password);
					if(strtoupper($MOD['passport_charset']) != DT_CHARSET) $user = convert($user, DT_CHARSET, $MOD['passport_charset']);
					extract($user);
					include DT_ROOT.'/api/'.$MOD['passport'].'.inc.php';
					if($api_url) $forward = $api_url;
				}
				if($DT['login_log'] == 2) $do->login_log($username, $password, $user['passsalt'], 0);
				if($api_msg) message($api_msg, $forward, -1);
				message($api_msg, $forward);
			} else {
				if($DT['login_log'] == 2) $do->login_log($username, $password, $user['passsalt'], 0, $do->errmsg);
				message($do->errmsg, '?action=login&forward='.urlencode($forward));
			}
		}
	break;
}
isset($username) or $username = $_username;
isset($password) or $password = '';
$register = isset($register) && $username ? 1 : 0;
$username or $username = get_cookie('username');
check_name($username) or $username = '';
$oa = 0;
foreach($OAUTH as $v) {
	if($v['enable']) {
		$oa = 1;
		break;
	}
}
if($DT_PC) {
	//
} else {
	$foot = '';
}
$head_title = $register ? $L['login_title_reg'] : $L['login_title'];
include template('login', $module);
?>