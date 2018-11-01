<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 
 * 如何判断该笔交易是通过即时到帐方式付款还是通过担保交易方式付款？
 * 
 * 担保交易的交易状态变化顺序是：等待买家付款→买家已付款，等待卖家发货→卖家已发货，等待买家收货→买家已收货，交易完成
 * 即时到帐的交易状态变化顺序是：等待买家付款→交易完成
 * 
 * 每当收到支付宝发来通知时，就可以获取到这笔交易的交易状态，并且商户需要利用商户订单号查询商户网站的订单数据，
 * 得到这笔订单在商户网站中的状态是什么，把商户网站中的订单状态与从支付宝通知中获取到的状态来做对比。
 * 如果商户网站中目前的状态是等待买家付款，而从支付宝通知获取来的状态是买家已付款，等待卖家发货，那么这笔交易买家是用担保交易方式付款的
 * 如果商户网站中目前的状态是等待买家付款，而从支付宝通知获取来的状态是交易完成，那么这笔交易买家是用即时到帐方式付款的
 */
include('../../../../common.php');
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();
//验证成功
if ($verify_result) {	
	//商户订单号
	$out_trade_no = $_POST['out_trade_no'];
	//支付宝交易号
	$trade_no = $_POST['trade_no'];

	$info = $db->pe_select('order', array('order_id'=>$out_trade_no));
	//该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
	if ($_POST['trade_status'] == 'WAIT_BUYER_PAY') {	
        echo "success";		//请不要修改或删除
    }
	//该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货
	elseif ($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
		if ($info['order_state'] == 'notpay') {
			$order['order_outid'] = $trade_no;
			$order['order_payway'] = 'alipay_db';
			$order['order_state'] = 'paid';
			$order['order_ptime'] = time();		
			$db->pe_update('order', array('order_id'=>$out_trade_no), $order);
		}
        echo "success";		//请不要修改或删除
    }
	//该判断表示卖家已经发了货，但买家还没有做确认收货的操作
	elseif ($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
		if ($info['order_state'] == 'paid') {
			$order['order_state'] = 'send';
			$order['order_stime'] = time();					
			$db->pe_update('order', array('order_id'=>$out_trade_no), $order);
		}	
        echo "success";		//请不要修改或删除
    }
	//该判断表示买家已经确认收货，这笔交易完成
	elseif ($_POST['trade_status'] == 'TRADE_FINISHED') {
		if ($info['order_state'] == 'notpay') {
			$order['order_outid'] = $trade_no;
			$order['order_payway'] = 'alipay_js';
			$order['order_state'] = 'paid';
			$order['order_ptime'] = time();					
			$db->pe_update('order', array('order_id'=>$out_trade_no), $order);
		}
		elseif ($info['order_state'] == 'send') {
			$order['order_state'] = 'success';					
			$db->pe_update('order', array('order_id'=>$out_trade_no), $order);
		}
        echo "success";		//请不要修改或删除
    }
	//其他状态判断
    else {
        echo "success";
    }
}
//验证失败
else {
    echo "fail";
}
?>