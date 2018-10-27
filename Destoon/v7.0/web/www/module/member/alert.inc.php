<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
$MG['biz'] or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MOD['alertid'] or message($L['feature_close']);
$MG['alert_limit'] > -1 or dalert(lang('message->without_permission_and_upgrade'), 'goback');
$menu_id = 2;
$mids = array();
$tmp = explode('|', $MOD['alertid']);
foreach($tmp as $v) {
	if($v > 4 && isset($MODULE[$v])) $mids[] = $v;
}
require DT_ROOT.'/include/post.func.php';
require DT_ROOT.'/module/'.$module.'/alert.class.php';
$do = new alert();

switch($action) {
	case 'add':
		if($MG['alert_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}alert WHERE username='$_username' AND status>0");
			if($r['num'] >= $MG['alert_limit']) dalert(lang($L['limit_add'], array($MG['alert_limit'], $r['num'])), 'goback');
		}
		if($submit) {
			if($do->pass($post)) {
				$post['username'] = $_username;
				$post['email'] = $_email;
				$need_check =  $MOD['alert_check'] == 2 ? $MG['check'] : $MOD['alert_check'];
				$post['status'] = get_status(3, $need_check);
				$post['addtime'] = $DT_TIME;
				$do->add($post);
				$msg = $L['op_add_success'];
				if($post['status'] == 2) $msg = $msg.' '.$L['op_checking'];
				dmsg($msg, '?status='.$post['status']);
			} else {
				message($do->errmsg);
			}
		} else {
			if(in_array($mid, $mids)) {
				$_mid = $mid;
				foreach($do->fields as $v) {
					$$v = '';
				}
				$mid = $_mid;
				$head_title = $L['alert_add_title'];
			} else {
				dheader('?action=list');
			}
		}
	break;
	case 'list':
		$head_title = $L['alert_add_title'];
	break;
	case 'edit':
		$itemid or message();
		$do->itemid = $itemid;
		$r = $do->get_one();
		if(!$r || $r['username'] != $_username) message();
		if($submit) {
			if($do->pass($post)) {
				$post['username'] = $_username;
				$post['email'] = $_email;
				$need_check =  $MOD['alert_check'] == 2 ? $MG['check'] : $MOD['alert_check'];
				$post['status'] = get_status($r['status'], $need_check);
				$do->edit($post);
				dmsg($L['op_edit_success'], $forward);
			} else {
				message($do->errmsg);
			}
		} else {
			extract($r);
		}
	break;
	case 'delete':
		$itemid or message();
		$itemids = is_array($itemid) ? $itemid : array($itemid);
		foreach($itemids as $itemid) {
			$do->itemid = $itemid;
			$item = $do->get_one();
			if($item && $item['username'] == $_username) $do->delete($itemid);
		}
		dmsg($L['op_del_success'], $forward);
	break;
	default:
		$status = isset($status) ? intval($status) : 3;
		in_array($status, array(2, 3)) or $status = 3;
		$condition = "username='$_username' AND status=$status";
		$lists = $do->get_list($condition);
		if($lists) {
			foreach($lists as $k=>$v) {
				if($v['catid']) $lists[$k]['cate'] = $DT_PC ? cat_pos(get_cat($v['catid']), '-', 1) : strip_tags(cat_pos(get_cat($v['catid']), '-'));
				if($v['email'] != $_email) $db->query("UPDATE {$DT_PRE}alert SET email='$_email' WHERE itemid=$v[itemid]");
			}
		}
		$head_title = $L['alert_title'];
	break;
}
$nums = array();
$limit_used = 0;
for($i = 2; $i < 4; $i++) {
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}alert WHERE username='$_username' AND status=$i");
	$nums[$i] = $r['num'];
	$limit_used += $r['num'];
}
$limit_free = $MG['alert_limit'] && $MG['alert_limit'] > $limit_used ? $MG['alert_limit'] - $limit_used : 0;
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'edit' || $action == 'list') {
		$back_link = '?action=index';
	} else if ($action == 'add') {
		$back_link = '?action=list';
	} else {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = ($kw || $page > 1) ? '?action=index' : 'biz.php';
	}
}
include template('alert', $module);
?>