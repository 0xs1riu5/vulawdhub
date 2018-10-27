<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$step = isset($step) ? intval($step) : 0;
$could_email = $DT['mail_type'] == 'close' ? 0 : 1;
$could_mobile = $DT['sms'] ? 1 : 0;
$seconds = 0;
switch($action) {
	case 'passport':
		login();
		$_username == $_passport or dheader('edit.php');
		if($submit) {
			isset($npassport) or $npassport = '';
			require DT_ROOT.'/module/'.$module.'/member.class.php';
			$do = new member;
			$do->userid = $_userid;
			if($do->edit_passport($_passport, $npassport, $_username)) {
				dmsg($L['op_edit_success'], 'edit.php');
			} else {
				message($do->errmsg);
			}
		} else {			
			$head_title = $L['send_passport_title'];
		}
	break;
	case 'email':
		login();
		$could_email or message($L['send_mail_close']);
		$username = $_username;
		(isset($email) && is_email($email)) or $email = '';
		$session = new dsession();
		isset($_SESSION['email_send']) or $_SESSION['email_send'] = 0;
		isset($_SESSION['email_time']) or $_SESSION['email_time'] = 0;
		$second = $DT_TIME - $_SESSION['email_time'];
		if($step == 2) {
			$email = $_SESSION['email_save'];
			is_email($email) or dheader('?action='.$action);
			$code = isset($code) ? trim($code) : '';
			(preg_match("/^[0-9]{6}$/", $code) && $_SESSION['email_code'] == md5($email.'|'.$code.'|'.$username.'|SE')) or message($L['register_pass_emailcode']);
			$db->query("UPDATE {$DT_PRE}member SET email='$email',vemail=1 WHERE userid=$_userid");
			$db->query("INSERT INTO {$DT_PRE}validate (type,username,ip,addtime,status,title,editor,edittime) VALUES ('email','$username','$DT_IP','$DT_TIME','3','$email','send','$DT_TIME')");
			userclean($username);
			unset($_SESSION['email_save'], $_SESSION['email_code'], $_SESSION['email_time'], $_SESSION['email_send']);
		} else if($step == 1) {
			captcha($captcha);
			is_email($email) or message($L['member_email_null']);
			if($email == $_email) message($L['send_email_exist']);
			$r = $db->get_one("SELECT email FROM {$DT_PRE}member WHERE email='$email'");
			if($r) message($L['send_email_exist']);
			$emailcode = random(6, '0123456789');
			$_SESSION['email_save'] = $email;
			$_SESSION['email_code'] = md5($email.'|'.$emailcode.'|'.$username.'|SE');
			$_SESSION['email_time'] = $DT_TIME;
			$_SESSION['email_send'] = $_SESSION['email_send'] + 1;
			$title = $L['register_msg_emailcode'];
			$content = ob_template('emailcode', 'mail');
			send_mail($email, $title, stripslashes($content));
			#log_write($content, 'mail', 1);
		} else {
			$seconds = $second < 180 ? 180 - $second : 0;
		}
		$head_title = $L['send_email_title'];
	break;
	case 'mobile':
		login();
		$could_mobile or message($L['send_sms_close']);
		$username = $_username;
		(isset($mobile) && is_mobile($mobile)) or $mobile = '';
		$session = new dsession();
		isset($_SESSION['mobile_send']) or $_SESSION['mobile_send'] = 0;
		isset($_SESSION['mobile_time']) or $_SESSION['mobile_time'] = 0;
		$second = $DT_TIME - $_SESSION['mobile_time'];
		if($step == 2) {
			$mobile = $_SESSION['mobile_save'];
			is_mobile($mobile) or dheader('?action='.$action);
			$code = isset($code) ? trim($code) : '';
			(preg_match("/^[0-9]{6}$/", $code) && $_SESSION['mobile_code'] == md5($mobile.'|'.$code.'|'.$username.'|SM')) or message($L['register_pass_mobilecode']);
			$db->query("UPDATE {$DT_PRE}member SET mobile='$mobile',vmobile=1 WHERE userid=$_userid");
			$db->query("INSERT INTO {$DT_PRE}validate (type,username,ip,addtime,status,title,editor,edittime) VALUES ('mobile','$username','$DT_IP','$DT_TIME','3','$mobile','send','$DT_TIME')");
			userclean($username);
			unset($_SESSION['mobile_save'], $_SESSION['mobile_code'], $_SESSION['mobile_time'], $_SESSION['mobile_send']);
		} else if($step == 1) {
			captcha($captcha);
			if(!is_mobile($mobile)) message($L['member_mobile_null']);
			if($mobile == $_mobile) message($L['send_mobile_exist']);
			$r = $db->get_one("SELECT userid FROM {$DT_PRE}member WHERE mobile='$mobile'");
			if($r) message($L['send_mobile_exist']);
			if(max_sms($mobile)) message($L['sms_msg_max']);
			$mobilecode = random(6, '0123456789');
			$_SESSION['mobile_save'] = $mobile;
			$_SESSION['mobile_code'] = md5($mobile.'|'.$mobilecode.'|'.$username.'|SM');
			$_SESSION['mobile_time'] = $DT_TIME;
			$_SESSION['mobile_send'] = $_SESSION['mobile_send'] + 1;
			$content = lang('sms->sms_code', array($mobilecode, $MOD['auth_days']*10)).$DT['sms_sign'];
			send_sms($mobile, $content);
			#log_write($content, 'sms', 1);
		} else {
			$seconds = $second < 180 ? 180 - $second : 0;
		}
		$head_title = $L['send_mobile_title'];
	break;
	case 'contact':
		if($DT_PC) {
			$url = DT_PATH;
			if(is_file(DT_ROOT.'/about/contact.'.$DT['file_ext'])) {
				$url = DT_PATH.'about/contact.'.$DT['file_ext'];
			} else if(is_file(DT_ROOT.'/about/'.$DT['index'].'.'.$DT['file_ext'])) {			
				$url = DT_PATH.'about/'.$DT['index'].'.'.$DT['file_ext'];
			}
		} else {
			$url = DT_MOB.'api/about.php';
			if(is_file(DT_ROOT.'/mobile/about/contact.'.$DT['file_ext'])) {
				$url = DT_MOB.'about/contact.'.$DT['file_ext'];
			} else if(is_file(DT_ROOT.'/mobile/about/'.$DT['index'].'.'.$DT['file_ext'])) {			
				$url = DT_MOB.'about/'.$DT['index'].'.'.$DT['file_ext'];
			}
		}
		$head_title = $L['send_password_title'];
	break;
	case 'mail':
		$could_email or message($L['send_mail_close']);
		(isset($email) && is_email($email)) or $email = '';
		if($_userid) {
			is_email($_email) or message($L['send_email_empty'], 'edit.php');
			$email = $_email;
		}
		$session = new dsession();
		isset($_SESSION['email_send']) or $_SESSION['email_send'] = 0;
		isset($_SESSION['email_time']) or $_SESSION['email_time'] = 0;
		$second = $DT_TIME - $_SESSION['email_time'];
		if($step == 2) {
			$email = $_SESSION['email_save'];
			is_email($email) or dheader('?action='.$action);
			$code = isset($code) ? trim($code) : '';
			(preg_match("/^[0-9]{6}$/", $code) && $_SESSION['email_code'] == md5($email.'|'.$code.'|SEM')) or message($L['register_pass_emailcode']);
			require DT_ROOT.'/module/'.$module.'/member.class.php';
			$do = new member;
			if(!$do->is_password($password, $cpassword)) message($do->errmsg);
			$condition = $_userid ? "userid=$_userid" : "email='$email'";
			$r = $db->get_one("SELECT userid,username,groupid,vemail FROM {$DT_PRE}member WHERE $condition");
			if($r) {
				if($r['groupid'] == 2 || $r['groupid'] == 4) message($L['send_password_checking']);
				$userid = $r['userid'];
				$username = $r['username'];
				$salt = random(8);
				$pass = dpassword($password, $salt);
				if($_userid) {
					$db->query("UPDATE {$DT_PRE}member SET payword='$pass',paysalt='$salt' WHERE userid=$userid");
				} else {
					$db->query("UPDATE {$DT_PRE}member SET password='$pass',passsalt='$salt' WHERE userid=$userid");
				}
				if(!$r['vemail']) $db->query("UPDATE {$DT_PRE}member SET vemail=1 WHERE userid=$userid");
				userclean($username);
				$editor = $_userid ? 'payword' : 'password';
				$db->query("INSERT INTO {$DT_PRE}validate (type,username,ip,addtime,status,title,editor,edittime) VALUES ('email','$username','$DT_IP','$DT_TIME','3','$email','$editor','$DT_TIME')");
				unset($_SESSION['email_save'], $_SESSION['email_code'], $_SESSION['email_time'], $_SESSION['email_send']);
			} else {
				message($L['send_bad_email']);
			}
		} else if($step == 1) {
			captcha($captcha);
			is_email($email) or message($L['member_email_null']);
			if($_SESSION['email_send'] > 9) message($L['send_too_many'], '?action='.$action);
			if($second < 180) message($L['send_too_quick'], '?action='.$action);
			$condition = $_userid ? "userid=$_userid" : "email='$email'";
			$r = $db->get_one("SELECT groupid FROM {$DT_PRE}member WHERE $condition");
			if(!$r) message($L['send_bad_email']);
			if($r['groupid'] == 2 || $r['groupid'] == 4) message($L['send_password_checking']);
			$emailcode = random(6, '0123456789');;
			$_SESSION['email_save'] = $email;
			$_SESSION['email_code'] = md5($email.'|'.$emailcode.'|SEM');
			$_SESSION['email_time'] = $DT_TIME;
			$_SESSION['email_send'] = $_SESSION['email_send'] + 1;
			$title = $L['register_msg_emailcode'];
			$content = ob_template('emailcode', 'mail');
			send_mail($email, $title, stripslashes($content));
			#log_write($content, 'mail', 1);
		} else {
			$seconds = $second < 180 ? 180 - $second : 0;
		}
		$head_title = $L['send_password_title'];
	break;
	case 'sms':
		$could_mobile or message($L['send_sms_close']);
		(isset($mobile) && is_mobile($mobile)) or $mobile = '';
		if($_userid) {
			is_mobile($_mobile) or message($L['send_mobile_empty'], 'edit.php');
			$mobile = $_mobile;
		}
		$session = new dsession();
		isset($_SESSION['mobile_send']) or $_SESSION['mobile_send'] = 0;
		isset($_SESSION['mobile_time']) or $_SESSION['mobile_time'] = 0;
		$second = $DT_TIME - $_SESSION['mobile_time'];
		if($step == 2) {
			$mobile = $_SESSION['mobile_save'];
			is_mobile($mobile) or dheader('?action='.$action);
			$code = isset($code) ? trim($code) : '';
			(preg_match("/^[0-9]{6}$/", $code) && $_SESSION['mobile_code'] == md5($mobile.'|'.$code.'|SMS')) or message($L['register_pass_mobilecode']);
			require DT_ROOT.'/module/'.$module.'/member.class.php';
			$do = new member;
			if(!$do->is_password($password, $cpassword)) message($do->errmsg);
			$condition = $_userid ? "userid=$_userid" : "mobile='$mobile' AND vmobile=1";
			$r = $db->get_one("SELECT userid,username,groupid,vmobile FROM {$DT_PRE}member WHERE $condition");
			if($r) {
				if($r['groupid'] == 2 || $r['groupid'] == 4) message($L['send_password_checking']);
				$userid = $r['userid'];
				$username = $r['username'];
				$salt = random(8);
				$pass = dpassword($password, $salt);
				if($_userid) {
					$db->query("UPDATE {$DT_PRE}member SET payword='$pass',paysalt='$salt' WHERE userid=$userid");
					if(!$r['vmobile']) $db->query("UPDATE {$DT_PRE}member SET vmobile=1 WHERE userid=$userid");
				} else {
					$db->query("UPDATE {$DT_PRE}member SET password='$pass',passsalt='$salt' WHERE userid=$userid");
				}
				userclean($username);
				$editor = $_userid ? 'payword' : 'password';
				$db->query("INSERT INTO {$DT_PRE}validate (type,username,ip,addtime,status,title,editor,edittime) VALUES ('mobile','$username','$DT_IP','$DT_TIME','3','$mobile','$editor','$DT_TIME')");				
				unset($_SESSION['mobile_save'], $_SESSION['mobile_code'], $_SESSION['mobile_time'], $_SESSION['mobile_send']);
			} else {
				message($L['send_bad_mobile']);
			}
		} else if($step == 1) {
			captcha($captcha);
			if(!is_mobile($mobile)) message($L['member_mobile_null']);
			if(max_sms($mobile)) message($L['sms_msg_max'], '?action='.$action);
			if($_SESSION['mobile_send'] > 4) message($L['send_too_many'], '?action='.$action);
			if($second < 180) message($L['send_too_quick'], '?action='.$action);
			$condition = $_userid ? "userid=$_userid" : "mobile='$mobile' AND vmobile=1";
			$r = $db->get_one("SELECT groupid FROM {$DT_PRE}member WHERE $condition");
			if(!$r) message($L['send_bad_mobile']);
			if($r['groupid'] == 2 || $r['groupid'] == 4) message($L['send_password_checking']);
			$mobilecode = random(6, '0123456789');
			$_SESSION['mobile_save'] = $mobile;
			$_SESSION['mobile_code'] = md5($mobile.'|'.$mobilecode.'|SMS');
			$_SESSION['mobile_time'] = $DT_TIME;
			$_SESSION['mobile_send'] = $_SESSION['mobile_send'] + 1;
			$content = lang('sms->sms_code', array($mobilecode, $MOD['auth_days']*10)).$DT['sms_sign'];
			send_sms($mobile, $content);
			#log_write($content, 'sms', 1);
		} else {
			$seconds = $second < 180 ? 180 - $second : 0;
		}
		$head_title = $L['send_password_title'];
	break;
	default:
		$head_title = $L['send_password_title'];
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
}
include template('send', $module);
?>