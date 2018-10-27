<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MG['promo_limit'] > -1 or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/include/post.func.php';
$menu_id = 2;
require DT_ROOT.'/module/'.$module.'/promo.class.php';
$do = new promo();
include load('message.lang');
switch($action) {
	case 'add':
		if($MG['promo_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_promo WHERE username='$_username'");
			if($r['num'] >= $MG['promo_limit']) dalert(lang($L['limit_add'], array($MG['promo_limit'], $r['num'])), 'goback');
		}
		if($submit) {
			if($do->pass($post)) {
				$post['open'] = 1;
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
			$fromtime = timetodate($DT_TIME, 3).' 00:00:00';
			$head_title = $L['promo_title_add'];
		}
	break;
	case 'edit':
		$itemid or message();
		$do->itemid = $itemid;
		$r = $do->get_one();
		if(!$r || $r['username'] != $_username) message();
		if($submit) {
			if($do->pass($post)) {
				$post['open'] = $r['open'];
				$post['username'] = $_username;
				$do->edit($post);
				dmsg($L['op_edit_success'], $forward);
			} else {
				message($do->errmsg);
			}
		} else {
			extract($r);
			$fromtime = $fromtime ? timetodate($fromtime, 6) : '';
			$totime = $totime ? timetodate($totime, 6) : '';
			$head_title = $L['promo_title_edit'];
		}
	break;
	case 'delete':
		$itemid or message($L['promo_msg_choose']);
		$itemids = is_array($itemid) ? $itemid : array($itemid);
		foreach($itemids as $itemid) {
			$do->itemid = $itemid;
			$item = $do->get_one();
			if($item && $item['username'] == $_username) $do->delete($itemid);
		}
		dmsg($L['op_del_success'], $forward);
	break;
	case 'coupon':
		$condition = "seller='$_username'";
		isset($username) or $username = '';
		$pid = isset($pid) ? intval($pid) : '';
		if(check_name($username)) $condition .= " AND username='$username'";
		if($itemid) $condition .= " AND itemid=$itemid";
		if($pid) $condition .= " AND pid=$pid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_coupon WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_coupon WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$lists[] = $r;
		}
		$head_title = $L['promo_coupon_title'];
	break;
	default:
		$condition = "username='$_username'";
		if($keyword) $condition .= " AND promo LIKE '%$keyword%'";
		$lists = $do->get_list($condition);
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_promo WHERE username='$_username'");
		$limit_used = $r['num'];
		$limit_free = $MG['promo_limit'] && $MG['promo_limit'] > $limit_used ? $MG['promo_limit'] - $limit_used : 0;
		$head_title = $L['promo_title'];
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'add' || $action == 'edit') {
		$back_link = '?action=index';
	} elseif($action == 'coupon') {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = ($pid || $username || $itemid) ? '?action=coupon' : '?action=index';
	} else {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = ($kw || $page > 1) ? '?action=index' : 'biz.php';
	}
}
include template('promo', $module);
?>