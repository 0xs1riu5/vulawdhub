<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
$DT['im_web'] or dheader('index.php');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$chatid = (isset($chatid) && is_md5($chatid)) ? $chatid : '';
$table = $DT_PRE.'chat';
$chat_poll = intval($MOD['chat_poll']);
/*
function get_chat_file($chatid) {
	return DT_ROOT.'/file/chat/'.substr($chatid, 0, 2).'/'.$chatid.'.php';
}
*/
function emoji_encode($str){
    $string = '';
    $length = mb_strlen($str, DT_CHARSET);
    for($i = 0; $i < $length; $i++) {
        $tmp = mb_substr($str, $i, 1, DT_CHARSET);    
        if(strlen($tmp) >= 4) {
            $string .= '[emoji]'.rawurlencode($tmp).'[/emoji]';
        } else {
            $string .= $tmp;
        }
    }
    return $string;
}

switch($action) {
	case 'send':		
		$chatid or exit('ko');
		trim($word) or exit('ko');
		if($MOD['chat_maxlen'] && strlen($word) > $MOD['chat_maxlen']*3) exit('max');
		$word = stripslashes(trim($word));
		$word = strip_tags($word);
		$word = dsafe($word);
		$word = nl2br($word);
		$word = strip_nr($word);
		if(!$DT_PC) $word = emoji_encode($word);
		$word = str_replace('|', ' ', $word);
		if($MOD['chat_file'] && $MG['upload']) clear_upload($word, $_userid, $table);
		$chat = $db->get_one("SELECT * FROM {$table} WHERE chatid='$chatid'");
		if($chat) {
			$lastmsg = strip_tags($word);
			if(!$DT_PC) $lastmsg = preg_replace('/\[emoji\](.+?)\[\/emoji\]/', "(:emoji:)", $lastmsg);
			$lastmsg = dsubstr($lastmsg, 50);
			$lastmsg = addslashes($lastmsg);
			if($chat['touser'] == $_username) {
				$sql = "fgettime=$DT_TIME,lasttime=$DT_TIME,lastmsg='$lastmsg'";
				if($DT_TIME - $chat['freadtime'] > $chat_poll) {
					$db->query("UPDATE {$DT_PRE}member SET chat=chat+1 WHERE username='$chat[fromuser]'");
					$sql .= ",fnew=fnew+1";
				}
				$db->query("UPDATE {$table} SET {$sql} WHERE chatid='$chatid'");
			} else if($chat['fromuser'] == $_username) {
				$sql = "tgettime=$DT_TIME,lasttime=$DT_TIME,lastmsg='$lastmsg'";
				if($DT_TIME - $chat['treadtime'] > $chat_poll) {
					$db->query("UPDATE {$DT_PRE}member SET chat=chat+1 WHERE username='$chat[touser]'");
					$sql .= ",tnew=tnew+1";
				}
				$db->query("UPDATE {$table} SET {$sql} WHERE chatid='$chatid'");
			} else {
				exit('ko');
			}
		} else {
			exit('ko');
		}
		$font_s = $font_s ? intval($font_s) : 0;
		$font_c = $font_c ? intval($font_c) : 0;
		$font_b = $font_b ? 1 : 0;
		$font_i = $font_i ? 1 : 0;
		$font_u = $font_u ? 1 : 0;
		$css = '';
		if($font_s) $css .= ' s'.$font_s;
		if($font_c) $css .= ' c'.$font_c;
		if($font_b) $css .= ' fb';
		if($font_i) $css .= ' fi';
		if($font_u) $css .= ' fu';
		if($css) $word = '<span class="'.trim($css).'">'.$word.'</span>';
		if($word) {
			$content = addslashes($word);
			$db->query("INSERT INTO ".get_chat_tb($chatid)." (chatid,username,addtime,content) VALUES ('$chatid','$_username','$DT_TIME','$content')");
			exit('ok');
		}
		exit('ko');
	break;
	case 'load':
		$chatid or exit;
		$tb = get_chat_tb($chatid);
		$chat = $db->get_one("SELECT * FROM {$table} WHERE chatid='$chatid'");
		if($chat) {
			if($chat['touser'] == $_username) {
				$db->query("UPDATE {$table} SET treadtime=$DT_TIME,tnew=0 WHERE chatid='$chatid'");
			} else if($chat['fromuser'] == $_username) {
				$db->query("UPDATE {$table} SET freadtime=$DT_TIME,fnew=0 WHERE chatid='$chatid'");
				if($DT_TIME - $chat['lasttime'] > 86400*7) {
					$r = $db->get_one("SELECT reply FROM {$DT_PRE}member_misc WHERE username='$chat[touser]'");
					if($r['reply']) {
						$content = addslashes(nl2br($r['reply']));
						$time = $DT_TIME + 10;
						$db->query("INSERT INTO {$tb} (chatid,username,addtime,content) VALUES ('$chatid','$chat[touser]','$time','$content')");
						$db->query("UPDATE {$table} SET lasttime=$time WHERE chatid='$chatid'");
					}
				}
			} else {
				exit('0');
			}
		} else {
			exit('0');
		}
		$chatlast = $_chatlast = intval($chatlast);
		$first = isset($first) ? intval($first) : 0;
		$i = $j = 0;
		$chat_lastuser = '';
		$chat_repeat = 0;
		$json = '';
		$time1 = 0;
		if($chatlast < 1 || $chat['lasttime'] > $chatlast) {
			if($chatlast < 1) {				
				$result = $db->query("SELECT addtime FROM {$tb} WHERE chatid='$chatid' ORDER BY addtime DESC LIMIT $pagesize");
				while($r = $db->fetch_array($result)) {
					$chatlast = $r['addtime'];
				}
				if($chatlast > 1) $chatlast--;
			}
			$result = $db->query("SELECT itemid,addtime,username,content FROM {$tb} WHERE chatid='$chatid' AND addtime>$chatlast ORDER BY addtime ASC LIMIT $pagesize");
			while($r = $db->fetch_array($result)) {
				$id = $r['itemid'];
				$time = $r['addtime'];
				$name = $r['username'];
				$word = $r['content'];
				if($_username == $name) { $chat_repeat++; } else {$chat_repeat = 0;}
				$chat_lastuser = $name;
				$chatlast = $time;
				$time2 = $time;
				if($time2 - $time1 < 600) {
					$date = '';
				} else {
					$date = timetodate($time2, 5);
					$time1 = $time2;
				}
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
				$word = str_replace('"', '\"', $word);
				$self = $_username == $name ? 1 : 0;
				if($self) {
					//$name = 'Me';
				} else {
					$j++;
				}
				$json .= ($i ? ',' : '').'{time:"'.$time.'",date:"'.$date.'",name:"'.$name.'",word:"'.$word.'",self:"'.$self.'"}';
				$i = 1;
			}
		}
		if($_chatlast == 0) $j = 0;
		$json = '{chat_msg:['.$json.'],chat_new:"'.$j.'",chat_last:"'.$chatlast.'"}';
		exit($json);
	break;
	case 'down':
		if($data && check_name($username) && is_md5($chatid)) {
			$chat = $db->get_one("SELECT * FROM {$table} WHERE chatid='$chatid'");
			if($chat['fromuser'] == $_username) {
				$chat['touser'] == $username or exit;
			} else {
				$chat['fromuser'] == $username or exit;
			}
			$data = stripslashes(dsafe($data));
			$css = file_get('image/chat.css');
			$css = str_replace('#chat {width:auto;height:366px;overflow:auto;', '#chat {width:700px;margin:auto;', $css);
			$css = str_replace('margin:100px 0 0 0;', 'margin:0;', $css);
			$css = str_replace("url('", "url('".$MOD['linkurl']."image/", $css);
			$data = str_replace('o<em></em>n', 'on', $data);
			$data = '<!DOCTYPE html><html><head><meta charset="'.DT_CHARSET.'"/><title>'.lang($L['chat_record'], array($username)).'</title><style type="text/css">'.$css.'</style><base href="'.$MOD['linkurl'].'"/></head><body><div id="chat">'.$data.'</div></body></html>';
			file_down('', 'chat-'.$username.'-'.timetodate($DT_TIME, 'Y-m-d-H-i').'.html', $data);
		}
		exit;
	break;
	default:
		$item = array();
		if(isset($touser) && check_name($touser)) {
			if($touser == $_username) dalert($L['chat_msg_self'], 'im.php');
			$MG['chat'] or dalert($L['chat_msg_no_rights'], 'grade.php');
			$user = userinfo($touser);
			$user or dalert($L['chat_msg_user'], 'im.php');
			$chatid = get_chat_id($_username, $touser);
			$chat_id = $chatid;
			if($user['black']) {
				$black = explode(' ', $user['black']);
				if(in_array($_username, $black)) {
					$db->query("DELETE FROM {$table} WHERE chatid='$chatid'");
					dalert($L['chat_msg_refuse'], 'im.php');
				}
			}
			$online = online($user['userid']);
			$head_title = lang($L['chat_with'], array($user['company']));
			$forward = is_url($forward) ? addslashes(dhtmlspecialchars($forward)) : '';
			if(strpos($forward, $MOD['linkurl']) !== false) $forward = '';
			$chat = $db->get_one("SELECT * FROM {$table} WHERE chatid='$chatid'");
			if($chat) {
				$db->query("UPDATE {$table} SET forward='$forward' WHERE chatid='$chatid'");
			} else {
				$db->query("INSERT INTO {$table} (chatid,fromuser,touser,tgettime,forward) VALUES ('$chat_id','$_username','$touser','0','$forward')");
			}
			$type = 1;
			if($mid > 4 && $itemid) {
				$r = DB::get_one("SELECT * FROM ".get_table($mid)." WHERE itemid=$itemid");
				if($r && $r['status'] > 2 && $touser==$r['username']) {
					if(strpos($r['linkurl'], '://') == false) $r['linkurl'] = $MODULE[$mid]['linkurl'].$r['linkurl'];
					$item = $r;
				}
			}
		} else if(isset($chatid) && is_md5($chatid)) {
			$chat = $db->get_one("SELECT * FROM {$table} WHERE chatid='$chatid'");
			if($chat && ($chat['touser'] == $_username || $chat['fromuser'] == $_username)) {
				if($chat['touser'] == $_username) {
					$user = userinfo($chat['fromuser']);
				} else if($chat['fromuser'] == $_username) {
					$user = userinfo($chat['touser']);
				}
				if($user['black']) {
					$black = explode(' ', $user['black']);
					if(in_array($_username, $black)) {						
						$db->query("DELETE FROM {$table} WHERE chatid='$chatid'");
						dalert($L['chat_msg_refuse'], 'im.php');
					}
				}
				$online = online($user['userid']);
				$chat_id = $chatid;
				$head_title = lang($L['chat_with'], array($user['company']));
			} else {
				dheader('im.php');
			}
			$type = 2;
		} else {
			dheader('im.php');
		}
		$faces = get_face();
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
}
include template('chat', $module);
?>