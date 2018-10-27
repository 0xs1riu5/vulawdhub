<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/api/pay/'.$bank.'/netpayclient_config.php';
//加载 netpayclient 组件
require DT_ROOT.'/api/pay/'.$bank.'/netpayclient.php';
//导入私钥文件, 返回值即为您的商户号，长度15位
$merid = buildKey(PRI_KEY);
$merid or exit('导入私钥文件失败！');

//生成订单号，定长16位，任意数字组合，一天内不允许重复，本例采用当前时间戳，必填
$ordid = "00" . date('YmdHis');
//订单金额，定长12位，以分为单位，不足左补0，必填
$transamt = padstr($charge*100,12);
//货币代码，3位，境内商户固定为156，表示人民币，必填
$curyid = "156";
//订单日期，本例采用当前日期，必填
$transdate = date('Ymd');
//交易类型，0001 表示支付交易，0002 表示退款交易
$transtype = "0001";
//接口版本号，境内支付为 20070129，必填
$version = "20070129";
//页面返回地址(您服务器上可访问的URL)，最长80位，当用户完成支付后，银行页面会自动跳转到该页面，并POST订单结果信息，可选
$pagereturl = $receive_url;
//后台返回地址(您服务器上可访问的URL)，最长80位，当用户完成支付后，我方服务器会POST订单结果信息到该页面，必填
$bgreturl = DT_PATH.'api/pay/'.$bank.'/notify.php';

/************************
页面返回地址和后台返回地址的区别：
后台返回从我方服务器发出，不受用户操作和浏览器的影响，从而保证交易结果的送达。
************************/

//支付网关号，4位，上线时建议留空，以跳转到银行列表页面由用户自由选择，本示例选用0001农商行网关便于测试，可选
$gateid = "";//"0001";
//备注，最长60位，交易成功后会原样返回，可用于额外的订单跟踪等，可选
$priv1 = $orderid;//设置为订单ID

//按次序组合订单信息为待签名串
$plain = $merid . $ordid . $transamt . $curyid . $transdate . $transtype . $priv1;
//生成签名值，必填
$chkvalue = sign($plain);
$chkvalue or message('签名失败！');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo DT_CHARSET;?>">
<title>正在跳转到<?php echo $PAY[$bank]['name'];?>在线支付平台...</title>
</head>
<body onload="document.getElementById('pay').submit();">
<form method="post" action="<?php echo REQ_URL_PAY;?>" id="pay">
<input type="hidden" name="MerId"		value="<?php echo $merid;?>">
<input type="hidden" name="Version"     value="<?php echo $version;?>">
<input type="hidden" name="OrdId"		value="<?php echo $ordid;?>">
<input type="hidden" name="TransAmt"    value="<?php echo $transamt;?>">
<input type="hidden" name="CuryId"		value="<?php echo $curyid;?>">
<input type="hidden" name="TransDate"   value="<?php echo $transdate;?>">
<input type="hidden" name="TransType"   value="<?php echo $transtype;?>">
<input type="hidden" name="BgRetUrl"    value="<?php echo $bgreturl;?>">
<input type="hidden" name="PageRetUrl"  value="<?php echo $pagereturl;?>">
<input type="hidden" name="GateId"      value="<?php echo $gateid;?>">
<input type="hidden" name="Priv1"		value="<?php echo $priv1;?>">
<input type="hidden" name="ChkValue"    value="<?php echo $chkvalue;?>">
</form>
</body>
</html>