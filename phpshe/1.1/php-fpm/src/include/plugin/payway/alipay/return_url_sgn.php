<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */
include('../../../../common.php');
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
//验证成功
if ($verify_result) {
	//商户订单号
	$out_trade_no = $_GET['out_trade_no'];
	//支付宝交易号
	$trade_no = $_GET['trade_no'];

	$info = $db->pe_select('order', array('order_id'=>$out_trade_no));
    if ($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
    	//担保交易
		if ($info['order_state'] == 'notpay') {
			$order['order_outid'] = $trade_no;
			$order['order_payway'] = 'alipay_db';
			$order['order_state'] = 'paid';
			$order['order_ptime'] = time();					
			$db->pe_update('order', array('order_id'=>$out_trade_no), $order);
		}
    }
	elseif ($_GET['trade_status'] == 'TRADE_FINISHED') {
		//即时到帐
		if ($info['order_state'] == 'notpay') {
			$order['order_outid'] = $trade_no;
			$order['order_payway'] = 'alipay_js';
			$order['order_state'] = 'paid';
			$order['order_ptime'] = time();					
			$db->pe_update('order', array('order_id'=>$out_trade_no), $order);
		}
    }
    else {
		echo "trade_status=".$_GET['trade_status'];
    }
	pe_goto("{$pe['host_root']}index.php?mod=user&act=order");
	//echo "验证成功<br />";
	//echo "trade_no=".$trade_no;
}
//验证失败
else {
    echo "验证失败";
}
?>
        <title>支付宝标准双接口</title>
	</head>
    <body>
    </body>
</html>