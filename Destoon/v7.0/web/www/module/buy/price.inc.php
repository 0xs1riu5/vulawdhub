<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
$itemid or dheader($MOD['linkurl']);
$MG['price_limit'] > -1 or message(lang('message->without_permission'), 'goback');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
include load('misc.lang');
$item = $db->get_one("SELECT title,tag,linkurl,totime,username,company,vip,status FROM {$table} WHERE itemid=$itemid");
$item or message($L['not_exists']);
if($item['totime'] && $DT_TIME > $item['totime']) message($L['has_expired']);
$item['status'] == 3 or message($L['not_exists']);
$item['username'] or message($L['com_not_member']);
$_username != $item['username'] or message($L['price_self']);

$limit_used = $limit_free = 0;
if($MG['price_limit']) {
	$today = $today_endtime - 86400;
	$sql = $_userid ? "fromuser='$_username'" : "ip='$DT_IP'";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND typeid=2 AND status=3");
	$limit_used = $r['num'];
	$limit_used < $MG['price_limit'] or message(lang($L['message_limit'], array($MG['price_limit'], $limit_used)), 'goback');

	$limit_free = $MG['price_limit'] > $limit_used ? $MG['price_limit'] - $limit_used : 0;
}

$linkurl = $MOD['linkurl'].$item['linkurl'];
$need_captcha = $MOD['captcha_price'] == 2 ? $MG['captcha'] : $MOD['captcha_price'];
$need_question = $MOD['question_price'] == 2 ? $MG['question'] : $MOD['question_price'];
require DT_ROOT.'/include/post.func.php';
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
if($submit) {
	captcha($captcha, $need_captcha);
	question($answer, $need_question);
	$title = dhtmlspecialchars(trim($title));
	if(!$title) message($L['msg_type_title']);
	$content = dhtmlspecialchars(trim($content));
	if(!$content) message($L['msg_type_content']);
	if(!$_userid) {
		$truename = dhtmlspecialchars(trim($truename));
		if(!$truename) message($L['msg_type_truename']);
		$telephone = dhtmlspecialchars(trim($telephone));
		if(!$telephone) message($L['msg_type_telephone']);
		$email = dhtmlspecialchars(trim($email));
		$company = dhtmlspecialchars(trim($company));
		if($DT['im_qq']) $qq = dhtmlspecialchars(trim($qq));
		if($DT['im_wx']) $wx = dhtmlspecialchars(trim($wx));
		if($DT['im_ali'])$ali = dhtmlspecialchars(trim($ali));
		if($DT['im_skype']) $skype = dhtmlspecialchars(trim($skype));
	}
	$content = nl2br($content);
	if($company) $content .= '<br/>'.$L['content_company'].$company;
	if($truename) $content .= '<br/>'.$L['content_truename'].$truename;
	if($telephone) $content .= '<br/>'.$L['content_telephone'].$telephone;
	if(is_email($email)) $content .= '<br/>'.$L['content_email'].$email;
	if($DT['im_qq'] && is_qq($qq)) $content .= '<br/>'.$L['content_qq'].' '.im_qq($qq).' '.$qq;
	if($DT['im_wx'] && is_wx($wx)) $content .= '<br/>'.$L['content_wx'].' '.im_wx($wx, $_username).' '.$wx;
	if($DT['im_ali'] && $ali) $content .= '<br/>'.$L['content_ali'].' '.im_ali($ali).' '.$ali;
	if($DT['im_skype'] && $skype) $content .= '<br/>'.$L['content_skype'].' '.im_skype($skype).' '.$skype;
	if(is_date($date)) $content .= '<hr size="1"/>'.lang($L['content_date'], array($date));	
	$message = $L['content_product'].'<a href="'.$linkurl.'"><strong>'.$item['title'].'</strong></a><br/>'.$content;
	//send sms
	if($DT['sms'] && $_sms && $item['username'] && isset($sendsms)) {
		$touser = userinfo($item['username']);
		if($touser['mobile']) {
			$message = lang('sms->sms_price', array($item['tag'] ? $item['tag'] : $item['title'], $itemid, $truename, $telephone));
			$message = strip_sms($message);
			$word = word_count($message);
			$sms_num = ceil($word/$DT['sms_len']);
			if($sms_num <= $_sms) {
				$sms_code = send_sms($touser['mobile'], $message, $word);
				if(strpos($sms_code, $DT['sms_ok']) !== false) {
					$tmp = explode('/', $sms_code);
					if(is_numeric($tmp[1])) $sms_num = $tmp[1];
					if($sms_num) sms_add($_username, -$sms_num);
					if($sms_num) sms_record($_username, -$sms_num, $_username, $L['sms_price'], 'ID:'.$itemid);
				}
			}
		}
	}
	//send sms
	$forward = $DT_PC ? $linkurl : str_replace($MOD['linkurl'], $MOD['mobile'], $linkurl);
	if(send_message($item['username'], $title, $message, 2, $_username)) {
		message($L['msg_price_success'], $forward);
	} else {
		message($_userid ? $L['msg_price_member_failed'] : $L['msg_price_guest_failed'], $forward);
	}
}	
$iask = explode('|', trim($MOD['price_ask']));
$date = timetodate($DT_TIME + 5*86400, 3);
$title = lang($L['price_message_title'], array($item['title']));
$head_title = $L['price_head_title'].$DT['seo_delimiter'].$item['title'].$DT['seo_delimiter'].$MOD['name'];
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	$back_link = $forward = $MOD['mobile'].$item['linkurl'];
	$head_name = $L['price_head_title'];
	$foot = '';
}
include template($MOD['template_price'] ? $MOD['template_price'] : 'price', $module);
?>