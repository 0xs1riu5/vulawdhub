<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MG['friend_limit'] > -1 or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/include/post.func.php';
$TYPE = get_type('friend-'.$_userid);
require DT_ROOT.'/module/'.$module.'/friend.class.php';
$do = new friend();
switch($action) {
	case 'add':
		if($MG['friend_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}friend WHERE userid=$_userid");
			if($r['num'] >= $MG['friend_limit']) dalert(lang($L['limit_add'], array($MG['friend_limit'], $r['num'])), 'goback');
		}
		if($submit) {
			if($do->pass($post)) {
				if($post['username'] && $db->get_one("SELECT username FROM {$DT_PRE}friend WHERE userid=$_userid AND username='$post[username]'")) message($L['friend_msg_add_again']);
				$post['userid'] = $_userid;
				$post['addtime'] = $DT_TIME;
				$do->add($post);
				dmsg($L['op_add_success'], '?action=index');
			} else {
				message($do->errmsg);
			}
		} else {
			$username = isset($username) ? trim($username) : '';
			$truename = $style = $note = $listorder = $mobile = $email = $homepage = $company = $career = $telephone = $qq = $wx = $ali = $skype = '';
			if($username) {
				$r = userinfo($username);
				if($r) {
					$truename = $r['truename'];
					$homepage = userurl($username);
					$company = $r['company'];
					$telephone = $r['telephone'];
					$career = $r['career'];
					$qq = $r['qq'];
					$wx = $r['wx'];
					$ali = $r['ali'];
					$skype = $r['skype'];
				}
			}
			$typeid = 0;
			$type_select = type_select($TYPE, 0, 'post[typeid]', $L['default_type']);
			$head_title = $L['friend_title_add'];
		}
		break;
	case 'edit':
		$itemid or message();
		$do->itemid = $itemid;
		$r = $do->get_one();
		if(!$r || $r['userid'] != $_userid) message();
		if($submit) {
			if($do->pass($post)) {
				$do->edit($post);
				dmsg($L['op_edit_success'], $forward);
			} else {
				message($do->errmsg);
			}
		} else {
			extract($r);
			$type_select = type_select($TYPE, 0, 'post[typeid]', $L['default_type'], $typeid);
			$head_title = $L['friend_title_edit'];
		}
	break;
	case 'show':
		$itemid or message();
		$do->itemid = $itemid;
		$r = $do->get_one();
		if(!$r || $r['userid'] != $_userid) message();
		extract($r);
		$head_title = $L['friend_title_show'];
	break;
	case 'delete':
		$itemid or message($L['friend_msg_choose']);	
		$itemids = is_array($itemid) ? $itemid : array($itemid);
		foreach($itemids as $itemid) {
			$do->itemid = $itemid;
			$item = $do->get_one();
			if(!$item || $item['userid'] != $_userid) message();
			$do->delete($itemid);
		}
		dmsg($L['op_del_success'], $forward);
	break;
	case 'my':
		$from = isset($from) ? $from : '';
		$condition = "userid=$_userid";
		if($from == 'sms') {
			$condition .= " AND mobile<>''";
		} else {
			$condition .= " AND username<>''";
		}

		$sfields = $L['friend_sfields'];
		$dfields = array('company', 'truename', 'company', 'career', 'telephone', 'mobile', 'homepage', 'email', 'qq', 'wx', 'ali', 'skype', 'username', 'note');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}friend WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT itemid,username,truename,company,mobile,note FROM {$DT_PRE}friend WHERE $condition ORDER BY listorder DESC,itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$lists[] = $r;
		}
		$head_title = $L['friend_title'];
		include template('friend_my', $module);
		exit;
	break;
	default:
		$sfields = $L['friend_sfields'];
		$dfields = array('company', 'truename', 'company', 'career', 'telephone', 'mobile', 'homepage', 'email', 'qq', 'ali', 'skype', 'username', 'note');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$typeid = isset($typeid) ? ($typeid === '' ? -1 : intval($typeid)) : -1;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$type_select = type_select($TYPE, 0, 'typeid', $L['default_type'], $typeid, '', $L['all_type']);
		$condition = "userid=$_userid";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($typeid > -1) $condition .= " AND typeid=$typeid";		
		$lists = $do->get_list($condition);
		if($MG['friend_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}friend WHERE userid=$_userid");
			$limit_used = $r['num'];
			$limit_free = $MG['friend_limit'] > $limit_used ? $MG['friend_limit'] - $limit_used : 0;
		}
		$head_title = $L['friend_title'];
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'add' || $action == 'edit' || $action == 'show') {
		$back_link = '?action=index';
	} else {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = ($kw || $page > 1) ? '?action=index' : 'index.php';
	}
}
include template('friend', $module);
?>