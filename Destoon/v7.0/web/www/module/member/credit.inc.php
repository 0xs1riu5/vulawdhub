<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
switch($action) {
	case 'exchange':
		if($MOD['credit_exchange'] && $MOD['ex_host'] && $MOD['ex_user'] && $MOD['ex_data'] && $MOD['ex_type']) {
			include DT_ROOT.'/config.inc.php';
			$db_class = 'db_'.$CFG['database'];
			$ex = new $db_class;
			$ex->connect($MOD['ex_host'], $MOD['ex_user'], $MOD['ex_pass'], $MOD['ex_data'], $CFG['db_expires'], $CFG['db_charset'], $CFG['pconnect']);
			$fd = $MOD['ex_fdnm'];
			$px = $MOD['ex_prex'];
			if($MOD['ex_type'] == 'PW') {
				$tb = $px.'memberdata';
				$r = $ex->get_one("SELECT `uid` FROM `{$px}members` WHERE `username`='$_passport'");
				if($r) {
					$uid = $r['uid'];
					$r = $ex->get_one("SELECT `$fd` FROM `{$tb}` WHERE `uid`=$uid");
					$num = intval($r[$fd]);
				} else {
					message($L['credit_msg_active'], '?action=index');
				}
			} elseif($MOD['ex_type'] == 'DZX') {
				$tb = $px.'common_member_count';
				$r = $ex->get_one("SELECT `uid` FROM `{$px}common_member` WHERE `username`='$_passport'");
				if($r) {
					$uid = $r['uid'];
					$r = $ex->get_one("SELECT `$fd` FROM `{$tb}` WHERE `uid`=$uid");
					$num = intval($r[$fd]);
				} else {
					message($L['credit_msg_active'], '?action=index');
				}
			} else {
				$tb = $px.'members';
				$r = $ex->get_one("SELECT `uid`,`$fd` FROM `{$tb}` WHERE `username`='$_passport'");
				if($r) {
					$uid = $r['uid'];
					$num = intval($r[$fd]);
				} else {
					message($L['credit_msg_active'], '?action=index');
				}
			}
			if($submit) {
				$num > 0 or message($L['credit_pass_ex_min']);
				$amount = intval($amount);
				if($amount > 0 && $amount <= $num) {
					$ex->query("UPDATE `{$tb}` SET `{$fd}`=`{$fd}`-{$amount} WHERE `uid`=$uid");
					$db = new $db_class;
					$db->halt = DT_DEBUG ? 1 : 0;
					$db->pre = $CFG['tb_pre'];
					$db->connect($CFG['db_host'], $CFG['db_user'], $CFG['db_pass'], $CFG['db_name'], $CFG['db_expires'], $CFG['db_charset'], $CFG['pconnect']);
					credit_add($_username, $amount*$MOD['ex_rate']);
					credit_record($_username, $amount*$MOD['ex_rate'], 'system', $L['credit_exchange_title'], $amount.$MOD['ex_name']);
					dmsg($L['credit_msg_exchange'], '?action=index');
				} else {
					message($L['credit_pass_ex_max'].$num);
				}
			}
		} else {
			message($L['feature_close'], '?action=index');
		}
		$head_title = $L['credit_exchange_title'];
	break;
	case 'buy':
		if($MOD['credit_buy'] && $MOD['credit_price']) {
			$C = explode('|', trim($MOD['credit_buy']));
			$P = explode('|', trim($MOD['credit_price']));
			$auto = 0;
			$auth = isset($auth) ? decrypt($auth, DT_KEY.'CG') : '';
			if($auth && substr($auth, 0, 7) == 'credit|') {
				$auto = $submit = 1;
				$type = intval(substr($auth, 7));
			}
			if($submit) {
				array_key_exists($type, $C) or message($L['credit_msg_buy_amount']);
				$amount = $P[$type];
				$credit = $C[$type];
				if($amount > 0) {
					$amount <= $_money or message($L['money_not_enough']);
					if($amount <= $DT['quick_pay']) $auto = 1;
					if(!$auto) {
						is_payword($_username, $password) or message($L['error_payword']);
					}
					money_add($_username, -$amount);
					money_record($_username, -$amount, $L['in_site'], 'system', $L['buy'].$DT['credit_name'], $credit.$DT['credit_unit']);
					if($credit > 0) {
						credit_add($_username, $credit);
						credit_record($_username, $credit, 'system', $L['buy'].$DT['credit_name'], $amount.$DT['money_unit']);
					}
				}
				dmsg($L['credit_msg_buy_success'], '?action=index');
			} else {
				$select = isset($C[$sum]) ? $sum : 0;
			}
		} else {
			message($L['feature_close'], '?action=index');
		}
		$head_title = $L['credit_buy_title'];
	break;
	case 'invite':
		$head_title = $L['invite_title'];
		$url = $MOD['linkurl'].'invite.php?user='.$_username;
	break;
	case 'less':
		if($_credit < 0) message($L['credit_msg_less'], '?action=index');
		dheader('?action=index');
	break;
	default:
		$sfields = $L['credit_fields'];
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
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_credit WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_credit WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		$income = $expense = 0;
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['amount'] > 0 ? $income += $r['amount'] : $expense += $r['amount'];
			$lists[] = $r;
		}
		$head_title = $L['credit_title'];
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'buy' || $action == 'invite') {
		$back_link = '?action=index';
	} else {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = 'index.php';
	}
}
include template('credit', $module);
?>