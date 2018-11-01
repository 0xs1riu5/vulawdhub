<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>支付宝标准双接口接口</title>
</head>
<?php
/* *
 * 功能：标准双接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */
include("{$pe['path_root']}include/plugin/payway/alipay/alipay.config.php");
include("{$pe['path_root']}include/plugin/payway/alipay/lib/alipay_submit.class.php");
//构造要请求的参数数组，无需改动
switch ($payway['alipay_class']) {
	case 'alipay_sgn':
		//异步通知页面路径，需http://格式的完整路径，不能加?id=123这类自定义参数
		$notify_url = "{$pe['host_root']}include/plugin/payway/alipay/notify_url_sgn.php";
		//同步通知页面路径，需http://格式的完整路径，不能加?id=123这类自定义参数
		$return_url = "{$pe['host_root']}include/plugin/payway/alipay/return_url_sgn.php";
		$parameter = array(
				"service" => "trade_create_by_buyer",
				"partner" => trim($alipay_config['partner']),
				"payment_type"	=> $payment_type,
				"notify_url"	=> $notify_url,
				"return_url"	=> $return_url,
				"seller_email"	=> $seller_email,
				"out_trade_no"	=> $out_trade_no,
				"subject"	=> $subject,
				"price"	=> $price,
				"quantity"	=> $quantity,
				"logistics_fee"	=> $logistics_fee,
				"logistics_type"	=> $logistics_type,
				"logistics_payment"	=> $logistics_payment,
				"body"	=> $body,
				"show_url"	=> $show_url,
				"receive_name"	=> $receive_name,
				"receive_address"	=> $receive_address,
				"receive_zip"	=> $receive_zip,
				"receive_phone"	=> $receive_phone,
				"receive_mobile"	=> $receive_mobile,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
	break;
	case 'alipay_js':
		//异步通知页面路径，需http://格式的完整路径，不能加?id=123这类自定义参数
		$notify_url = "{$pe['host_root']}include/plugin/payway/alipay/notify_url_js.php";
		//同步通知页面路径，需http://格式的完整路径，不能加?id=123这类自定义参数
		$return_url = "{$pe['host_root']}include/plugin/payway/alipay/return_url_js.php";
		$parameter = array(
				"service" => "create_direct_pay_by_user",
				"partner" => trim($alipay_config['partner']),
				"payment_type"	=> $payment_type,
				"notify_url"	=> $notify_url,
				"return_url"	=> $return_url,
				"seller_email"	=> $seller_email,
				"out_trade_no"	=> $out_trade_no,
				"subject"	=> $subject,
				"total_fee"	=> $total_fee,
				"body"	=> $body,
				"show_url"	=> $show_url,
				"anti_phishing_key"	=> $anti_phishing_key,
				"exter_invoke_ip"	=> $exter_invoke_ip,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
	break;
	case 'alipay_db':
		//异步通知页面路径，需http://格式的完整路径，不能加?id=123这类自定义参数
		$notify_url = "{$pe['host_root']}include/plugin/payway/alipay/notify_url_db.php";
		//同步通知页面路径，需http://格式的完整路径，不能加?id=123这类自定义参数
		$return_url = "{$pe['host_root']}include/plugin/payway/alipay/return_url_db.php";
		$parameter = array(
				"service" => "create_partner_trade_by_buyer",
				"partner" => trim($alipay_config['partner']),
				"payment_type"	=> $payment_type,
				"notify_url"	=> $notify_url,
				"return_url"	=> $return_url,
				"seller_email"	=> $seller_email,
				"out_trade_no"	=> $out_trade_no,
				"subject"	=> $subject,
				"price"	=> $price,
				"quantity"	=> $quantity,
				"logistics_fee"	=> $logistics_fee,
				"logistics_type"	=> $logistics_type,
				"logistics_payment"	=> $logistics_payment,
				"body"	=> $body,
				"show_url"	=> $show_url,
				"receive_name"	=> $receive_name,
				"receive_address"	=> $receive_address,
				"receive_zip"	=> $receive_zip,
				"receive_phone"	=> $receive_phone,
				"receive_mobile"	=> $receive_mobile,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
	break;
}
//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
echo $html_text;
?>
</body>
</html>