<?php
$partner = $data[0]['userid'];				//合作伙伴ID
$security_code 	= $data[0]['key'];		//安全检验码
$seller_email 	= $data[0]['email'];	//卖家邮箱
$_input_charset = 'GBK'; 				//字符编码格式  目前支持 GBK 或 utf-8
$sign_type = "MD5"; 					//加密方式  系统默认(不要修改)
$transport = "http";					//访问模式,你可以根据自己的服务器是否支持ssl访问而选择http以及https访问模式(系统默认,不要修改)
$notify_url = BLUE_ROOT."include/payment/alipay/notify_url.php";// 异步返回地址 需要填写完整的路径
$return_url = BLUE_ROOT."include/payment/alipay/return_url.php"; //同步返回地址  需要填写完整大额路径
$show_url = ""  						//你网站商品的展示地址,可以为空
?>