<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MG['address_limit'] > -1 or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/include/post.func.php';
require DT_ROOT.'/module/'.$module.'/address.class.php';
$do = new address();
include load('message.lang');
switch($action) {
	case 'add':
		if($MG['address_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}address WHERE username='$_username'");
			if($r['num'] >= $MG['address_limit']) dalert(lang($L['limit_add'], array($MG['address_limit'], $r['num'])), 'goback');
		}
		if($submit) {
			if($do->pass($post)) {
				$post['username'] = $_username;
				$do->add($post);
				dmsg($L['op_add_success'], '?action=index');
			} else {
				message($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				$$v = '';
			}
			$head_title = $L['address_title_add'];
		}
	break;
	case 'edit':
		$itemid or message();
		$do->itemid = $itemid;
		$r = $do->get_one();
		if(!$r || $r['username'] != $_username) message();
		if($submit) {
			if($do->pass($post)) {
				$post['username'] = $_username;
				$do->edit($post);
				dmsg($L['op_edit_success'], $forward);
			} else {
				message($do->errmsg);
			}
		} else {
			extract($r);
			$head_title = $L['address_title_edit'];
		}
	break;
	case 'delete':
		$itemid or message($L['address_msg_choose']);
		$itemids = is_array($itemid) ? $itemid : array($itemid);
		foreach($itemids as $itemid) {
			$do->itemid = $itemid;
			$item = $do->get_one();
			if($item && $item['username'] == $_username) $do->delete($itemid);
		}
		dmsg($L['op_del_success'], $forward);
	break;
	default:
		$condition = "username='$_username'";
		if($keyword) $condition .= " AND address LIKE '%$keyword%'";
		$lists = $do->get_list($condition);
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}address WHERE username='$_username'");
		$limit_used = $r['num'];
		$limit_free = $MG['address_limit'] && $MG['address_limit'] > $limit_used ? $MG['address_limit'] - $limit_used : 0;
		$head_title = $L['address_title'];
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'add' || $action == 'edit') {
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
include template('address', $module);
?>