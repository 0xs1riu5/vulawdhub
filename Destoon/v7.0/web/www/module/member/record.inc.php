<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$condition = "username='$_username'";
switch($action) {
	case 'pay':
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		isset($currency) or $currency = '';
		$module_select = module_select('mid', $L['module_name'], $mid);
		if($keyword) $condition .= " AND title LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND paytime>=$fromtime";
		if($totime) $condition .= " AND paytime<=$totime";
		if($mid) $condition .= " AND mid=$mid";
		if($itemid) $condition .= " AND itemid=$itemid";
		if($currency) $condition .= " AND currency='$currency'";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_pay WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_pay WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		$fee = 0;
		while($r = $db->fetch_array($result)) {
			$r['paytime'] = timetodate($r['paytime'], 5);
			$fee += $r['fee'];
			$lists[] = $r;
		}
		$head_title = $L['record_title_pay'];	
	break;
	case 'award':
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$module_select = module_select('mid', $L['module_name'], $mid);
		if($keyword) $condition .= " AND title LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND paytime>=$fromtime";
		if($totime) $condition .= " AND paytime<=$totime";
		if($mid) $condition .= " AND mid=$mid";
		if($itemid) $condition .= " AND itemid=$itemid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_award WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_award WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		$fee = 0;
		while($r = $db->fetch_array($result)) {
			$r['paytime'] = timetodate($r['paytime'], 5);
			$fee += $r['fee'];
			$lists[] = $r;
		}
		$head_title = $L['record_title_award'];	
	break;
	case 'login':
		$DT['login_log'] == 2 or dheader('index.php');
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}login WHERE $condition ORDER BY logid DESC LIMIT 0,15");
		while($r = $db->fetch_array($result)) {
			$r['logintime'] = timetodate($r['logintime'], 5);
			$r['area'] = ip2area($r['loginip']);
			$lists[] = $r;
		}
		$head_title = $L['record_title_login'];	
	break;
	default:
		$BANKS = explode('|', trim($MOD['pay_banks']));
		$sfields = $L['record_sfields'];
		$dfields = array('reason', 'amount', 'bank', 'reason', 'note');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		isset($type) or $type = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND addtime>=$fromtime";
		if($totime) $condition .= " AND addtime<=$totime";
		if($type) $condition .= $type == 1 ? " AND amount>0" : " AND amount<0" ;
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_record WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_record WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		$income = $expense = 0;
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['amount'] > 0 ? $income += $r['amount'] : $expense += $r['amount'];
			$lists[] = $r;
		}
		$head_title = $L['record_title'];	
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'login') {
		$back_link = 'index.php';
	} else {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = 'index.php';
	}
}
include template('record', $module);
?>