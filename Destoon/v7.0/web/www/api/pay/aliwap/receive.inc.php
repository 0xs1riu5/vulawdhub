<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/api/pay/'.$bank.'/config.inc.php';
require DT_ROOT.'/api/pay/'.$bank.'/notify.class.php';
/*
	*功能：付完款后跳转的页面
	*版本：2.0
	*日期：2008-08-01
	*作者：支付宝公司销售部技术支持团队
	*联系：0571-26888888
	*版权：支付宝公司
*/
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	if($out_trade_no != $charge_orderid) {
		$charge_status = 2;
		$charge_errcode = '订单号不匹配';
		$note = $charge_errcode.'S:'.$charge_orderid.'R:'.$out_trade_no;
		log_result($note);
	} else if($total_fee != $charge_money) {
		$charge_status = 2;
		$charge_errcode = '充值金额不匹配';
		$note = $charge_errcode.'S:'.$charge_money.'R:'.$total_fee;
		log_result($note);
	} else {
		$charge_status = 1;
	}
	//这里放入你自定义代码,比如根据不同的trade_status进行不同操作
	//log_result("verify_success"); 
}
function log_result($word) {
	log_write($word, 'raliwap');
}
?>