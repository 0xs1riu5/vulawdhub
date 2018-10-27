<?php
defined('IN_DESTOON') or exit('Access Denied');
$db->query("UPDATE {$DT_PRE}finance_charge SET status=3,money=$charge_money,receivetime='$DT_TIME',editor='$editor' WHERE itemid=$charge_orderid");
include load('member.lang');
require DT_ROOT.'/include/module.func.php';
money_add($r['username'], $r['amount']);
money_record($r['username'], $r['amount'], $PAY[$bank]['name'], 'system', $L['charge_online'], $L['charge_id'].':'.$charge_orderid);
$MOD = cache_read('module-2.php');
if($MOD['credit_charge'] > 0) {
	$credit = intval($r['amount']*$MOD['credit_charge']);
	if($credit > 0) {
		credit_add($r['username'], $credit);
		credit_record($r['username'], $credit, 'system', $L['charge_reward'], $L['charge'].$r['amount'].$DT['money_unit']);
	}
}
//Pay
if($r['reason']) {
	include load('order.lang');
	$_username = $r['username'];
	$timenow = timetodate($DT_TIME, 3);
	$memberurl = $MODULE[2]['linkurl'];
	$myurl = userurl($_username);
	$arr = explode('|', $r['reason']);
	if($arr[0] == 'trade' || $arr[0] == 'trades') {
		foreach(explode(',', $arr[1]) as $id) {
			$itemid = intval($id);
			if($itemid < 1) continue;
			$table = $DT_PRE.'order';
			$td = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
			if($td && $td['buyer'] == $r['username'] && $td['status'] == 1) {
				$mallid = $td['mallid'];
				$m = $db->get_one("SELECT money FROM {$DT_PRE}member WHERE username='$r[username]'");
				$money = $td['amount'] + $td['fee'];
				if($m['money'] >= $money) {
					money_add($_username, -$money);
					money_record($_username, -$money, $L['in_site'], 'system', $L['trade_pay_order_title'], $L['trade_order_id'].$itemid);
					$db->query("UPDATE {$table} SET status=2,updatetime=$DT_TIME WHERE itemid=$itemid");
					$touser = $td['seller'];
					$title = lang($L['trade_message_t2'], array($itemid));
					$url = $memberurl.'trade.php?itemid='.$itemid;
					$content = lang($L['trade_message_c2'], array($myurl, $_username, $timenow, $url));
					$content = ob_template('messager', 'mail');
					send_message($touser, $title, $content);			
					//send sms
					if($DT['sms'] && $_sms && $touser && isset($sendsms)) {
						$touser = userinfo($touser);
						if($touser['mobile']) {
							$message = lang('sms->ord_pay', array($itemid, $money));
							$message = strip_sms($message);
							$word = word_count($message);
							$sms_num = ceil($word/$DT['sms_len']);
							if($sms_num <= $_sms) {
								$sms_code = send_sms($touser['mobile'], $message, $word);
								if(strpos($sms_code, $DT['sms_ok']) !== false) {
									$tmp = explode('/', $sms_code);
									if(is_numeric($tmp[1])) $sms_num = $tmp[1];
									if($sms_num) sms_add($_username, -$sms_num);
									if($sms_num) sms_record($_username, -$sms_num, $_username, $L['trade_sms_pay'], $itemid);
								}
							}
						}
					}
					//send sms
					if($MODULE[$td['mid']]['module'] == 'mall') {
						$db->query("UPDATE ".get_table($td['mid'])." SET orders=orders+1,sales=sales+$td[number],amount=amount-$td[number] WHERE itemid=$mallid");
					} else {
						$db->query("UPDATE ".get_table($td['mid'])." SET amount=amount-$td[number] WHERE itemid=$mallid");
					}
				}
			}
		}
	} else if($arr[0] == 'group') {
		$itemid = intval($arr[1]);
		$mid = intval($arr[2]);
		$table = $DT_PRE.'group_order_'.$mid;
		$td = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
		if($td && $td['buyer'] == $_username && $td['status'] == 6) {
			$m = $db->get_one("SELECT money FROM {$DT_PRE}member WHERE username='$r[username]'");
			$money = $td['amount'];
			if($m['money'] >= $money) {
				money_add($_username, -$money);
				money_record($_username, -$money, $L['in_site'], 'system', $L['group_order_credit'], $L['trade_order_id'].$itemid);
				$password = $td['logistic'] ? '' : random(6, '0123456789');
				$db->query("UPDATE {$table} SET status=0,password='$password',updatetime=$DT_TIME WHERE itemid=$itemid");
				if($password) {
					//send sms
					if($DT['sms']) {
						$message = lang('sms->ord_group', array($td['title'], $itemid, $password));
						$message = strip_sms($message);
						send_sms($td['buyer_mobile'], $message);
					}
					//send sms
				}
				$db->query("UPDATE ".get_table($mid)." SET orders=orders+1,sales=sales+$td[number] WHERE itemid=$td[gid]");
			}
		}
	}
}
?>