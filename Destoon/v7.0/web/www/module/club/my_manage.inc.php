<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
if($action) {
	$gid = isset($gid) ? intval($gid) : 0;
	$gid or message();
	$GRP = get_group($gid);
	($GRP && $GRP['status'] == 3) or message($L['my_not_group']);
	if(!is_admin($GRP)) message($L['my_not_admin']);
	$REASON = explode('|', trim($MOD['manage_reasons']));
	if($MOD['manage_message'] == 2) $message = 1;
	if($MOD['manage_reason']) {
		$need_reason = 0;
		if(in_array($action, array('edit', 'reply_edit'))) {
			if($submit) $need_reason = 1;
		} else if(in_array($action, array('delete', 'reply_delete', 'level', 'ontop', 'style'))){
			$need_reason = 1;
		}
		if($need_reason && ($reason == $L['my_manage_reason'] || strlen($reason) < 4)) message($L['my_manage_input_reason']);
	}
}
switch($action) {
	case 'edit':
		$itemid or message();
		require DT_ROOT.'/module/'.$module.'/club.class.php';
		$do = new club($moduleid);
		$do->itemid = $itemid;
		$T = $do->get_one();
		if(!$T || $T['gid'] != $gid || $T['status'] != 3) message($L['my_not_post']);		
		if($submit) {
			$_post = daddslashes($T);
			$_post['addtime'] = timetodate($T['addtime']);
			$_post['title'] = $post['title'];
			$_post['thumb'] = $post['thumb'];
			$_post['content'] = $post['content'];
			$post = $_post;
			if($do->pass($post)) {		
				$do->edit($post);
				$tid = $itemid;
				$title = $_post['title'];
				$content = '';
				if($reason == $L['my_manage_reason']) $reason = '';
				$reason = dhtmlspecialchars($reason);
				$message = isset($message) ? 1 : 0;
				if($message) send_message($T['username'], lang($L['manage_msg_title'], array($L['my_manage_type_post'], dsubstr($T['title'], 20, '...'), $L['my_manage_type_edit'])), lang($L['manage_msg_content'], array(($DT_PC ? $MOD['linkurl'] : $MOD['mobile']).$T['linkurl'], nl2br($reason), $_username)));
				$db->query("INSERT INTO {$table_manage} (gid,tid,username,addtime,typeid,title,content,reason,message) VALUES ('$gid','$itemid','$_username','$DT_TIME','2','$title','$content','$reason','$message')");
				dmsg($L['post_success_edit'], $forward);
			} else {
				message($do->errmsg);
			}
		} else {
			extract($T);
		}
	break; 
	case 'delete':
		($itemid && is_array($itemid)) or message($L['my_choose_post']);
		$content = '';
		if($reason == $L['my_manage_reason']) $reason = '';
		$reason = dhtmlspecialchars($reason);
		$message = isset($message) ? 1 : 0;
		require DT_ROOT.'/module/'.$module.'/club.class.php';
		$do = new club($moduleid);
		foreach($itemid as $tid) {
			$do->itemid = $tid;
			$T = $do->get_one();
			if(!$T || $T['status'] != 3 || $T['gid'] != $gid) continue;
			$do->recycle($tid);
			if($message) send_message($T['username'], lang($L['manage_msg_title'], array($L['my_manage_type_post'], dsubstr($T['title'], 20, '...'), $L['my_manage_type_del'])), lang($L['manage_msg_content'], array(($DT_PC ? $MOD['linkurl'] : $MOD['mobile']).$T['linkurl'], nl2br($reason), $_username)));
			$title = addslashes($T['title']);
			$db->query("INSERT INTO {$table_manage} (gid,tid,username,addtime,typeid,title,content,reason,message) VALUES ('$gid','$tid','$_username','$DT_TIME','1','$title','$content','$reason','$message')");
		}
		dmsg($L['post_success_del'], $forward);
	break;
	case 'style':
		($itemid && is_array($itemid)) or message($L['my_choose_post']);
		$STYLE = array('red' => '#FF0000', 'blue' => '#0000FF', 'orange' => '#FF6600');
		$_style = $style;
		$style = isset($STYLE[$style]) ? $STYLE[$style] : '';
		$content = $style;
		if($reason == $L['my_manage_reason']) $reason = '';
		$reason = dhtmlspecialchars($reason);
		$message = isset($message) ? 1 : 0;
		foreach($itemid as $tid) {
			$T = $db->get_one("SELECT * FROM {$table} WHERE itemid=$tid");
			if(!$T || $T['status'] != 3 || $T['gid'] != $gid || $T['style'] == $style) continue;
			$db->query("UPDATE {$table} SET style='$style' WHERE itemid=$tid");
			if($message) send_message($T['username'], lang($L['manage_msg_title'], array($L['my_manage_type_post'], dsubstr($T['title'], 20, '...'), $style ? $L['my_manage_type_style'] : $L['my_manage_type_style_cancel'])), lang($L['manage_msg_content'], array($MOD['linkurl'].$T['linkurl'], nl2br($reason), $_username)));
			$title = addslashes($T['title']);
			$db->query("INSERT INTO {$table_manage} (gid,tid,username,addtime,typeid,title,content,reason,message) VALUES ('$gid','$tid','$_username','$DT_TIME','5','$title','$content','$reason','$message')");
			if($MOD['show_html']) tohtml('show', $module, "itemid=$tid");
		}
		dmsg($style ? $L['post_success_style'] : $L['post_cancel_style'], $forward);
	break;
	case 'ontop':
		($itemid && is_array($itemid)) or message($L['my_choose_post']);
		$ontop = $ontop ? 1 : 0;
		$content = $ontop;
		if($reason == $L['my_manage_reason']) $reason = '';
		$reason = dhtmlspecialchars($reason);
		$message = isset($message) ? 1 : 0;
		foreach($itemid as $tid) {
			$T = $db->get_one("SELECT * FROM {$table} WHERE itemid=$tid");
			if(!$T || $T['status'] != 3 || $T['gid'] != $gid || $T['ontop'] == $ontop || $T['ontop'] == 2) continue;
			$db->query("UPDATE {$table} SET ontop=$ontop WHERE itemid=$tid");
			if($message) send_message($T['username'], lang($L['manage_msg_title'], array($L['my_manage_type_post'], dsubstr($T['title'], 20, '...'), $ontop ? $L['my_manage_type_ontop'] : $L['my_manage_type_ontop_cancel'])), lang($L['manage_msg_content'], array($MOD['linkurl'].$T['linkurl'], nl2br($reason), $_username)));
			$title = addslashes($T['title']);
			$db->query("INSERT INTO {$table_manage} (gid,tid,username,addtime,typeid,title,content,reason,message) VALUES ('$gid','$tid','$_username','$DT_TIME','4','$title','$content','$reason','$message')");
			if($MOD['show_html']) tohtml('show', $module, "itemid=$tid");
		}
		dmsg($ontop ? $L['post_success_ontop'] : $L['post_cancel_ontop'], $forward);
	break;
	case 'level':
		($itemid && is_array($itemid)) or message($L['my_choose_post']);
		$level = isset($level) ? intval($level) : 0;
		in_array($level, array(0, 1, 2, 3)) or message($L['my_manage_not_level']);
		$content = $level;
		if($reason == $L['my_manage_reason']) $reason = '';
		$reason = dhtmlspecialchars($reason);
		$message = isset($message) ? 1 : 0;
		foreach($itemid as $tid) {
			$T = $db->get_one("SELECT * FROM {$table} WHERE itemid=$tid");
			if(!$T || $T['status'] != 3 || $T['gid'] != $gid || $T['level'] == $level) continue;
			$db->query("UPDATE {$table} SET level=$level WHERE itemid=$tid");
			if($message) send_message($T['username'], lang($L['manage_msg_title'], array($L['my_manage_type_post'], dsubstr($T['title'], 20, '...'), $level ? $L['my_manage_type_level'] : $L['my_manage_type_level_cancel'])), lang($L['manage_msg_content'], array($MOD['linkurl'].$T['linkurl'], nl2br($reason), $_username)));
			$title = addslashes($T['title']);
			$db->query("INSERT INTO {$table_manage} (gid,tid,username,addtime,typeid,title,content,reason,message) VALUES ('$gid','$tid','$_username','$DT_TIME','3','$title','$content','$reason','$message')");
			if($MOD['show_html']) tohtml('show', $module, "itemid=$tid");
		}
		dmsg($level ? $L['post_success_level'] : $L['post_cancel_level'], $forward);
	break;
	case 'post':
		require DT_ROOT.'/module/'.$module.'/club.class.php';
		$do = new club($moduleid);
		$sfields = $L['my_fields_post'];
		$dfields = array('keyword', 'title', 'username');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$level = isset($level) ? intval($level) : 0;
		$style = isset($style) ? intval($style) : 0;
		$ontop = isset($ontop) ? intval($ontop) : 0;
		$thumb = isset($thumb) ? intval($thumb) : 0;
		$guest = isset($guest) ? intval($guest) : 0;
		
		$fields_select = dselect($sfields, 'fields', '', $fields);

		$condition = "gid=$gid AND status=3";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($itemid) $condition .= " AND itemid=$itemid";

		if($level) $condition .= " AND level>0";
		if($style) $condition .= " AND style<>''";
		if($ontop) $condition .= " AND ontop>0";
		if($thumb) $condition .= " AND thumb<>''";
		if($guest) $condition .= " AND username=''";
		$lists = $do->get_list($condition);
	break;
	case 'reply':
		require DT_ROOT.'/module/'.$module.'/reply.class.php';
		$do = new reply();
		$sfields = $L['my_fields_reply'];
		$dfields = array('content', 'username');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$tid = isset($tid) ? intval($tid) : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = "gid=$gid AND status=3";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($tid) $condition .= " AND tid=$tid";
		$lists = $do->get_list($condition, 'itemid ASC');
	break;
	case 'reply_edit':
		$itemid or message();
		require DT_ROOT.'/module/'.$module.'/reply.class.php';
		$do = new reply();
		$do->itemid = $itemid;
		$R = $do->get_one();
		if(!$R || $R['gid'] != $gid || $R['status'] != 3) message($L['my_not_reply']);		
		if($submit) {
			$_post = daddslashes($R);
			$_post['content'] = $post['content'];
			$post = $_post;
			if($do->pass($post)) {		
				$do->edit($post);
				$tid = $itemid;
				$title = addslashes(get_intro(stripslashes($post['content']), 50));
				$content = '';
				if($reason == $L['my_manage_reason']) $reason = '';
				$reason = dhtmlspecialchars($reason);
				$message = isset($message) ? 1 : 0;
				if($message) send_message($R['username'], lang($L['manage_msg_title'], array($L['my_manage_type_reply'], get_intro($R['content'], 20), $L['my_manage_type_edit'])), lang($L['manage_msg_content'], array(($DT_PC ? $MOD['linkurl'] : $MOD['mobile']).'goto.php?itemid='.$R['itemid'], nl2br($reason), $_username)));
				$db->query("INSERT INTO {$table_manage} (gid,rid,username,addtime,typeid,title,content,reason,message) VALUES ('$gid','$itemid','$_username','$DT_TIME','2','$title','$content','$reason','$message')");
				dmsg($L['reply_success_edit'], $forward);
			} else {
				message($do->errmsg);
			}
		} else {
			extract($R);
		}
	break; 
	case 'reply_delete':
		($itemid && is_array($itemid)) or message($L['my_choose_reply']);
		$content = '';
		if($reason == $L['my_manage_reason']) $reason = '';
		$reason = dhtmlspecialchars($reason);
		$message = isset($message) ? 1 : 0;
		require DT_ROOT.'/module/'.$module.'/reply.class.php';
		$do = new reply();
		foreach($itemid as $rid) {
			$do->itemid = $rid;
			$R = $do->get_one();
			if(!$R || $R['status'] != 3 || $R['gid'] != $gid) continue;
			$do->recycle($rid);
			if($message) send_message($R['username'], lang($L['manage_msg_title'], array($L['my_manage_type_reply'], get_intro($R['content'], 20), $L['my_manage_type_del'])), lang($L['manage_msg_content'], array($MOD['linkurl'].'goto.php?itemid='.$R['itemid'], nl2br($reason), $_username)));
			$title = addslashes(get_intro($R['content'], 50));
			$db->query("INSERT INTO {$table_manage} (gid,rid,username,addtime,typeid,title,content,reason,message) VALUES ('$gid','$rid','$_username','$DT_TIME','1','$title','$content','$reason','$message')");
		}
		dmsg($L['reply_success_del'], $forward);
	break;
	default:
		require DT_ROOT.'/module/'.$module.'/manage.class.php';
		$do = new manage();
		$sfields = $L['my_fields_manage'];
		$dfields = array('title', 'reason', 'content');

		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$typeid = isset($typeid) ? intval($typeid) : 0;
		$message = isset($message) ? intval($message) : -1;

		$fields_select = dselect($sfields, 'fields', '', $fields);

		$condition = "username='$_username'";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($typeid) $condition .= " AND typeid='$typeid'";
		if($message > -1) $condition .= " AND message='$message'";
		$lists = $do->get_list($condition);

		$open_name = $open_url = '';
		if(isset($gid)) {
			$gid = intval($gid);
			$pid = isset($pid) ? intval($pid) : 1;
			$GRP = get_group($gid);
			if($GRP && $GRP['status'] == 3) {
				$open_name = '['.$GRP['title'].']'.$L['my_manage_post'];
				$open_url = '?mid='.$mid.'&job='.$job.'&action=post&gid='.$gid.'&page='.$pid;
			}
		} else if(isset($tid)) {
			$tid = intval($tid);
			if($tid) {
				$T = $db->get_one("SELECT * FROM {$table} WHERE itemid=$tid");
				if($T && $T['status'] == 3) {
					$gid = $T['gid'];
					$GRP = get_group($gid);
					if($GRP && $GRP['status'] == 3) {
						$open_name ='['.$GRP['title'].']'.$L['my_manage_post'];
						$open_url = '?mid='.$mid.'&job='.$job.'&action=post&gid='.$gid.'&itemid='.$tid;
					}
				}
			}
		} else if(isset($rid)) {
			$rid = intval($rid);
			if($rid) {
				$T = $db->get_one("SELECT * FROM {$table_reply} WHERE itemid=$rid");
				if($T && $T['status'] == 3) {
					$gid = $T['gid'];
					$tid = $T['tid'];
					$GRP = get_group($gid);
					if($GRP && $GRP['status'] == 3) {
						$open_name = '['.$GRP['title'].']'.$L['my_manage_reply'];
						$open_url = '?mid='.$mid.'&job='.$job.'&action=reply&gid='.$gid.'&tid='.$tid.'&itemid='.$rid.'&page='.$pid;
					}
				}
			}
		}
	break;
}
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MODULE[2]['linkurl'], $MODULE[2]['mobile'], $DT_URL);
} else {
	$foot = '';
	if($action == 'add' || $action == 'edit') {
		$back_link = '?mid='.$mid.'&job='.$job;
	} else {
		foreach($lists as $k=>$v) {
			$lists[$k]['linkurl'] = str_replace($MOD['linkurl'], $MOD['mobile'], $v['linkurl']);
			$lists[$k]['date'] = timetodate($v['addtime'], 5);
		}
		$pages = mobile_pages($items, $page, $pagesize);
		$foot = '';
		$back_link = ($kw || $page > 1) ? '?mid='.$mid.'&job='.$job.'&status='.$status : '?mid='.$mid.'&job='.$job;
	}
}
$head_title = $L['my_manage_title'];
include template($MOD['template_my_manage'] ? $MOD['template_my_manage'] : 'my_club_manage', 'member');
?>