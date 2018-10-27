<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MG['ask'] or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/include/post.func.php';
$TYPE = get_type('ask', 1);
$TYPE or message($L['feature_close']);
$forward or $forward = '?action=index';
$dstatus = $L['ask_status'];
$r = $db->get_one("SELECT support FROM {$DT_PRE}member WHERE userid=$_userid");
$support = $r['support'] ? $r['support'] : '';
switch($action) {
	case 'add':
		$a = array();
		if($itemid) {
			$r = $db->get_one("SELECT * FROM {$DT_PRE}ask WHERE itemid=$itemid");
			if($r['username'] == $_username && $r['status'] > 1) $a = $r;
		}
		if($submit) {
			$typeid = intval($typeid);
			if(!$typeid || !isset($TYPE[$typeid])) message($L['pass_typeid']);
			if(empty($title)) message($L['pass_title']);
			if(empty($content)) message($L['pass_content']);
			$fields = array(
				'typeid' => $typeid,
				'title' => $title,
				);
			$fields = dhtmlspecialchars($fields);
			$content = dsafe(addslashes(save_remote(save_local(stripslashes($content)))));
			$fields['content'] = $content;
			$fields['qid'] = $a ? $a['itemid'] : 0;
			$fields['username'] = $_username;
			$fields['addtime'] = $DT_TIME;
			$sqlk = $sqlv = '';
			foreach($fields as $k=>$v) {
				$sqlk .= ','.$k; $sqlv .= ",'$v'";
			}
			$sqlk = substr($sqlk, 1); $sqlv = substr($sqlv, 1);
			$db->query("INSERT INTO {$DT_PRE}ask ($sqlk) VALUES ($sqlv)");
			$itemid = $db->insert_id();
			clear_upload($content, $itemid, 'ask');
			dmsg($L['ask_add_success'], '?action=index');
		} else {
			$typeid = isset($typeid) ? intval($typeid) : 0;
			$title = '';
			$content = '';
			if($a) {
				$typeid = $a['typeid'];
				$title = $a['title'];
				$content = $a['content'];
			}
			$type_select = type_select($TYPE, 1, 'typeid', $L['choose_type'], $typeid, 'id="typeid"');
			$head_title = $L['ask_title_add'];
		}
	break;
	case 'edit':
		$itemid or message();
		$r = $db->get_one("SELECT * FROM {$DT_PRE}ask WHERE itemid=$itemid");
		$r or message();
		$r['username'] == $_username or message();
		if($r['status'] > 0) message($L['ask_msg_edit']);
		if($submit) {
			$typeid = intval($typeid);
			if(!$typeid || !isset($TYPE[$typeid])) message($L['pass_typeid']);		
			if(empty($title)) message($L['pass_title']);
			if(empty($content)) message($L['pass_content']);
			$content = dsafe(addslashes(save_remote(save_local(stripslashes($content)))));
			$fields = array(
				'typeid' => $typeid,
				'title' => $title,
				);
			$fields = dhtmlspecialchars($fields);
			$fields['content'] = $content;
			$sql = '';
			foreach($fields as $k=>$v) {
				$sql .= ",$k='$v'";
			}
			$sql = substr($sql, 1);
			$db->query("UPDATE {$DT_PRE}ask SET $sql WHERE itemid=$itemid");
			clear_upload($content, $itemid, 'ask');
			dmsg($L['op_edit_success'], $forward);
		} else {			
			extract($r);
			$type_select = type_select($TYPE, 1, 'typeid', $L['choose_type'], $typeid, 'id="typeid"');
			$head_title = $L['ask_title_edit'];
		}
	break;
	case 'show':
		$itemid or message();
		$r = $db->get_one("SELECT * FROM {$DT_PRE}ask WHERE itemid=$itemid");
		$r or message();
		$r['username'] == $_username or message();
		extract($r);
		$addtime = timetodate($addtime, 5);
		$edittime = $edittime ? timetodate($edittime, 5) : '';
		$stars = $L['ask_star_type'];
		$head_title = $L['ask_title_show'];
	break;
	case 'star':
		$itemid or message();
		$r = $db->get_one("SELECT * FROM {$DT_PRE}ask WHERE itemid=$itemid");
		$r or message();
		$r['username'] == $_username or message();
		$r['star'] == 0 or message();
		$star = isset($star) ? intval($star) : 3;
		in_array($star, array(1, 2, 3)) or $star = 3;
		$db->query("UPDATE {$DT_PRE}ask SET star=$star WHERE itemid=$itemid");
		dmsg($L['ask_star_success'], '?action=show&itemid='.$itemid);
	break;
	case 'delete':
		$itemid or message();
		$r = $db->get_one("SELECT * FROM {$DT_PRE}ask WHERE itemid=$itemid");
		$r or message();
		$r['username'] == $_username or message();
		$r['status'] == 0 or message();
		$db->query("DELETE FROM {$DT_PRE}ask WHERE itemid=$itemid");
		dmsg($L['op_del_success'], $forward);
	break;
	case 'support':
		$support or message($L['support_error_1']);
		$user = userinfo($support);
		$user or message($L['support_error_2']);
		$head_title = $L['support_title'];
	break;
	default:
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$typeid = isset($typeid) ? ($typeid === '' ? -1 : intval($typeid)) : -1;
		$type_select = type_select($TYPE, 1, 'typeid', $L['default_type'], $typeid, '', $L['all_type']);
		$condition = "username='$_username'";
		if($keyword) $condition .= " AND title LIKE '%$keyword%'";
		if($typeid > -1) $condition .= " AND typeid=$typeid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}ask WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}ask WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = $r['edittime'] ? timetodate($r['edittime'], 5) : 'N/A';
			$r['dstatus'] = $dstatus[$r['status']];
			$r['dstar'] = $L['ask_star_type'][$r['star']];
			$r['type'] = $r['typeid'] && isset($TYPE[$r['typeid']]) ? set_style($TYPE[$r['typeid']]['typename'], $TYPE[$r['typeid']]['style']) : $L['default_type'];
			$lists[] = $r;
		}
		$head_title = $L['ask_title'];
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'add' || $action == 'edit' || $action == 'show' || $action == 'support') {
		$back_link = '?action=index';
	} else {
		$time = 'addtime';
		foreach($lists as $k=>$v) {
			$lists[$k]['date'] = timetodate($v[$time], 5);
		}
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = ($kw || $page > 1) ? '?action=index' : 'index.php';
	}
}
include template('ask', $module);
?>