<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/api/pay/'.$bank.'/config.inc.php';
require DT_ROOT.'/api/pay/'.$bank.'/submit.class.php';
$out_trade_no = $orderid;
$subject = $charge_title ? $charge_title : '会员('.$_username.')充值(流水号:'.$orderid.')';
$total_fee = $charge;
$show_url = $EXT['mobile_url'];
$body = '';
$parameter = array(
		"service"       => $alipay_config['service'],
		"partner"       => $alipay_config['partner'],
		"seller_id"  => $alipay_config['seller_id'],
		"payment_type"	=> $alipay_config['payment_type'],
		"notify_url"	=> $alipay_config['notify_url'],
		"return_url"	=> $alipay_config['return_url'],
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset'])),
		"out_trade_no"	=> $out_trade_no,
		"subject"	=> $subject,
		"total_fee"	=> $total_fee,
		"show_url"	=> $show_url,
		"app_pay"	=> "Y",
		"body"	=> $body,
		
);
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "GO");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>Loading...</title>
</head>
<body>
<?php echo $html_text;?>
</body>
</html>