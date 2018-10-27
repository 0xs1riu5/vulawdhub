<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($_userid) dheader($DT_PC ? $MOD['linkurl'] : $MOD['mobile']);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if(!$MOD['enable_register']) message($L['register_msg_close'], $DT_PC ? DT_PATH : DT_MOB);
if($MOD['defend_proxy']) {
	if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_CACHE_INFO'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
		message(lang('include->defend_proxy'));
	}
}
if($MOD['banagent']) {
	$banagent = explode('|', $MOD['banagent']);
	foreach($banagent as $v) {
		if(strpos($_SERVER['HTTP_USER_AGENT'], $v) !== false) message($L['register_msg_agent']);
	}
}
if($MOD['iptimeout'] && $action != 'success') {
	$timeout = $DT_TIME - $MOD['iptimeout']*3600;
	$r = $db->get_one("SELECT userid FROM {$DT_PRE}member WHERE regip='$DT_IP' AND regtime>'$timeout'");
	if($r) message(lang($L['register_msg_ip'], array($MOD['iptimeout'])), $DT_PC ? DT_PATH : DT_MOB);
}
require DT_ROOT.'/include/post.func.php';
require DT_ROOT.'/module/'.$module.'/member.class.php';
$do = new member;
$session = new dsession();
if($DT['mail_type'] == 'close' && ($MOD['checkuser'] == 2 || $MOD['checkuser'] == 4)) $MOD['checkuser'] = 0;
if(!$DT['sms'] && ($MOD['checkuser'] == 3 || $MOD['checkuser'] == 4)) $MOD['checkuser'] = 0;
$could_mail = ($MOD['checkuser'] == 2 || $MOD['checkuser'] == 4) ? 1 : 0;
$could_sms = ($MOD['checkuser'] == 3 || $MOD['checkuser'] == 4) ? 1 : 0;
$could_verify = $MOD['checkuser'] < 2 ? 1 : 0;
isset($sid) or $sid = '';
$_sid = md5(md5(session_id().DT_KEY));
$timeout = 180;
$stepid = 1;
switch($action) {
	case 'verify':
		($could_verify && $sid == $_sid) or message($L['register_msg_error']);
		if(!$DT_PC && $MOD['question_register']) $MOD['question_register'] = 0;
		if($submit) {
			captcha($captcha, $MOD['captcha_register']);
			question($answer, $MOD['question_register']);
			$_SESSION['verify'] = '1';
			dheader('?reload='.$DT_TIME);
		}
	break;
	case 'mail':
		($could_mail && $sid == $_sid) or message($L['register_msg_error']);
		if($submit) {
			(is_email($email) && preg_match("/^[0-9]{6}$/", $code) && isset($_SESSION['email_code']) && $_SESSION['email_code'] == md5($email.'|'.$code)) or message('验证失败');
			$user = $db->get_one("SELECT userid FROM {$DT_PRE}member WHERE email='$email'");
			if($user) message($L['member_email_reg']);
			$_SESSION['email_code'] = '';
			$_SESSION['verify'] = $email;
			dheader('?reload='.$DT_TIME);
		}
	break;
	case 'sendmail':
		($could_mail && $sid == $_sid) or exit('close');
		is_email($email) or exit('format');
		$msg = captcha($captcha, 1, true);
		if($msg) exit('captcha');
		$user = $db->get_one("SELECT userid FROM {$DT_PRE}member WHERE email='$email'");
		if($user) exit('exist');
		isset($_SESSION['email_send']) or $_SESSION['email_send'] = 0;
		isset($_SESSION['email_time']) or $_SESSION['email_time'] = 0;
		if($_SESSION['email_send'] > 4) exit('max');
		if($_SESSION['email_time'] && (($DT_TIME - $_SESSION['email_time']) < $timeout)) exit('fast');
		$emailcode = random(6, '0123456789');
		$_SESSION['email_code'] = md5($email.'|'.$emailcode);
		$_SESSION['email_time'] = $DT_TIME;
		$_SESSION['email_send'] = $_SESSION['email_send'] + 1;
		$title = $L['register_msg_emailcode'];
		$content = ob_template('emailcode', 'mail');
		send_mail($email, $title, stripslashes($content));
		#log_write($content, 'mail', 1);
		exit('ok');
	break;
	case 'sms':
		($could_sms && $sid == $_sid) or message($L['register_msg_error']);
		if($submit) {
			(is_mobile($mobile) && preg_match("/^[0-9]{6}$/", $code) && isset($_SESSION['mobile_code']) && $_SESSION['mobile_code'] == md5($mobile.'|'.$code)) or message('验证失败');
			$user = $db->get_one("SELECT userid FROM {$DT_PRE}member WHERE mobile='$mobile'");
			if($user) message($L['member_mobile_reg']);
			$_SESSION['mobile_code'] = '';
			$_SESSION['verify'] = $mobile;
			dheader('?reload='.$DT_TIME);
		}
	break;
	case 'sendsms':
		($could_sms && $sid == $_sid) or exit('close');
		is_mobile($mobile) or exit('format');
		$msg = captcha($captcha, 1, true);
		if($msg) exit('captcha');
		$user = $db->get_one("SELECT userid FROM {$DT_PRE}member WHERE mobile='$mobile'");
		if($user) exit('exist');
		isset($_SESSION['mobile_send']) or $_SESSION['mobile_send'] = 0;
		isset($_SESSION['mobile_time']) or $_SESSION['mobile_time'] = 0;
		if($_SESSION['mobile_send'] > 4) exit('max');
		if($_SESSION['mobile_time'] && (($DT_TIME - $_SESSION['mobile_time']) < $timeout)) exit('fast');
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
	case 'success':
		$_auth = isset($auth) ? decrypt($auth, DT_KEY.'LOGIN') : '';
		substr($_auth, 0, 5) == 'LOGIN' or dheader($DT_PC ? DT_PATH : DT_MOB);
		$stepid = 3;
		$url = $DT['file_login'].'?auth='.$auth.'&forward='.urlencode($DT_PC ? $MOD['linkurl'] : DT_MOB.'my.php');
	break;
	case 'read':
		exit(include template('agreement', $module));
	break;
	default:
		if($MOD['checkuser'] == 4) {
			(is_email($_SESSION['verify']) || is_mobile($_SESSION['verify'])) or dheader('?action=sms&sid='.$_sid);
		} elseif($MOD['checkuser'] == 3) {
			is_mobile($_SESSION['verify']) or dheader('?action=sms&sid='.$_sid);
		} elseif($MOD['checkuser'] == 2) {
			is_email($_SESSION['verify']) or dheader('?action=mail&sid='.$_sid);
		} else {
			$_SESSION['verify'] or dheader('?action=verify&sid='.$_sid);
		}
		$FD = $MFD = cache_read('fields-member.php');
		$CFD = cache_read('fields-company.php');
		isset($post_fields) or $post_fields = array();
		if($MFD || $CFD) require DT_ROOT.'/include/fields.func.php';
		$GROUP = cache_read('group.php');
		if($submit) {
			if($sid != $_sid) message($L['check_sign']);
			$post['passport'] = isset($post['passport']) && $post['passport'] ? $post['passport'] : $post['username'];
			if($MOD['passport'] == 'uc') {
				$passport = convert($post['passport'], DT_CHARSET, $MOD['uc_charset']);
				require DT_ROOT.'/api/uc.inc.php';
				list($uid, $rt_username, $rt_password, $rt_email) = uc_user_login($passport, $post['password']);
				if($uid == -2) message($L['register_msg_passport']);
			}
			$RG = array();
			foreach($GROUP as $k=>$v) {
				if($k > 4 && $v['vip'] == 0) $RG[] = $k;
			}
			in_array($post['regid'], $RG) or message($L['register_pass_groupid']);
			if(!$GROUP[$post['regid']]['type']) $post['company'] = $post['truename'];
			$post['groupid'] = $MOD['checkuser'] == 1 ? 4 : $post['regid'];
			if(is_email($_SESSION['verify'])) $post['email'] = $_SESSION['verify'];
			if(is_mobile($_SESSION['verify'])) $post['mobile'] = $_SESSION['verify'];
			$post['content'] = $post['introduce'] = $post['thumb'] = $post['banner'] = $post['catid'] = $post['catids'] = '';
			$post['edittime'] = 0;
			$inviter = get_cookie('inviter');
			$post['inviter'] = $inviter ? decrypt($inviter, DT_KEY.'INVITER') : '';
			check_name($post['inviter']) or $post['inviter'] = '';
			if($do->add($post)) {
				$userid = $do->userid;
				$username = $post['username'];
				$email = $post['email'];
				$mobile = $post['mobile'];
				if($MFD) fields_update($post_fields, $do->table_member, $userid, 'userid', $MFD);
				if($CFD) fields_update($post_fields, $do->table_company, $userid, 'userid', $CFD);
				if($MOD['passport'] == 'uc') {
					$uid = uc_user_register($passport, $post['password'], $post['email']);
					if($uid > 0 && $MOD['uc_bbs']) uc_user_regbbs($uid, $passport, $post['password'], $post['email']);
				}
				//send sms
				if($MOD['welcome_sms'] && $DT['sms'] && is_mobile($post['mobile'])) {
					$message = lang('sms->wel_reg', array($post['truename'], $DT['sitename'], $post['username'], $post['password']));
					$message = strip_sms($message);
					send_sms($post['mobile'], $message);
				}
				//send sms
				if($MOD['checkuser'] != 1) {
					if($MOD['welcome_message'] || $MOD['welcome_email']) {
						$title = $L['register_msg_welcome'];
						$content = ob_template('welcome', 'mail');
						if($MOD['welcome_message']) send_message($username, $title, $content);
						if($MOD['welcome_email'] && $DT['mail_type'] != 'close') send_mail($email, $title, $content);
					}
				}
				if(is_email($_SESSION['verify'])) {
					$db->query("UPDATE {$DT_PRE}member SET vemail=1 WHERE userid=$userid");
					$db->query("INSERT INTO {$DT_PRE}validate (type,username,ip,addtime,status,title,editor,edittime) VALUES ('email','$username','$DT_IP','$DT_TIME','3','$email','register','$DT_TIME')");
				}
				if(is_mobile($_SESSION['verify'])) {
					$db->query("UPDATE {$DT_PRE}member SET vmobile=1 WHERE userid=$userid");
					$db->query("INSERT INTO {$DT_PRE}validate (type,username,ip,addtime,status,title,editor,edittime) VALUES ('mobile','$username','$DT_IP','$DT_TIME','3','$mobile','register','$DT_TIME')");
				}
				$_SESSION['verify'] = '';
				dheader('?action=success&auth='.encrypt('LOGIN|'.$username.'|'.$post['password'].'|'.$DT_TIME, DT_KEY.'LOGIN'));
			} else {
				message($do->errmsg);
			}
		}
		isset($auth) or $auth = '';
		$username = $password = $email = $passport = '';
		if($auth) {
			$auth = decrypt($auth, DT_KEY.'UC');
			$auth = explode('|', $auth);
			$passport = $auth[0];
			if(check_name($passport)) $username = $passport;
			$password = $auth[1];
			$email = is_email($auth[2]) ? $auth[2] : '';
			if($email) $_SESSION['regemail'] = md5(md5($email.DT_KEY.$DT_IP));
		}
		$areaid = $cityid;
		$stepid = 2;
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
}
$head_title = $L['register_title'];
include template('register', $module);
?>