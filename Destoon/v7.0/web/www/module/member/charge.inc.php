<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$PAY = cache_read('pay.php');
$amount = isset($amount) ? dround($amount) : '';
function get_reason_url($r = array()) {
	$url = '';
	$arr = explode('|', $r['reason']);
	switch($arr[0]) {
		case 'deposit':
			$url = 'deposit.php?action=add&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		case 'credit':
			$url = 'credit.php?action=buy&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		case 'sms':
			$url = 'sms.php?action=buy&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		case 'vip':
			$url = 'account.php?action=vip&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		case 'grade':
			$url = 'account.php?action=grade&groupid='.intval($arr[1]).'&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		case 'style':
			if(is_numeric($arr[1]) && is_numeric($arr[2])) $url = 'style.php?action=buy&itemid='.intval($arr[1]).'&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		case 'spread':
			if(is_numeric($arr[1]) && $arr[2]) $url = 'spread.php?action=add&mid='.intval($arr[1]).'&word='.urlencode(decrypt($arr[2], DT_KEY.'CR')).'&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		case 'pay':
			if(is_numeric($arr[1]) && is_numeric($arr[2])) $url = 'pay.php?mid='.intval($arr[1]).'&itemid='.intval($arr[2]).'&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		case 'award':
			if(is_numeric($arr[1]) && is_numeric($arr[2])) $url = 'award.php?mid='.intval($arr[1]).'&itemid='.intval($arr[2]).'&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		case 'trade':
			if(is_numeric($arr[1])) $url = 'order.php?action=update&step=pay&itemid='.intval($arr[1]).'&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		case 'trades':
			$url = 'order.php?action=muti&step=pay&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		case 'group':
			if(is_numeric($arr[1])) $url = 'deal.php?mid='.intval($arr[2]).'&action=update&step=pay&itemid='.intval($arr[1]).'&auth='.encrypt($r['reason'], DT_KEY.'CG', 600);
		break;
		default:
		break;
	}
	return $url;
}
function get_reason($reason) {
	global $L;
	$str = '';
	$arr = explode('|', $reason);
	switch($arr[0]) {
		case 'deposit':
			$str = $L['charge_reason_deposit'];
		break;
		case 'credit':
			$str = $L['charge_reason_credit'];
		break;
		case 'sms':
			$str = $L['charge_reason_sms'];
		break;
		case 'vip':
			$str = $L['charge_reason_vip'];
		break;
		case 'grade':
			$str = $L['charge_reason_grade'];
		break;
		case 'style':
			$str = $L['charge_reason_style'];
		break;
		case 'spread':
			$str = $L['charge_reason_spread'];
		break;
		case 'pay':
			$str = $L['charge_reason_pay'];
			if(is_numeric($arr[1]) && is_numeric($arr[2])) {
				$t = DB::get_one("SELECT title FROM ".get_table(intval($arr[1]))." WHERE itemid=".intval($arr[2]));
				if($t) $str = $t['title'];
			}
		break;
		case 'award':
			$str = $L['charge_reason_award'];
			if(is_numeric($arr[1]) && is_numeric($arr[2])) {
				$t = DB::get_one("SELECT title FROM ".get_table(intval($arr[1]))." WHERE itemid=".intval($arr[2]));
				if($t) $str = $t['title'];
			}
		break;
		case 'trade':
			if(is_numeric($arr[1])) {
				$t = DB::get_one("SELECT title FROM ".DT_PRE."order WHERE itemid=".intval($arr[1]));
				if($t) $str = $t['title'];
			}
		break;
		case 'trades':
			$ids = explode(',', $arr[1]);
			$t = DB::get_one("SELECT title FROM ".DT_PRE."order WHERE itemid=".intval($ids[0]));
			if($t) $str = $L['charge_reason_muti'].$t['title'].'...';
		break;
		case 'group':
			if(is_numeric($arr[1])) {
				$t = DB::get_one("SELECT title FROM ".DT_PRE."group_order_".intval($arr[2])." WHERE itemid=".intval($arr[1]));
				if($t) $str = $t['title'];
			}
		break;
		default:
		break;
	}
	return $str;
}
switch($action) {
	case 'record':
		$PAY['card']['name'] = $L['charge_card_name'];
		$_status = $L['charge_status'];
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$minamount = isset($minamount) ? intval($minamount) : '';
		$minamount or $minamount = '';
		$maxamount = isset($maxamount) ? intval($maxamount) : '';
		$maxamount or $maxamount = '';
		$condition = "username='$_username'";
		if($fromtime) $condition .= " AND sendtime>=$fromtime";
		if($totime) $condition .= " AND sendtime<=$totime";
		if($minamount)  $condition .= " AND amount>=$minamount";
		if($maxamount)  $condition .= " AND amount<=$maxamount";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_charge WHERE $condition");
		$items = $r['num'];
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$amount = $fee = $money = $repay = 0;
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_charge WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['repay'] = ($r['status'] == 0 && $DT_TIME - $r['sendtime'] > 600) ? 1 : 0;
			if($r['repay']) $repay = 1;
			$r['sendtime'] = timetodate($r['sendtime'], 5);
			$r['receivetime'] = $r['receivetime'] ? timetodate($r['receivetime'], 5) : '--';
			$r['dstatus'] = $_status[$r['status']];
			$amount += $r['amount'];
			$fee += $r['fee'];
			$money += $r['money'];
			$lists[] = $r;
		}
		$head_title = $L['charge_title_record'];
	break;
	case 'card':
		if($submit) {
			if(!preg_match("/^[0-9a-zA-z]{6,}$/", $number)) message($L['charge_pass_card_number']);
			if(!preg_match("/^[0-9]{6,}$/", $password)) message($L['charge_pass_card_password']);
			$card = $db->get_one("SELECT * FROM {$DT_PRE}finance_card WHERE number='$number'");
			if($card) {
				if($card['updatetime']) message($L['charge_pass_card_used']);
				if($card['totime'] < $DT_TIME) message($L['charge_pass_card_expired']);
				if($card['password'] != $password) message($L['charge_pass_card_error_password']);
				$db->query("INSERT INTO {$DT_PRE}finance_charge (username,bank,amount,money,sendtime,receivetime,editor,status,note) VALUES ('$_username','card', '$card[amount]','$card[amount]','$DT_TIME','$DT_TIME','system','3','$number')");
				$db->query("UPDATE {$DT_PRE}finance_card SET username='$_username',updatetime='$DT_TIME',ip='$DT_IP' WHERE itemid='$card[itemid]'");
				money_add($_username, $card['amount']);
				money_record($_username, $card['amount'], $L['charge_card_name'], 'system', $L['charge_card'], $L['charge_card_number'].':'.$number);
				message($L['charge_msg_card_success'], '?action=record');
			} else {
				message($L['charge_pass_card_error_number']);
			}
		}
	break;
	case 'repay':
		$itemid or dheader('?action=record');
		$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE itemid=$itemid");
		($r && $r['status'] == 0 && $DT_TIME - $r['sendtime'] > 600 && $_username == $r['username']) or dheader('?action=record');
		$amount = $r['amount'];
		$fee = $r['fee'];
		$charge = $fee + $amount;
		$bank = $r['bank'];
		$orderid = $itemid;
		$receive_url = $MOD['linkurl'].'charge.php';
		$charge_title = get_reason($r['reason']);
		set_cookie('pay_id', $orderid);
		include DT_ROOT.'/api/pay/'.$bank.'/send.inc.php';
		exit;
	break;
	case 'confirm':
		if($MOD['mincharge']) {
			$amount >= intval($MOD['mincharge']) or message($L['charge_pass_amount_min'].$MOD['mincharge']);
		} else {			
			$amount > 0 or message($L['charge_pass_type_amount']);
		}
		isset($PAY[$bank]) or message($L['charge_pass_bank']);
		$PAY[$bank]['enable'] or message($L['charge_pass_bank_close']);
		$fee = $PAY[$bank]['percent'] ? dround($amount*$PAY[$bank]['percent']/100) : 0;
		$charge = $fee + $amount;
		preg_match("/^[a-z0-9_\-\,\|]{3,}$/i", $reason) or $reason = '';
		$auto = isset($auto) ? $auto : 1;//debug?
		if($fee == 0) $auto = 1;
		if($auto) $goto = 1;
		if(isset($goto)) {
			$receive_url = ($DT_PC ? $MOD['linkurl'] : $MOD['mobile']).'charge.php';
			$charge_title = get_reason($reason);
			$db->query("INSERT INTO {$DT_PRE}finance_charge (username,bank,amount,fee,sendtime,reason) VALUES ('$_username','$bank','$amount','$fee','$DT_TIME','$reason')");
			$orderid = $db->insert_id();
			set_cookie('pay_id', $orderid);
			set_cookie('pay_bank', $bank);
			include DT_ROOT.'/api/pay/'.$bank.'/send.inc.php';
			exit;
		} else {
			$head_title = $L['charge_title_confirm'];
		}
	break;
	case 'pay':
		$MOD['pay_online'] or dheader('?action=card');
		$auto = 0;
		if($MOD['mincharge']) {
			$mincharge = intval($MOD['mincharge']);
		} else {
			$mincharge = 0;
			if($amount) $auto = 1;
		}
		isset($reason) or $reason = '';
		(isset($bank) && isset($PAY[$bank]) && $PAY[$bank]['enable']) or $bank = '';
		$PAYLIST = get_paylist();
		$bank or $bank = $PAYLIST[0]['bank'];
		$total = count($PAYLIST);
		$head_title = $L['charge_title_pay'];
	break;
	default:
		$_POST = $_DPOST;
		$_GET = $_DGET;
		$head_title = $L['charge_title'];
		//$passed = true;
		$charge_errcode = '';
		$charge_status = 0;
		$charge_forward = '';
		/*
		0 fail
		1 success
		2 unknow
		*/
		$pay_id = intval(get_cookie('pay_id'));
		if($pay_id) {
			$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE itemid=$pay_id");
			if($r && $r['username'] == $_username) {
				//
			} else {
				$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE username='$_username' ORDER BY itemid DESC");
			}
		} else {
			$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE username='$_username' ORDER BY itemid DESC");
		}
		if($r) {
			$charge_orderid = $r['itemid'];
			$charge_money = $r['amount'] + $r['fee'];
			$charge_amount = $r['amount'];
			if($r['status'] == 0) {
				$receive_url = '';
				$bank = $r['bank'];
				$editor = 'R'.$bank;
				$note = '';
				include DT_ROOT.'/api/pay/'.$bank.'/receive.inc.php';
				if($charge_status == 1) {
					$db->query("UPDATE {$DT_PRE}finance_charge SET status=3,money=$charge_money,receivetime='$DT_TIME',editor='$editor' WHERE itemid=$charge_orderid");
					money_add($r['username'], $r['amount']);
					money_record($r['username'], $r['amount'], $PAY[$bank]['name'], 'system', $L['charge_online'], $L['charge_id'].':'.$charge_orderid);
					if($MOD['credit_charge'] > 0) {
						$credit = intval($r['amount']*$MOD['credit_charge']);
						if($credit > 0) {
							credit_add($r['username'], $credit);
							credit_record($r['username'], $credit, 'system', $L['charge_reward'], $L['charge'].$r['amount'].$DT['money_unit']);
						}
					}
					if($r['reason']) {
						$url = get_reason_url($r);
						if($url) $charge_forward = $url;
					}
					if($bank == 'tenpay') {
						$show_url = $charge_forward ? $charge_forward : 'charge.php';
						if(strpos($show_url, '://') === false) $show_url = $MOD['linkurl'].$show_url;
						$resHandler->doShow($show_url);
					}
				} else if($charge_status == 2) {
					$db->query("UPDATE {$DT_PRE}finance_charge SET status=1,receivetime='$DT_TIME',editor='$editor',note='$note' WHERE itemid=$charge_orderid");
				}
			} else if($r['status'] == 1) {
				$charge_status = 2;		
				$charge_errcode = $L['charge_msg_order_fail'].$charge_orderid;
			} else if($r['status'] == 2) {
				$charge_status = 2;		
				$charge_errcode = $L['charge_msg_order_cancel'].$charge_orderid;
			} else {
				if($DT_TIME - $r['receivetime'] < 600) {
					if($r['reason']) {
						$url = get_reason_url($r);
						if($url) $charge_forward = $url;
					}
					$charge_status = 1;
				} else {
					dheader('?action=record');
				}
			}
		} else {
			$charge_status = 2;		
			$charge_errcode = $L['charge_msg_not_order'];
		}
		if($charge_forward) dheader($charge_forward);
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'record') {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = 'index.php';
	} else {
		$back_link = '?action=record';
	}
}
include template('charge', $module);
?>