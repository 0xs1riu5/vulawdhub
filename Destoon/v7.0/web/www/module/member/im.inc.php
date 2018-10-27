<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
$DT['im_web'] or dheader('index.php');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$chatid = (isset($chatid) && is_md5($chatid)) ? $chatid : '';
$table = $DT_PRE.'chat';
$chat_poll = intval($MOD['chat_poll']);
$head_title = $L['chat_title'];
switch($action) {
	case 'black':
		if(!check_name($username)) message($L['chat_msg_black']);
		$black = $db->get_one("SELECT black FROM {$DT_PRE}member_misc WHERE userid=$_userid");
		$black = $black['black'];
		if($black) {
			$tmp = explode(' ', trim($black));
			if(in_array($username, $tmp)) {
				//
			} else {
				$black = $black.' '.$username;
			}
		} else {
			$black = $username;
		}
		$db->query("UPDATE {$DT_PRE}member_misc SET black='$black' WHERE userid=$_userid");
		$chatid = get_chat_id($_username, $username);
		$db->query("DELETE FROM {$table} WHERE chatid='$chatid'");
		userclean($_username);
		dmsg($L['chat_msg_black_success'], '?action=setting');
	break;
	case 'setting':
		if($submit) {
			if($black) {
				$blacks = array();
				$tmp = explode(' ', trim($black));
				foreach($tmp as $v) {
					if((check_name($v) || $v == 'Guest') && !in_array($v, $blacks)) $blacks[] = $v;
				}
				$black = $blacks ? implode(' ', $blacks) : '';
			} else {
				$black = '';
			}
			$reply = strip_tags(trim($reply));
			$db->query("UPDATE {$DT_PRE}member_misc SET black='$black',reply='$reply' WHERE userid=$_userid");
			userclean($_username);
			dmsg($L['op_update_success'], '?action=setting');
		} else {
			$user = $db->get_one("SELECT black,reply FROM {$DT_PRE}member_misc WHERE userid=$_userid");
		}
	break;
	case 'view':
		$chatid or dheader('?action=index');
		$chat = $db->get_one("SELECT * FROM {$table} WHERE chatid='$chatid'");
		($chat && ($chat['fromuser'] == $_username || $chat['touser'] == $_username)) or dheader('?action=index');
		$table = get_chat_tb($chatid);
		(isset($username) && check_name($username)) or $username = '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$condition = "chatid='$chatid'";
		if($keyword) $condition .= " AND content LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND addtime>=$fromtime";
		if($totime) $condition .= " AND addtime<=$totime";
		if($username) $condition .= " AND username='$username'";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY addtime DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$id = $r['itemid'];
			$time = $r['addtime'];
			$name = $r['username'];
			$word = $r['content'];
			if($MOD['chat_url'] || $MOD['chat_img']) {
				if(preg_match_all("/([http|https]+)\:\/\/([a-z0-9\/\-\_\.\,\?\&\#\=\%\+\;]{4,})/i", $word, $m)) {
					foreach($m[0] as $u) {
						if($MOD['chat_img'] && preg_match("/^(jpg|jpeg|gif|png|bmp)$/i", file_ext($u)) && !preg_match("/([\?\&\=]{1,})/i", $u)) {
							$word = str_replace($u, '<img src="'.$u.'" onload="if(this.width>320)this.width=320;" onclick="window.open(this.src);"/>', $word);
						} else if($MOD['chat_img'] && preg_match("/^(mp4)$/i", file_ext($u)) && !preg_match("/([\?\&\=]{1,})/i", $u)) {
							$word = str_replace($u, '<video src="'.$u.'" width="200" height="150" controls="controls"></video>', $word);
						} else if($MOD['chat_url']) {
							$word = str_replace($u, '<a href="'.$u.'" target="_blank">'.$u.'</a>', $word);
						}
					}
				}
			}			
			if(strpos($word, ')') !== false) $word = parse_face($word);			
			if(strpos($word, '[emoji]') !== false) $word = emoji_decode($word);
			$r = array();
			$r['date'] = timetodate($time, 6);
			$r['name'] = $name;
			$r['word'] = $word;
			$lists[] = $r;
		}
	break;
	case 'list':
		$data = '';
		$new = 0;
		$result = $db->query("SELECT * FROM {$table} WHERE fromuser='$_username' OR touser='$_username' ORDER BY lasttime DESC LIMIT 100");
		while($r = $db->fetch_array($result)) {
			if($r['fromuser'] == $_username) {
				$r['user'] = $r['touser'];
				$r['new'] = $r['fnew'];
			} else {					
				$r['user'] = $r['fromuser'];
				$r['new'] = $r['tnew'];
			}
			$new += $r['new'];
			if($r['new'] > 99) $r['new'] = 99;
			$r['last'] = timetodate($r['lasttime'], $r['lasttime'] > $today_endtime - 86400 ? 'H:i:s' : 'y-m-d');
			$r['online'] = online($r['user'], 1);
			if($DT_PC) {
				$data .= '<table cellpadding="0" cellspacing="0" class="bd-b"><tr><td width="60">';
				$data .= '<a href="chat.php?chatid='.$r['chatid'].'" target="chat_'.$r['chatid'].'"><img src="'.useravatar($r['user']).'" width="48"'.($r['online'] ? '' : ' class="chat_offline"').'/></a>';
				$data .= '</td><td><ul>';
				$data .= '<li><span>'.$r['last'].'</span><a href="chat.php?chatid='.$r['chatid'].'" target="chat_'.$r['chatid'].'">'.$r['user'].'</a></li>';
				$data .= '<li>'.($r['new'] ? '<em>'.$r['new'].'</em>' : '').($r['online'] ? $L['chat_online'] : $L['chat_offline']).' '.$r['lastmsg'].'</li>';
				$data .= '</ul></td></tr></table>';
			} else {
				$data .= '<div class="list-img list-chat">';
				$data .= '<a href="chat.php?chatid='.$r['chatid'].'"><img src="'.useravatar($r['user']).'" class="'.($r['online'] ? 'chat_onl' : 'chat_off').'"/></a><ul>';
				$data .= '<li><span class="f_r">'.$r['last'].'</span><a href="chat.php?chatid='.$r['chatid'].'"><strong>'.$r['user'].'</strong></a></li>';
				$data .= '<li>'.($r['new'] ? '<em>'.$r['new'].'</em>' : '').'<span>'.$r['lastmsg'].'</span></li>';
				$data .= '</ul></div>';
			}
		}
		if($new != $_chat) {
			$db->query("UPDATE {$DT_PRE}member SET chat=$new WHERE userid=$_userid");
			$_chat = $new;
		}
		if(!$data) $data = '<div style="padding:100px 0;text-align:center;">'.$L['chat_empty'].'</div>';
		exit($data);
	break;
	default:
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'view') {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = ($kw || $page > 1) ? '?action=view&chatid='.$chatid : 'chat.php?chatid='.$chatid;
	} else {		
		$back_link = 'index.php';
	}
}
include template('im', $module);
?>