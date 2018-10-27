<?php
defined('IN_DESTOON') or exit('Access Denied');
$condition = 'isread=0 AND issend=0 AND status=3';
if($DT['message_time']) {
	$time = $DT_TIME - $DT['message_time']*60;
	$condition .= " AND addtime<$time";
}
if($DT['message_type']) $condition .= " AND typeid IN ($DT[message_type])";
$msg = $db->get_one("SELECT * FROM {$DT_PRE}message WHERE $condition ORDER BY itemid ASC");
if($msg) {
	$db->query("UPDATE {$DT_PRE}message SET issend=1 WHERE itemid=$msg[itemid]");
	$user = $db->get_one("SELECT groupid,email,send FROM {$DT_PRE}member WHERE username='$msg[touser]'");
	if($user) {
		if($user['send']) {
			if(check_group($user['groupid'], $DT['message_group'])) {
				extract($msg);
				$NAME = $L['message_type'];
				$member_url = $MODULE[2]['linkurl'];
				$content = ob_template('message', 'mail');
				send_mail($user['email'], '['.$NAME[$typeid].']'.$title, $content);
				if($DT['message_weixin']) send_weixin($msg['touser'], $title.$L['message_weixin']);
			}
		}
	}
}
?>