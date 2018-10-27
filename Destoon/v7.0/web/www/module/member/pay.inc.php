<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
($mid > 4 && isset($MODULE[$mid]) && !$MODULE[$mid]['islink']) or dheader($DT_PC ? DT_PATH : DT_MOB);
$moduleid = $mid;
$MOD = cache_read('module-'.$mid.'.php');
$itemid or dheader($DT_PC ? $MOD['linkurl'] : $MOD['mobile']);
$table = $MOD['module'] == 'job' ? $DT_PRE.'job_resume_'.$mid : get_table($mid);
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
if(!$item || $item['status'] < 3) dheader($DT_PC ? $MOD['linkurl'] : $MOD['mobile']);
$title = $item['title'];
$username = $item['username'];
if($MOD['module'] == 'know') {
	$aid = intval($item['aid']);
	$best = $aid ? $db->get_one("SELECT * FROM {$DT_PRE}know_answer_{$mid} WHERE itemid=$aid") : array();
	if($best && $best['username']) $username = $best['username'];
}
if($_username == $username) message($L['pay_msg_self']);

$fee = get_fee($item['fee'], $MOD['fee_view']);
$currency = $MOD['fee_currency'];
$unit = $currency == 'money' ? $DT['money_unit'] : $DT['credit_unit'];
$name = $currency == 'money' ? $DT['money_name'] : $DT['credit_name'];
$fee_back = min(abs(dround($MOD['fee_back'])), 100);
$fee_back = $currency == 'money' ? dround($fee*$fee_back/100) : ceil($fee*$fee_back/100);
$forward = strpos($item['linkurl'], '://') === false ? ($DT_PC ? $MOD['linkurl'] : $MOD['mobile']).$item['linkurl'] : $item['linkurl'];
$note = $MOD['name'].($MOD['module'] == 'job' ? $L['resume_name'] : '').':'.$itemid;
($fee && !check_pay($mid, $itemid)) or dheader($forward);
if($currency == 'credit') {
	$fee = intval($fee);
	if($_credit >= $fee) {
		$db->query("INSERT INTO {$DT_PRE}finance_pay (mid,tid,username,fee,currency,paytime,ip,title) VALUES ('$mid','$itemid','$_username','$fee','$currency','$DT_TIME','$DT_IP','".addslashes($title)."')");
		credit_add($_username, -$fee);
		credit_record($_username, -$fee, 'system', $L['pay_record_view'], $note);
		if($username && $fee_back) {
			credit_add($username, $fee_back);
			credit_record($username, $fee_back, 'system', $L['pay_record_back'], $note);
		}
		message($L['pay_msg_success'], $forward);
	} else {
		dheader('credit.php?action=buy');
	}
}
$discount = $MG['discount'] > 0 && $MG['discount'] < 100 ? $MG['discount'] : 100;
$discount = dround($discount/100);
$auto = 0;
$auth = isset($auth) ? decrypt($auth, DT_KEY.'CG') : '';
if($auth && $auth == 'pay|'.$mid.'|'.$itemid) {
	$auto = $submit = 1;
}
if($submit) {
	$fee = dround($fee*$discount);
	$fee > 0 or message($L['pay_msg_fee']);
	$fee <= $_money or message($L['money_not_enough']);
	if($fee <= $DT['quick_pay']) $auto = 1;
	if(!$auto) {
		is_payword($_username, $password) or message($L['error_payword']);
	}
	money_add($_username, -$fee);
	money_record($_username, -$fee, $L['in_site'], 'system', $L['pay_record_view'], $note);
	if($username && $fee_back) {
		if($MOD['module'] == 'know') {
			$aid = intval($item['aid']);
			$best = $aid ? $db->get_one("SELECT * FROM {$DT_PRE}know_answer_{$mid} WHERE itemid=$aid") : array();
			if($best && $best['username']) $username = $best['username'];
		}
		money_add($username, $fee_back);
		money_record($username, $fee_back, $L['in_site'], 'system', $L['pay_record_back'], $note);
	}
	$db->query("INSERT INTO {$DT_PRE}finance_pay (mid,tid,username,fee,currency,paytime,ip,title) VALUES ('$mid','$itemid','$_username','$fee','$currency','$DT_TIME','$DT_IP','".addslashes($title)."')");
	message($L['pay_msg_success'], $forward);
} else {
	$head_title = $L['pay_title'];
	if($DT_PC) {
		if($EXT['mobile_enable']) $head_mobile = str_replace($MODULE[2]['linkurl'], $MODULE[2]['mobile'], $DT_URL);
	} else {
		$foot = '';
		$head_name = $L['pay_title'];
		$back_link = $forward;
	}
	$member_fee = dround($fee*$discount);
	include template('pay', $module);
}
?>