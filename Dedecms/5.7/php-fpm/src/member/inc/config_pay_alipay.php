<?php
/**
 * 支付页面
 * 
 * @version        $Id: config_pay_alipay.php 1 13:52 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(DEDEMEMBER."/paycenter/alipay/alipay_config.php");
require_once(DEDEMEMBER."/paycenter/alipay/alipay_service.php");
if($payment_exp[2] < 0) $payment_exp[2] = 0;
$piice_ex = $price*$payment_exp[2];
$parameter = array(
"service" => "trade_create_by_buyer", //交易类型，必填实物交易＝trade_create_by_buyer（需要填写物流）
"partner" => $partner,                                                //合作商户号
"return_url" => $return_url,  //同步返回
"notify_url" => $notify_url,  //异步返回
"_input_charset" => $_input_charset,          //字符集，默认为GBK
"subject" => $ptype,                          //商品名称，必填
"body" => $pname,                             //商品描述，必填
"out_trade_no" => $buyid,                     //商品外部交易号，必填,每次测试都须修改
"logistics_fee" => '0.00',                    //物流配送费用
"logistics_payment"=>'BUYER_PAY',             // 物流配送费用付款方式：SELLER_PAY(卖家支付)、BUYER_PAY(买家支付)、BUYER_PAY_AFTER_RECEIVE(货到付款)
"logistics_type"=>'EXPRESS',                  // 物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)

"price" => sprintf("%01.2f", $price),         //商品单价，必填
"payment_type"=>"1",                          // 默认为1,不需要修改
"quantity" => "1",                            //商品数量，必填
"show_url" => $show_url,                                //商品相关网站
"seller_email" => $seller_email               //卖家邮箱，必填
);
$alipay = new alipay_service($parameter,$security_code,$sign_type);
$link    = $alipay->create_url();

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