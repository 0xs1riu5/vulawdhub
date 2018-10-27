<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MG['inquiry_limit'] > -1 or dalert(lang('message->without_permission'), 'goback');
include load('misc.lang');
$limit_used = $limit_free = 0;
if($MG['inquiry_limit']) {
	if(is_array($itemid) && count($itemid) > $MG['inquiry_limit']) dalert(lang($L['inquiry_limit'], array($MG['inquiry_limit'])), 'goback');
	$today = $today_endtime - 86400;
	$sql = $_userid ? "fromuser='$_username'" : "ip='$DT_IP'";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND typeid=1 AND status=3");
	$limit_used = $r['num'];
	$limit_used < $MG['inquiry_limit'] or dalert(lang($L['message_limit'], array($MG['inquiry_limit'], $limit_used)), 'goback');
	$limit_free = $MG['inquiry_limit'] > $limit_used ? $MG['inquiry_limit'] - $limit_used : 0;
}
require DT_ROOT.'/include/post.func.php';
$user = array();
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
$need_captcha = $MOD['captcha_inquiry'] == 2 ? $MG['captcha'] : $MOD['captcha_inquiry'];
$need_question = $MOD['question_inquiry'] == 2 ? $MG['question'] : $MOD['question_inquiry'];
if($submit) {
	preg_match("/^[0-9\,]{1,}$/", $itemids) or dalert($L['inquiry_itemid'], 'goback');
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
	$type = dhtmlspecialchars(implode(',', $type));
	$content = nl2br($content);
	if($type) $content = $L['content_type'].$type.'<br/>'.$content;
	if($company) $content .= '<br/>'.$L['content_company'].$company;
	if($truename) $content .= '<br/>'.$L['content_truename'].$truename;
	if($telephone) $content .= '<br/>'.$L['content_telephone'].$telephone;
	if(is_email($email)) $content .= '<br/>'.$L['content_email'].$email;
	if($DT['im_qq'] && is_qq($qq)) $content .= '<br/>'.$L['content_qq'].' '.im_qq($qq).' '.$qq;
	if($DT['im_wx'] && is_wx($wx)) $content .= '<br/>'.$L['content_wx'].' '.im_wx($wx, $_username).' '.$wx;
	if($DT['im_ali'] && $ali) $content .= '<br/>'.$L['content_ali'].' '.im_ali($ali).' '.$ali;
	if($DT['im_skype'] && $skype) $content .= '<br/>'.$L['content_skype'].' '.im_skype($skype).' '.$skype;
	if(is_date($date)) $content .= '<hr size="1"/>'.lang($L['content_date'], array($date));	
	$result = $db->query("SELECT * FROM {$table} WHERE itemid IN ($itemids) AND status=3 LIMIT 30");
	$i = $j = 0;
	while($r = $db->fetch_array($result)) {
		if($_username && $_username == $r['username']) continue;
		$linkurl = $MOD['linkurl'].$r['linkurl'];
		$message = $L['content_product'].'<a href="'.$linkurl.'"><strong>'.$r['title'].'</strong></a><br/>'.$content;
		++$i;
		if(send_message($r['username'], $title, $message, 1, $_username)) ++$j;
		//send sms
		if($DT['sms'] && $_sms && $r['username'] && isset($sendsms)) {
			$touser = userinfo($r['username']);
			if($touser['mobile']) {
				$message = lang('sms->sms_inquiry', array($r['tag'] ? $r['tag'] : $r['title'], $r['itemid'], $truename, $telephone));
				$message = strip_sms($message);
				$word = word_count($message);
				$sms_num = ceil($word/$DT['sms_len']);
				if($sms_num <= $_sms) {
					$sms_code = send_sms($touser['mobile'], $message, $word);
					if(strpos($sms_code, $DT['sms_ok']) !== false) {
						$tmp = explode('/', $sms_code);
						if(is_numeric($tmp[1])) $sms_num = $tmp[1];
						if($sms_num) sms_add($_username, -$sms_num);
						if($sms_num) sms_record($_username, -$sms_num, $_username, $L['sms_inquiry'], 'ID:'.$r['itemid']);
						$_sms = $_sms - $sms_num;
					}
				}
			}
		}
		//send sms
	}
	if($i == 1) $forward = $DT_PC ? $linkurl : str_replace($MOD['linkurl'], $MOD['mobile'], $linkurl);
	dalert(lang($L['inquiry_result'], array($i, $j)), $forward);
}
$itemid or dalert($L['inquiry_itemid'], 'goback');
$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
$list = array();
$result = $db->query("SELECT * FROM {$table} WHERE itemid IN ($itemids) AND status=3 LIMIT 30");
while($r = $db->fetch_array($result)) {
	if(!$r['username']) continue;
	if($r['username'] == $_username) dalert($L['inquiry_self'], 'goback');
	$list[] = $r;
}
$total = count($list);
if($total < 1) dalert($L['inquiry_no_info'], 'goback');
$itype = explode('|', trim($MOD['inquiry_type']));
$iask = explode('|', trim($MOD['inquiry_ask']));
$date = timetodate($DT_TIME + 5*86400, 3);
$title = $total == 1 ? lang($L['inquiry_message_title'], array($list[0]['title'])) : lang($L['inquiry_message_title_multi'], array($DT['sitename']));
$head_title = ($total == 1 ? $L['inquiry_head_title'].$DT['seo_delimiter'].$list[0]['title'] : $L['inquiry_head_title_multi']).$DT['seo_delimiter'].$MOD['name'];
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	$back_link = $forward = $MOD['mobile'].$list[0]['linkurl'];
	$head_name = $L['inquiry_head_title'];
	$foot = '';
}
include template($MOD['template_inquiry'] ? $MOD['template_inquiry'] : 'inquiry', $module);
?>