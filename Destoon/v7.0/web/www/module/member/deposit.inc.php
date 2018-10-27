<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
$MG['biz'] or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$menu_id = 2;
switch($action) {
	case 'add':
		$auto = 0;
		$auth = isset($auth) ? decrypt($auth, DT_KEY.'CG') : '';
		if($auth && substr($auth, 0, 8) == 'deposit|') {
			$auto = $submit = 1;
			$num = intval(substr($auth, 8));
		}
		if($submit) {
			$num = intval($num);
			$num >= 1 or $num = 1;
			$money = $MOD['deposit']*$num;
			$money <= $_money or message($L['money_not_enough']);
			if($money <= $DT['quick_pay']) $auto = 1;
			if(!$auto) {
				is_payword($_username, $password) or message($L['error_payword']);
			}
			money_add($_username, -$money);
			money_record($_username, -$money, $L['in_site'], 'system', $L['deposit_title_add']);
			$db->query("INSERT INTO {$DT_PRE}finance_deposit (username,amount,addtime,editor) VALUES ('$_username','$money','$DT_TIME','$_username')");
			$db->query("UPDATE {$DT_PRE}member SET deposit=deposit+$money WHERE userid=$_userid");
			dmsg($L['op_success'], '?action=index');
		} else {
			$amount = $MOD['deposit'];
			if($sum > 1) $amount = $MOD['deposit']*$sum;
			$head_title = $L['deposit_title_add'];
		}
	break;
	default:
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$condition = "username='$_username'";
		if($fromtime) $condition .= " AND addtime>=$fromtime";
		if($totime) $condition .= " AND addtime<=$totime";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_deposit WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_deposit WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		$amount = 0;
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$amount += $r['amount'];
			$lists[] = $r;
		}
		$amount = number_format($amount, 2, '.', ',');
		$head_title = $L['deposit_title'];
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'add') {
		$lists = array();
		for($i = 1; $i < 20; $i++) {
			$lists[$i] = $MOD['deposit']*$i;
		}
		$back_link = '?action=index';
	} else {
		$user = userinfo($_username);
		$_deposit = $user['deposit'];
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = 'biz.php';
	}
}
include template('deposit', $module);
?>