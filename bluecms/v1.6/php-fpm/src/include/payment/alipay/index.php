<?php
require_once(BLUE_ROOT."include/payment/alipay/alipay_config.php");
require_once(BLUE_ROOT."include/payment/alipay/alipay_service.php");
//if($data[0]['fee'] < 0) $data[0]['fee'] = 0;
$parameter = array(
"service" 					=> "trade_create_by_buyer", 	//交易类型，必填实物交易＝trade_create_by_buyer（需要填写物流）
"partner" 					=> $partner,	 				//合作商户号
"return_url" 				=> $return_url,  				//同步返回
"notify_url" 				=> $notify_url,  				//异步返回
"_input_charset" 			=> $_input_charset,         	//字符集，默认为GBK
"subject" 					=> $name,                   	//商品名称，必填
"body" 						=> $name,                   	//商品描述，必填
"out_trade_no" 				=> $id,                         //商品外部交易号，必填,每次测试都须修改
"price" 					=> sprintf("%01.2f", $price),   //商品单价，必填
"payment_type"				=>"1",                          // 默认为1,不需要修改
"quantity" 					=> "1",                         //商品数量，必填
"show_url" 					=> $show_url,            		//商品相关网站
"seller_email" 				=> $seller_email                //卖家邮箱，必填
);
$alipay = new alipay_service($parameter,$security_code,$sign_type);
$link	= $alipay->create_url();

echo '<html>
<head>
	<title>转到支付宝支付页面</title>
</head>
<body onload="document.alipay.submit();">
	<form name="alipay" action="'.$link.'" method="post">
	</form>
</body>
</html>';
exit;