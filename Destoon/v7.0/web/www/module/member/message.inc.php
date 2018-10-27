<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MG['inbox_limit'] > -1 or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/include/post.func.php';
require DT_ROOT.'/module/'.$module.'/message.class.php';
$do = new message;
$typeid = isset($typeid) ? intval($typeid) : -1;
isset($style) or $style = '';
$fields = isset($fields) ? trim($fields) : 'title';
$NAME = $L['message_type'];
$COLORS = array('FF0000','0000FF','000000','008080','008000','800000','808000','808080');
in_array($style, $COLORS) or $style = '';
$action or $action = 'inbox';
$menuid = $action;
$condition = '';
if($typeid > -1) $condition .= " AND typeid=$typeid";
if($keyword) $condition .= $fields == 'content' ? " AND content LIKE '%$keyword%'" : " AND title LIKE '%$keyword%'";
if($style) $condition .= " AND style='$style'";
$head_title = $L['message_title'];
switch($action) {
	case 'send':
		$MG['message_limit'] > -1 or dalert(lang('message->without_permission_and_upgrade'), 'goback');
		$limit_used = $limit_free = 0;
		if($MG['message_limit']) {
			$today = $today_endtime - 86400;
			$sql = $_userid ? "fromuser='$_username'" : "ip='$DT_IP'";
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND status=3");
			$limit_used = $r['num'];
			$limit_used < $MG['message_limit'] or dalert(lang($L['message_limit'], array($MG['message_limit'], $limit_used)), 'goback');
			$limit_free = $MG['message_limit'] > $limit_used ? $MG['message_limit'] - $limit_used : 0;
		}

		$need_captcha = $MOD['captcha_sendmessage'] == 2 ? $MG['captcha'] : $MOD['captcha_sendmessage'];
		if($submit) {
			captcha($captcha, $need_captcha);
			$post['typeid'] = $typeid;
			if($do->send($post)) {
				if($forward && strpos($forward, 'message.php') !== false) $forward = '?action=send';
				dmsg(isset($post['save']) ? $L['message_msg_save_draft'] : $L['message_msg_send'], $forward);
			} else {
				message($do->errmsg);
			}
		} else {
			$touser = isset($touser) ? trim($touser) : '';
			$title = isset($title) ? stripslashes($title) : '';
			$content = isset($content) ? stripslashes($content) : '';	
		}
	break;
	case 'edit':
		$itemid or message($L['message_msg_choose']);
		$do->itemid = $itemid;
		if($submit) {
			if($do->edit($message)) {
				dmsg(isset($message['send']) ? $L['message_msg_send'] : $L['message_msg_edit_draft'], '?action=draft');
			} else {
				message($do->errmsg);
			}
		} else {
			$message = $do->get_one();
			if(!$message || $message['status'] != 1 || $message['fromuser'] != $_username) message($L['message_msg_deny']);
			$touser = $message['touser'];
			$title = $message['title'];
			$content = $message['content'];
			$menuid = 'draft';
		}
	break;
	case 'clear':
		$status or message();
		$message = $do->clear($status);
		dmsg($L['message_msg_clear'], $forward);
	break;
	case 'delete':
		$itemid or message($L['message_msg_choose']);
		$recycle = isset($recycle) ? 0 : 1;
		$do->itemid = $itemid;
		$do->delete($recycle);
		dmsg($L['op_del_success'], $forward);
	break;
	case 'mark':
		$itemid or message($L['message_msg_choose']);
		$do->itemid = $itemid;
		$do->mark();
		dmsg($L['message_msg_mark'], $forward);
	break;
	case 'markall':
		$message = $do->markall();
		dmsg($L['message_msg_mark'], $forward);
	break;
	case 'restore':
		$itemid or message($L['message_msg_choose']);
		$do->itemid = $itemid;
		$do->restore();
		dmsg($L['message_msg_restore'], $forward);
	break;
	case 'color':
		$itemid or message();
		$do->itemid = $itemid;
		$do->color($style);
		dmsg($L['op_set_success'], $forward);
	break;
	case 'show':
		$itemid or message();
		$do->itemid = $itemid;
		$message = $do->get_one();
		if(!$message) message($L['message_msg_deny']);
		$fback = isset($feedback) ? 1 : 0;
		extract($message);
		if($status == 4 || $status == 3) {
			if($touser != $_username) message($L['message_msg_deny']);
			if(!$isread) {
				$do->read();
				--$_message;
				if($fback && $feedback) $do->feedback($message);
			}
		} else if($status == 2 || $status == 1) {
			if($fromuser != $_username) message($L['message_msg_deny']);
		}
		$addtime = timetodate($addtime, 5);
		if($status == 1) {
			$menuid = 'draft';
		} else if($status == 2) {
			$menuid = 'outbox';
		} else if($status == 4) {
			$menuid = 'recycle';
		} else {
			$menuid = 'inbox';
		}
	break;
	case 'export':
		if($submit) {
			$do->export($message) or message($do->errmsg);
		} else {
			$fromdate = timetodate(strtotime('-1 month'), 3);
			$todate = timetodate($DT_TIME, 3);
		}
	break;
	case 'empty':
		if($submit) {
			$message['username'] = $_username;
			if($do->_clear($message)) {
				dmsg($L['message_msg_empty'], $forward);
			} else {
				message($do->errmsg);
			}
		} else {
			$fromdate = '';
			$todate = timetodate(strtotime('-1 month'), 3);
		}
	break;
	case 'refuse':
		if(!$username) message($L['message_black_username']);
		if(!$do->is_member($username)) message($L['message_black_not_member']);
		$black = $db->get_one("SELECT black FROM {$DT_PRE}member_misc WHERE userid=$_userid");
		$black = $black['black'];
		if($black) {
			$tmp = explode(' ', trim($black));
			if(in_array($username, $tmp)) {
				message($L['message_black_exist']);
			} else {
				$black = $black.' '.$username;
			}
		} else {
			$black = $username;
		}
		$db->query("UPDATE {$DT_PRE}member_misc SET black='$black' WHERE userid=$_userid");
		userclean($_username);
		dmsg($L['message_black_update'], '?action=setting');
	break;
	case 'setting':
		if($submit) {
			if($black) {
				$blacks = array();
				$tmp = explode(' ', trim($black));
				foreach($tmp as $v) {
					if(($do->is_member($v) || $v == 'Guest') && !in_array($v, $blacks)) $blacks[] = $v;
				}
				$black = $blacks ? implode(' ', $blacks) : '';
			} else {
				$black = '';
			}
			$send = $send ? 1 : 0;
			$db->query("UPDATE {$DT_PRE}member_misc SET black='$black',send='$send' WHERE userid=$_userid");
			userclean($_username);
			dmsg($L['op_update_success'], '?action=setting');
		} else {
			$head_title = $L['message_title_black'].$DT['seo_delimiter'].$head_title;
			$user = $db->get_one("SELECT black,send FROM {$DT_PRE}member_misc WHERE userid=$_userid");
			$could_send = false;
			if($DT['message_email'] && $DT['mail_type'] != 'close') {
				if(check_group($_groupid, $DT['message_group'])) $could_send = true;
			}
		}
	break;
	case 'outbox':
		$status = 2;
		$name = $L['message_title_outbox'];
		$condition = "fromuser='$_username' AND status=$status ".$condition;
		$lists = $do->get_list($condition);
	break;
	case 'draft':
		$status = 1;
		$name = $L['message_title_draft'];
		$condition = "fromuser='$_username' AND status=$status ".$condition;
		$lists = $do->get_list($condition);
	break;
	case 'recycle':
		$status = 4;
		$name = $L['message_title_recycle'];
		$condition = "touser='$_username' AND status=$status ".$condition;
		$lists = $do->get_list($condition);
	break;
	case 'last':
		if($_message) {
			$item = $db->get_one("SELECT itemid,feedback FROM {$DT_PRE}message WHERE touser='$_username' AND status=3 AND isread=0 ORDER BY itemid DESC");
			if($item) dheader('?action=show&itemid='.$item['itemid'].($item['feedback'] ? '&feedback=1' : ''));
		} 
		dheader('?action=index');
	break;
	default:
		if($MG['inbox_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE touser='$_username' AND status=3");
			$limit_used = $r['num'];
			$limit_free = $MG['inbox_limit'] > $limit_used ? $MG['inbox_limit'] - $limit_used : 0;
			if($limit_used >= $MG['inbox_limit']) dalert($L['message_msg_inbox_limit'], '?action=empty');
		}
		$status = 3;
		$name = $L['message_title_inbox'];
		if($_message) $do->fix_message();
		$condition = "touser='$_username' AND status=$status ".$condition;
		$lists = $do->get_list($condition);
		$systems = $do->get_sys();
		$color_select = '';
		foreach($COLORS as $v) {
			$color_select .= '<option value="'.$v.'" style="background:#'.$v.';">&nbsp;</option>';
		}
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'send' || $action == 'edit') {
		$back_link = '?action=inbox';
	} else if($action == 'show') {
		$back_link = '?action='.$menuid.'&page='.$page;
	} else {
		$pages = isset($items) ? mobile_pages($items, $page, $pagesize) : '';
		$back_link = ($kw || $page > 1) ? '?action='.$action : 'index.php';
	}
}
include template('message', $module);
?>