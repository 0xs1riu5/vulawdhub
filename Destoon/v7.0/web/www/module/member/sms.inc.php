<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
$DT['sms'] or message($L['feature_close']);
$MG['biz'] or dalert(lang('message->without_permission_and_upgrade'), 'goback');
$MG['sms'] or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$_user = $db->get_one("SELECT mobile,vmobile FROM {$DT_PRE}member WHERE userid=$_userid");
if(!$_user['mobile'] || !$_user['vmobile']) message($L['sms_msg_validate'], 'validate.php?action=mobile');
require DT_ROOT.'/include/post.func.php';
$mobile = $_user['mobile'];
$menu_id = 2;
switch($action) {
	case 'buy':
		$fee = $DT['sms_fee'];
		$fee or message($L['sms_msg_no_price']);
		if($fee) {
			$auto = 0;
			$auth = isset($auth) ? decrypt($auth, DT_KEY.'CG') : '';
			if($auth && substr($auth, 0, 4) == 'sms|') {
				$auto = $submit = 1;
				$total = intval(substr($auth, 4));
			}
			if($submit) {
				$total = intval($total);
				$total > 0 or message($L['sms_msg_buy_num']);
				$amount = dround($total*$fee);
				if($amount > 0) {
					$amount <= $_money or message($L['money_not_enough']);
					if($amount <= $DT['quick_pay']) $auto = 1;
					if(!$auto) {
						is_payword($_username, $password) or message($L['error_payword']);
					}
					money_add($_username, -$amount);
					money_record($_username, -$amount, $L['in_site'], 'system', $L['sms_buy_note'], $total);
					sms_add($_username, $total);
					sms_record($_username, $total, 'system', $L['sms_buy_record'], $amount.$DT['money_unit']);
				}
				dmsg($L['sms_buy_success'], '?action=index');
			}
		} else {
			message($L['sms_msg_no_price']);
		}
		$head_title = $L['sms_buy_title'];
	break;
	case 'record':
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$condition = "editor='$_username'";
		if($keyword) $condition .= " AND message LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND sendtime>=$fromtime";
		if($totime) $condition .= " AND sendtime<=$totime";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}sms WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}sms WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['message'] = preg_replace("/:([0-9]{6}),/", ':******,', $r['message']);
			$r['sendtime'] = $DT_PC ? str_replace(' ', '<br/>', timetodate($r['sendtime'], 6)) : timetodate($r['sendtime'], 6);
			$r['num'] = ceil($r['word']/$DT['sms_len']);
			$lists[] = $r;
		}
		$head_title = $L['sms_send_title'];
	break;
	default:
		$sfields = $L['sms_sfields'];
		$dfields = array('reason', 'amount', 'reason', 'note');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		isset($type) or $type = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = "username='$_username'";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND addtime>=$fromtime";
		if($totime) $condition .= " AND addtime<=$totime";
		if($type) $condition .= $type == 1 ? " AND amount>0" : " AND amount<0" ;
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_sms WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_sms WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		$income = $expense = 0;
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 6);
			$r['amount'] > 0 ? $income += $r['amount'] : $expense += $r['amount'];
			$lists[] = $r;
		}
		$head_title = $L['sms_title'];
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'buy') {
		$back_link = '?action=index';
	} else if($action == 'send') {
		$back_link = '?action=record';
	} else if($action == 'record') {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = '?action=index';
	} else {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = 'biz.php';
	}
}
include template('sms', $module);
?>