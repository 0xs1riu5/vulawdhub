<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/api/pay/'.$bank.'/service.class.php';
require DT_ROOT.'/api/pay/'.$bank.'/config.inc.php';
$parameter = array(
	'service' => $service_type,	//交易类型，必填实物交易＝trade_create_by_buyer（需要填写物流） 虚拟物品交易＝create_digital_goods_trade_p
	'partner' =>$partner,					//合作商户号
	'return_url' =>$return_url,				//同步返回
	'notify_url' =>$notify_url,				//异步返回
	'_input_charset' => $_input_charset,	//字符集，默认为GBK
	'subject' => $DT['sitename'].'会员充值',	//商品名称，必填
	'body' => $charge_title ? $charge_title : '会员('.$_username.')充值(流水号:'.$orderid.')',      //商品描述，必填

	'out_trade_no'   => $orderid,     //商品外部交易号，必填（保证唯一性）
	'price'          => $charge,           //商品单价，必填（价格不能为0）
	'payment_type'   => '1',              //默认为1,不需要修改
	'quantity'       => '1',              //商品数量，必填
		
	'logistics_fee'      =>'0.00',        //物流配送费用
	'logistics_payment'  =>'SELLER_PAY',   //物流费用付款方式：SELLER_PAY(卖家支付)、BUYER_PAY(买家支付)、BUYER_PAY_AFTER_RECEIVE(货到付款)
	'logistics_type'     =>'EXPRESS',     //物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)

	'show_url'       => $show_url,        //商品相关网站
	'seller_email'   => $seller_email,     //卖家邮箱，必填 
	'buyer_email'    => $_email,	//买家邮箱 
);

//对URL组合
$alipay = new alipay_service($parameter, $security_code, $sign_type);
$URI = $alipay->create_url();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo DT_CHARSET;?>">
<title>正在跳转到<?php echo $PAY[$bank]['name'];?>在线支付平台...</title>
<meta http-equiv="refresh" content="0;url=<?php echo $URI;?>">
</head>
<body>
</body>
</html>