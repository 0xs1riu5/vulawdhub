<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/api/pay/'.$bank.'/config.inc.php';
require DT_ROOT.'/api/pay/'.$bank.'/sdk.class.php';
#log_write($_POST, 'upr', 1);
if(AcpService::validate($_POST)) {
	if($_POST['respCode'] == '00' || $_POST['respCode'] == 'A6') {
		$orderid = intval(substr($_POST['orderId'], 7));
		$txnAmt = intval($_POST['txnAmt'])/100;
		if($orderid != $charge_orderid) {
			$charge_status = 2;
			$charge_errcode = '订单号不匹配';
			$note = $charge_errcode.'S:'.$charge_orderid.'R:'.$orderid;
			log_write($note, 'r'.$bank);
		} else if($txnAmt != $charge_money) {
			$charge_status = 2;
			$charge_errcode = '充值金额不匹配';
			$note = $charge_errcode.'S:'.$charge_money.'R:'.$txnAmt;
			log_write($note, 'r'.$bank);
		} else {
			$charge_status = 1;
		}
	}
}
?>