<?php
$_SERVER['REQUEST_URI'] = '';
$_DPOST = $_POST;
$_DGET = $_GET;
require '../../../common.inc.php';
$_POST = $_DPOST;
$_GET = $_DGET;
if(!$_POST && !$_GET) exit('error');
$bank = 'upay';
$PAY = cache_read('pay.php');
if(!$PAY[$bank]['enable']) exit('error');
if(strlen($PAY[$bank]['keycode']) < 6) exit('error');
$key = $PAY[$bank]['keycode'];
require DT_ROOT.'/api/pay/'.$bank.'/config.inc.php';
require DT_ROOT.'/api/pay/'.$bank.'/sdk.class.php';
#log_write($_POST, 'upn', 1);
if(AcpService::validate($_POST)) {
	if($_POST['respCode'] == '00' || $_POST['respCode'] == 'A6') {
		$orderid = intval(substr($_POST['orderId'], 7));
		$txnAmt = intval($_POST['txnAmt'])/100;		
		$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE itemid='$orderid'");
		if($r) {
			if($r['status'] == 0) {
				$charge_orderid = $r['itemid'];
				$charge_money = $r['amount'] + $r['fee'];
				$charge_amount = $r['amount'];
				$editor = 'N'.$bank;
				if($txnAmt == $charge_money) {
					require DT_ROOT.'/api/pay/success.inc.php';
					exit('ok');
				} else {
					$note = '充值金额不匹配S:'.$charge_money.'R:'.$txnAmt;
					$db->query("UPDATE {$DT_PRE}finance_charge SET status=1,receivetime='$DT_TIME',editor='$editor',note='$note' WHERE itemid=$charge_orderid");//支付失败
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
			exit('error');
		}
	}
}
exit('error');
?>