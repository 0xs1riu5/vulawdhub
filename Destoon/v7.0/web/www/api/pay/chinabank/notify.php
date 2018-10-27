<?php
$_SERVER['REQUEST_URI'] = '';
$_DPOST = $_POST;
$_DGET = $_GET;
require '../../../common.inc.php';
$_POST = $_DPOST;
$_GET = $_DGET;
if(!$_POST && !$_GET) exit('error');
$bank = 'chinabank';
$PAY = cache_read('pay.php');
if(!$PAY[$bank]['enable']) exit('error');
if(strlen($PAY[$bank]['keycode']) < 7) exit('error');
$key = $PAY[$bank]['keycode'];
$v_oid     =trim($_POST['v_oid']);
$v_pmode   =trim($_POST['v_pmode']);
$v_pstatus =trim($_POST['v_pstatus']);
$v_pstring =trim($_POST['v_pstring']);
$v_amount  =trim($_POST['v_amount']);
$v_moneytype  =trim($_POST['v_moneytype']);
$remark1   =trim($_POST['remark1' ]);
$remark2   =trim($_POST['remark2' ]);
$v_md5str  =trim($_POST['v_md5str' ]);
                           
$md5string = strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));
if($v_md5str == $md5string) {	
   if($v_pstatus == "20") {
		$v_oid = intval($v_oid);
		$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE itemid='$v_oid'");
		if($r) {
			if($r['status'] == 0) {
				$charge_orderid = $r['itemid'];
				$charge_money = $r['amount'] + $r['fee'];
				$charge_amount = $r['amount'];
				$editor = 'N'.$bank;
				if($v_amount == $charge_money) {
					require DT_ROOT.'/api/pay/success.inc.php';
					exit('ok');
				} else {
					$note = '充值金额不匹配S:'.$charge_money.'R:'.$v_amount;
					$db->query("UPDATE {$DT_PRE}finance_charge SET status=1,receivetime='$DT_TIME',editor='$editor',note='$note' WHERE itemid=$charge_orderid");//支付失败
					log_write($note, 'nchinabank');
					exit('error');
				}
			} else if($r['status'] == 1) {
				exit('error');
			} else if($r['status'] == 2) {
				exit('error');
			} else {
				exit('ok');
			}
		} else {
			log_write('通知订单号不存在R:'.$v_oid, 'nchinabank');
			exit('error');
		}
	}
}
exit('error');
?>