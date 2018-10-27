<?php
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
switch($action) {
	case 'search':
		if(check_name($homepage) && in_array($file, array('sell', 'buy', 'news', 'credit', 'job', 'price', 'photo', 'info', 'brand', 'video', 'mall')) && $kw) {
			$user = userinfo($homepage);
			$domain = $user['domain'];
			if($domain) $DT['rewrite'] = intval($CFG['com_rewrite']);
			dheader(userurl($homepage, 'file='.$file.'&kw='.urlencode($kw), $domain));
		}
	break;
	case 'message':
		if(!$username || !$template || !$skin || !$sign) exit;
		if($job == 'inquiry' || $job == 'order' || $job == 'price') {
			$title = rawurldecode($title);
			if(!$title || !$itemid) exit;
			check_sign($itemid.$template.$skin.$title.$username, $sign) or exit;
		} else if($job == 'guestbook') {
			check_sign($template.$skin.$username, $sign) or exit;
		} else {
			exit;
		}
		$HSPATH = $MODULE[4]['linkurl'].'/skin/'.$skin.'/';
		$company = $truename = $telephone = $email = $qq = $wx = $ali = $skype = '';
		if($_userid) {
			$user = userinfo($_username);
			$company = $user['company'];
			$truename = $user['truename'];
			$telephone = $user['telephone'] ? $user['telephone'] : $user['mobile'];
			$email = $user['mail'] ? $user['mail'] : $user['email'];
			$qq = $user['qq'];
			$wx = $user['wx'];
			$ali = $user['ali'];
			$skype = $user['skype'];
		}
		include template('message', $template);
	break;
	case 'send':
		check_name($username) or exit;
		in_array($job, array('inquiry', 'order', 'guestbook', 'price')) or exit;
		require DT_ROOT.'/include/post.func.php';
		include load('misc.lang');
		$today = $today_endtime - 86400;
		$sql = $_userid ? "fromuser='$_username'" : "ip='$DT_IP'";
		if($job == 'inquiry') {
			if($MG['inquiry_limit']) {
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND typeid=1 AND status=3");
				$r['num'] < $MG['inquiry_limit'] or dalert(lang($L['message_limit'], array($MG['inquiry_limit'], $r['num'])));
			}
		} else if($job == 'price') {
			if($MG['price_limit']) {
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND typeid=2 AND status=3");
				$r['num'] < $MG['price_limit'] or dalert(lang($L['message_limit'], array($MG['price_limit'], $r['num'])));
			}
		} else {
			if($MG['message_limit']) {
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND status=3");
				$r['num'] < $MG['message_limit'] or dalert(lang($L['message_limit'], array($MG['message_limit'], $r['num'])));
			}
		}
		$msg = captcha($captcha, 1, true);
		if($msg) dalert($msg);
		$title = dhtmlspecialchars(trim($title));
		if(!$title) dalert($L['msg_type_title']);
		$content = dhtmlspecialchars(trim($content));
		if(!$content) dalert($L['msg_type_content']);
		$truename = dhtmlspecialchars(trim($truename));
		if(!$truename)  dalert($L['msg_type_truename']);
		$telephone = dhtmlspecialchars(trim($telephone));
		if(!$telephone) message($L['msg_type_telephone']);
		$company = dhtmlspecialchars(trim($company));
		$email = dhtmlspecialchars(trim($email));
		$qq = dhtmlspecialchars(trim($qq));
		$wx = dhtmlspecialchars(trim($wx));
		$ali = dhtmlspecialchars(trim($ali));
		$skype = dhtmlspecialchars(trim($skype));
		$content = nl2br($content);
		if($company) $content .= '<br/>'.$L['content_company'].$company;
		if($truename) $content .= '<br/>'.$L['content_truename'].$truename;
		if($telephone) $content .= '<br/>'.$L['content_telephone'].$telephone;
		if(is_email($email)) $content .= '<br/>'.$L['content_email'].$email;
		if(is_qq($qq)) $content .= '<br/>'.$L['content_qq'].' '.im_qq($qq).' '.$qq;
		if(is_wx($wx)) $content .= '<br/>'.$L['content_wx'].' '.im_wx($wx, $_username).' '.$wx;
		if($ali) $content .= '<br/>'.$L['content_ali'].' '.im_ali($ali).' '.$ali;
		if($skype) $content .= '<br/>'.$L['content_skype'].' '.im_skype($skype).' '.$skype;
		if($job != 'guestbook') $content .= '<br/>'.$L['content_from'];
		if($job == 'guestbook') {
			$type = 3;
		} else if($job == 'price') {
			$type = 2;
		} else {
			$type = 1;
		}
		if(send_message($username, $title, $content, $type, $_username)) {
			dalert($L['msg_home_success'], '', 'parent.window.location=parent.window.location;');
		} else {
			dalert($_userid ? $L['msg_home_member_failed'] : $L['msg_home_guest_failed']);
		}
	break;
	case 'next':
		$itemid or dheader($MOD['linkurl']);
		check_name($username) or dheader($MOD['linkurl']);
		$user = userinfo($username);
		$domain = $user['domain'];
		if($domain) $DT['rewrite'] = intval($CFG['com_rewrite']);
		$r = $db->get_one("SELECT itemid FROM {$DT_PRE}sell_5 WHERE username='$username' AND itemid>$itemid AND status=3 ORDER BY itemid ASC");
		if($r) dheader(userurl($username, 'file=sell&itemid='.$r['itemid'], $domain));
		dheader(userurl($username, 'file=sell', $domain));
	break;
	case 'prev':
		$itemid or dheader($MOD['linkurl']);
		check_name($username) or dheader($MOD['linkurl']);
		$user = userinfo($username);
		$domain = $user['domain'];
		if($domain) $DT['rewrite'] = intval($CFG['com_rewrite']);
		$r = $db->get_one("SELECT itemid FROM {$DT_PRE}sell_5 WHERE username='$username' AND itemid<$itemid AND status=3 ORDER BY itemid DESC");
		if($r) dheader(userurl($username, 'file=sell&itemid='.$r['itemid'], $domain));
		dheader(userurl($username, 'file=sell', $domain));
	break;
	default:
		dheader($MOD['linkurl']);
	break;
}
?>