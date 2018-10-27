<?php
defined('IN_DESTOON') or exit('Access Denied');
//---------------------------------------------------------
//财付通即时到帐支付请求示例，商户按照此文档进行开发即可
//---------------------------------------------------------
require_once DT_ROOT.'/api/pay/'.$bank.'/RequestHandler.class.php';
require_once DT_ROOT.'/api/pay/'.$bank.'/config.inc.php';

//4位随机数
$randNum = rand(1000, 9999);

//订单号，此处用时间加随机数生成，商户根据自己情况调整，只要保持全局唯一就行
$out_trade_no = $orderid;

$desc = $charge_title ? $charge_title : '会员('.$_username.')充值(流水号:'.$orderid.')';

/* 创建支付请求对象 */
$reqHandler = new RequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

//----------------------------------------
//设置支付参数 
//----------------------------------------
$reqHandler->setParameter("partner", $partner);
$reqHandler->setParameter("out_trade_no", $out_trade_no);
$reqHandler->setParameter("total_fee", $charge*100);  //总金额
$reqHandler->setParameter("return_url",  $return_url);
$reqHandler->setParameter("notify_url", $notify_url);
$reqHandler->setParameter("body", $desc);
$reqHandler->setParameter("bank_type", "DEFAULT");  	  //银行类型，默认为财付通
//用户ip
$reqHandler->setParameter("spbill_create_ip", $DT_IP);//客户端IP
$reqHandler->setParameter("fee_type", "1");               //币种
$reqHandler->setParameter("subject", $desc);          //商品名称，（中介交易时必填）

//系统可选参数
$reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
$reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
$reqHandler->setParameter("input_charset", DT_CHARSET);   	  //字符集
$reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号

//业务可选参数
$reqHandler->setParameter("attach", "");             	  //附件数据，原样返回就可以了
$reqHandler->setParameter("product_fee", "");        	  //商品费用
$reqHandler->setParameter("transport_fee", "0");      	  //物流费用
$reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
$reqHandler->setParameter("time_expire", "");             //订单失效时间
$reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
$reqHandler->setParameter("goods_tag", "");               //商品标记
$reqHandler->setParameter("trade_mode","1");              //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
$reqHandler->setParameter("transport_desc","");              //物流说明
$reqHandler->setParameter("trans_type","1");              //交易类型
$reqHandler->setParameter("agentid","");                  //平台ID
$reqHandler->setParameter("agent_type","");               //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
$reqHandler->setParameter("seller_id","");                //卖家的商户号



//请求的URL
$reqUrl = $reqHandler->getRequestURL();

//获取debug信息,建议把请求和debug信息写入日志，方便定位问题
/**/
$debugInfo = $reqHandler->getDebugInfo();
//echo "<br/>" . $reqUrl . "<br/>";
//echo "<br/>" . $debugInfo . "<br/>";


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo DT_CHARSET;?>">
<title>正在跳转到<?php echo $PAY[$bank]['name'];?>在线支付平台...</title>
</head>
<body onload="document.getElementById('pay').submit();">
<form action="<?php echo $reqHandler->getGateUrl();?>" method="post"  id="pay">
<?php
$params = $reqHandler->getAllParameters();
foreach($params as $k => $v) {
	echo '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
}
?>
</form>
</body>
</html>