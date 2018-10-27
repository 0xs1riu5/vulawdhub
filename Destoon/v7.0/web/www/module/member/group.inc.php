<?php 
defined('IN_DESTOON') or exit('Access Denied');
#卖家订单管理
login();
($mid && isset($MODULE[$mid]) && $MODULE[$mid]['module'] == 'group') or dheader($MODULE[2]['linkurl']);
($MG['biz'] && $MG['group_order']) or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
include load('order.lang');
$_status = $L['group_status'];
$dstatus = $L['group_dstatus'];
$_send_status = $L['send_status'];
$dsend_status = $L['send_dstatus'];
$step = isset($step) ? trim($step) : '';
$timenow = timetodate($DT_TIME, 3);
$memberurl = $MOD['linkurl'];
$myurl = userurl($_username);
$table = $DT_PRE.'group_order_'.$mid;
$menu_id = 2;
if($action == 'update') {
	$itemid or message();
	$td = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	$td or message($L['group_msg_null']);
	if($td['seller'] != $_username) message($L['group_msg_deny']);
	$td['adddate'] = timetodate($td['addtime'], 5);
	$td['updatedate'] = timetodate($td['updatetime'], 5);
	$td['total'] = $td['amount'];
	$td['linkurl'] = ($DT_PC ? DT_PATH : DT_MOB).'api/redirect.php?mid='.$mid.'&itemid='.$td['gid'];
	$td['fee_name'] = $td['fee'] = $td['par'] = '';
	$lists = array($td);
	$gid = $td['gid'];
	switch($step) {
		case 'detail':
			$auth = encrypt('group|'.$td['send_type'].'|'.$td['send_no'].'|'.$td['send_status'].'|'.$td['itemid'], DT_KEY.'EXPRESS');
			$head_title = $L['group_detail_title'];
		break;
		case 'express':
			($td['send_type'] && $td['send_no']) or dheader('?action=update&step=detail&itemid='.$itemid);
			$auth = encrypt('group|'.$td['send_type'].'|'.$td['send_no'].'|'.$td['send_status'].'|'.$td['itemid'], DT_KEY.'EXPRESS');
			$head_title = $L['group_express_title'];
		break;
		case 'used':
			if($td['seller'] == $_username) {
				if($td['status'] != 0 || $td['logistic']) message();
				$date = timetodate($DT_TIME, 6);
				$db->query("UPDATE {$table} SET status=2,send_time='$date',updatetime=$DT_TIME WHERE itemid=$itemid");
				dmsg($L['op_success'], '?page='.$page);
			} else {
				if($td['status'] != 2 || $td['logistic']) message();
				//交易成功
				$money = $td['amount'];
				money_add($td['seller'], $money);
				money_record($td['seller'], $money, $L['in_site'], 'system', $L['group_record_pay'], $L['group_order_id'].$itemid);
				//网站服务费
				$G = $db->get_one("SELECT groupid FROM {$DT_PRE}member WHERE username='".$td['seller']."'");
				$SG = cache_read('group-'.$G['groupid'].'.php');
				if($SG['commission']) {
					$fee = dround($money*$SG['commission']/100);
					if($fee > 0) {
						money_add($td['seller'], -$fee);
						money_record($td['seller'], -$fee, $L['in_site'], 'system', $L['trade_fee'], $L['trade_order_id'].$itemid);	
					}
				}
				$db->query("UPDATE {$table} SET status=3,updatetime=$DT_TIME WHERE itemid=$itemid");
				dmsg($L['group_success'], '?mid='.$mid.'&action=order&page='.$page);
			}
		break;
		case 'send':
			if($td['status'] != 0 || !$td['logistic']) message();
			if($submit) {
				$send_type = dhtmlspecialchars($send_type);
				if(strlen($send_type) > 2 && strlen($send_no) < 5) message($L['msg_express_no']);
				if(strlen($send_no) > 4 && strlen($send_type) < 3) message($L['msg_express_type']);
				if($send_no && !preg_match("/^[a-z0-9_\-]{4,}$/i", $send_no)) message($L['msg_express_no_error']);
				is_date($send_time) or message($L['msg_express_date_error']);
				$db->query("UPDATE {$table} SET status=1,updatetime=$DT_TIME,send_type='$send_type',send_no='$send_no',send_time='$send_time' WHERE itemid=$itemid");
				dmsg($L['op_success'], '?mid='.$mid.'&page='.$page);
			} else {
				$head_title = $L['group_send_title'];
				$send_types = explode('|', trim($MOD['send_types']));
				$send_time = timetodate($DT_TIME, 3);
			}
		break;
		case 'add_time'://增加确认收货时间
			if(!in_array($td['status'], array(1, 2))) message();
			if($submit) {
				$add_time = intval($add_time);
				$add_time > 0 or message($L['group_addtime_null']);
				$add_time = $td['add_time'] + $add_time;
				$db->query("UPDATE {$table} SET add_time='$add_time' WHERE itemid=$itemid");
				message($L['group_addtime_success'], $forward);
			} else {
				$head_title = $L['group_addtime_title'];
			}
		break;
		case 'print'://订单打印
			if(!$td['logistic']) message($L['group_msg_deny']);
			$td['total'] = $td['amount'];
			include template('group_print', $module);
			exit;
		break;
		case 'refund_agree'://卖家同意买家退款
			if($td['status'] != 4) message($L['trade_msg_deny']);
			$money = $td['amount'];
			if($submit) {
				$content .= $L['trade_refund_by_seller'];
				clear_upload($content, $itemid, $table);
				$content = dsafe(addslashes(save_remote(save_local(stripslashes($content)))));
				is_payword($_username, $password) or message($L['error_payword']);
				money_add($td['buyer'], $money);
				money_record($td['buyer'], $money, $L['in_site'], 'system', $L['trade_refund'], $L['group_order_id'].$itemid.$L['trade_refund_by_seller']);
				$db->query("UPDATE {$table} SET status=5,editor='$_username',updatetime=$DT_TIME,refund_reason='$content' WHERE itemid=$itemid");
				message($L['trade_refund_agree_success'], $forward, 3);
			} else {
				$head_title = $L['trade_refund_agree_title'];
			}
		break;
		case 'get_pay'://买家确认超时 卖家申请直接付款
			$gone = $DT_TIME - $td['updatetime'];
			if(!in_array($td['status'], array(1, 2)) || $gone < ($MOD['trade_day']*86400 + $td['add_time']*3600)) message($L['group_msg_deny']);
			//交易成功
			$money = $td['amount'];
			money_add($td['seller'], $money);
			money_record($td['seller'], $money, $L['in_site'], 'system', $L['group_record_pay'], lang($L['group_buyer_timeout'], array($itemid)));
			//网站服务费
			$G = $db->get_one("SELECT groupid FROM {$DT_PRE}member WHERE username='".$td['seller']."'");
			$SG = cache_read('group-'.$G['groupid'].'.php');
			if($SG['commission']) {
				$fee = dround($money*$SG['commission']/100);
				if($fee > 0) {
					money_add($td['seller'], -$fee);
					money_record($td['seller'], -$fee, $L['in_site'], 'system', $L['trade_fee'], $L['trade_order_id'].$itemid);	
				}
			}
			$db->query("UPDATE {$table} SET status=3,updatetime=$DT_TIME WHERE itemid=$itemid");
			message($L['group_success'], $forward, 3);
		break;
	}
} else if($action == 'express') {//我的快递
	$sfields = $L['express_sfields'];
	$dfields = array('title', 'title', 'send_type ', 'send_no');
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	$status = isset($status) && isset($dsend_status[$status]) ? intval($status) : '';
	$fields_select = dselect($sfields, 'fields', '', $fields);
	$status_select = dselect($dsend_status, 'status', $L['status'], $status, '', 1, '', 1);
	$condition = "send_no<>'' AND seller='$_username'";
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($status !== '') $condition .= " AND send_status='$status'";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
	$items = $r['num'];
	$pages = $DT_PC ? pages($items, $page, $pagesize) : mobile_pages($items, $page, $pagesize);
	$lists = array();
	$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
	while($r = $db->fetch_array($result)) {
		$r['addtime'] = timetodate($r['addtime'], 5);
		$r['updatetime'] = timetodate($r['updatetime'], 5);
		$r['dstatus'] = $_send_status[$r['send_status']];
		$lists[] = $r;
	}
	$head_title = $L['express_title'];
} else {
	$sfields = $L['group_sfields'];
	$dfields = array('title', 'title ', 'amount', 'password', 'buyer', 'buyer_name', 'buyer_address', 'buyer_postcode', 'buyer_mobile', 'buyer_phone', 'send_type', 'send_no', 'note');
	$gid = isset($gid) ? intval($gid) : 0;
	(isset($buyer) && check_name($buyer)) or $buyer = '';
	(isset($pass) && preg_match("/^[a-z0-9]{6}$/", $pass)) or $pass = '';
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	$fromdate = isset($fromdate) ? $fromdate : '';
	$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
	$todate = isset($todate) ? $todate : '';
	$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
	$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';
	$nav = isset($nav) ? intval($nav) : -1;
	$fields_select = dselect($sfields, 'fields', '', $fields);
	$status_select = dselect($dstatus, 'status', $L['status'], $status, '', 1, '', 1);
	$condition = "seller='$_username'";
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($fromtime) $condition .= " AND addtime>=$fromtime";
	if($totime) $condition .= " AND addtime<=$totime";
	if($status !== '') $condition .= " AND status='$status'";
	if($itemid) $condition .= " AND itemid=$itemid";
	if($gid) $condition .= " AND gid=$gid";
	if($buyer) $condition .= " AND buyer='$buyer'";
	if($pass) $condition .= " AND password='$pass'";
	if(in_array($nav, array(0,1,2,4,5,6))) $condition .= " AND status=$nav";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
	$items = $r['num'];
	$pages = $DT_PC ? pages($items, $page, $pagesize) : mobile_pages($items, $page, $pagesize);
	$lists = array();
	$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
	$amount = $fee = $money = 0;
	while($r = $db->fetch_array($result)) {
		$r['gone'] = $DT_TIME - $r['updatetime'];
		if($r['status'] == 1 || $r['status'] == 2) {
			if($r['gone'] > ($MOD['trade_day']*86400 + $r['add_time']*3600)) {
				$r['lefttime'] = 0;
			} else {
				$r['lefttime'] = secondstodate($MOD['trade_day']*86400 + $r['add_time']*3600 - $r['gone']);
			}
		}
		$r['addtime'] = str_replace(' ', '<br/>', timetodate($r['addtime'], $DT_PC ? 5 : 3));
		$r['updatetime'] = str_replace(' ', '<br/>', timetodate($r['updatetime'], 5));
		$r['linkurl'] = ($DT_PC ? DT_PATH : DT_MOB).'api/redirect.php?mid='.$mid.'&itemid='.$r['gid'];
		$r['dstatus'] = $_status[$r['status']];
		$r['money'] = $r['amount'];
		$r['money'] = number_format($r['money'], 2, '.', '');
		$amount += $r['amount'];
		$lists[] = $r;
	}
	$money = $amount + $fee;
	$money = number_format($money, 2, '.', '');
	$forward = urlencode($DT_URL);
	$head_title = $L['group_title'];
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'update') {
		$back_link = '?mid='.$mid.'&action=index';
	} else if($action == 'express') {
		$back_link = ($kw || $page > 1) ? '?mid='.$mid.'&action='.$action : '?mid='.$mid.'&action=index';
	} else {
		$back_link = ($kw || $page > 1) ? '?mid='.$mid.'&action=index' : 'biz.php';
	}
}
include template('group', $module);
?>