<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
($MG['biz'] && $MG['homepage'] && $MG['page_limit'] > -1) or dalert(lang('message->without_permission_and_upgrade'), 'goback');
if($MG['type'] && !$_edittime && $action == 'add') dheader($MODULE[2]['linkurl'].'edit.php?tab=2');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
include load('my.lang');
require DT_ROOT.'/module/'.$module.'/page.class.php';
$do = new page();
switch($action) {
	case 'add':
		if($_credit < 0 && $MOD['credit_less']) dheader('credit.php?action=less');
		if($MG['hour_limit']) {
			$today = $DT_TIME - 3600;
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}page WHERE username='$_username' AND addtime>$today");
			if($r && $r['num'] >= $MG['hour_limit']) dalert(lang($L['hour_limit'], array($MG['hour_limit'])), '?action=index');
		}
		if($MG['day_limit']) {
			$today = $today_endtime - 86400;
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}page WHERE username='$_username' AND addtime>$today");
			if($r && $r['num'] >= $MG['day_limit']) dalert(lang($L['day_limit'], array($MG['day_limit'])), '?action=index');
		}
		if($MG['page_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}page WHERE username='$_username' AND status>0");
			if($r['num'] >= $MG['page_limit']) dalert(lang($L['limit_add'], array($MG['page_limit'], $r['num'])), '?action=index');
		}
		if($submit) {
			if($do->pass($post)) {
				$post['username'] = $_username;
				$post['level'] = 0;
				$need_check =  $MOD['page_check'] == 2 ? $MG['check'] : $MOD['page_check'];
				$post['status'] = get_status(3, $need_check);
				$do->add($post);
				dmsg($L['op_add_success'], '?status='.$post['status']);
			} else {
				message($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				$$v = '';
			}
			$content = '';	
			$head_title = $L['page_title_add'];
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
				$need_check =  $MOD['page_check'] == 2 ? $MG['check'] : $MOD['page_check'];
				$post['status'] = get_status($r['status'], $need_check);
				$post['level'] = $r['level'];
				$do->edit($post);
				dmsg($L['op_edit_success'], $forward);
			} else {
				message($do->errmsg);
			}
		} else {
			extract($r);
			$head_title = $L['page_title_edit'];
		}
	break;
	case 'delete':
		$itemid or message($L['page_msg_choose']);
		$itemids = is_array($itemid) ? $itemid : array($itemid);
		foreach($itemids as $itemid) {
			$do->itemid = $itemid;
			$item = $do->get_one();
			if($item && $item['username'] == $_username) $do->recycle($itemid);
		}
		dmsg($L['op_del_success'], $forward);
	break;
	default:
		$status = isset($status) ? intval($status) : 3;
		in_array($status, array(1, 2, 3)) or $status = 3;
		$condition = "username='$_username' AND status=$status";
		if($keyword) $condition .= " AND title LIKE '%$keyword%'";
		$lists = $do->get_list($condition);
		$head_title = $L['page_title'];
	break;
}
$nums = array();
$limit_used = 0;
for($i = 1; $i < 4; $i++) {
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}page WHERE username='$_username' AND status=$i");
	$nums[$i] = $r['num'];
	$limit_used += $r['num'];
}
$limit_free = $MG['page_limit'] && $MG['page_limit'] > $limit_used ? $MG['page_limit'] - $limit_used : 0;
if($DT_PC) {	
	$menu_id = 2;
} else {
	$foot = '';
	if($action == 'add' || $action == 'edit') {
		$back_link = '?action=index';
	} else {
		$time = 'addtime';
		foreach($lists as $k=>$v) {
			$lists[$k]['linkurl'] = str_replace($MOD['linkurl'], $MOD['mobile'], $v['linkurl']);
			$lists[$k]['date'] = timetodate($v[$time], 5);
		}
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = ($kw || $page > 1) ? '?status='.$status : 'biz.php';
	}
}
include template('page', $module);
?>