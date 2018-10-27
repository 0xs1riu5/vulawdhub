<?php
defined('IN_DESTOON') or exit('Access Denied');
$partner = trim($PAY[$bank]['partnerid']);			//合作伙伴ID
$security_code = trim($PAY[$bank]['keycode']);		//安全检验码
$seller_email = trim($PAY[$bank]['email']);	//卖家邮箱
$_input_charset = DT_CHARSET;		//字符编码格式  目前支持 GBK 或 utf-8
$service_type = 'create_direct_pay_by_user';//交易类型，必填实物交易＝trade_create_by_buyer（需要填写物流） 虚拟物品交易＝create_digital_goods_trade_p
$sign_type = 'MD5';				//加密方式  系统默认(不要修改)
$transport= 'http';			//访问模式,你可以根据自己的服务器是否支持ssl访问而选择http以及https访问模式(系统默认,不要修改)
$notify_url = DT_PATH.'api/pay/'.$bank.'/'.($PAY[$bank]['notify'] ? $PAY[$bank]['notify'] : 'notify.php');	// 异步返回地址
$return_url = $receive_url;		//同步返回地址
$show_url   = DT_PATH;		//你网站商品的展示地址,可以为空
?>