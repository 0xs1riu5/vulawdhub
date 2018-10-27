<?php
require '../../../common.inc.php';
$charge_title = '';
$auth = isset($auth) ? decrypt($auth, DT_KEY.'JSPAY') : '';
$auth or dheader($MODULE[2]['mobile'].'charge.php?action=record');
$t = explode('|', $auth);
$itemid = $orderid = intval($t[0]);
$openid = $t[3];
($itemid && $t[2] == $DT_IP && is_openid($openid)) or dheader($MODULE[2]['mobile'].'charge.php?action=record');
$charge_title = $t[1];
$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE itemid=$itemid");
if(!$r || !$_username || $r['username'] != $_username || $r['status'] != 0 || $r['bank'] != 'weixin') dheader($MODULE[2]['mobile'].'charge.php?action=record');
$bank = 'weixin';
$PAY = cache_read('pay.php');
$PAY[$bank]['enable'] or dheader($MODULE[2]['mobile'].'charge.php?action=record');
function make_sign($arr, $key) {
	ksort($arr);
	$str = '';
	foreach($arr as $k=>$v) {
		if($v) $str .= $k.'='.$v.'&';
	}
	$str .= 'key='.$key;
	return strtoupper(md5($str));
}
function make_xml($arr) {
	$str = '<xml>';
	foreach($arr as $k=>$v) {
		if(is_numeric($v)) {
			$str .= '<'.$k.'>'.$v.'</'.$k.'>';
		} else {
			$str .= '<'.$k.'><![CDATA['.$v.']]></'.$k.'>';
		}
	}
	$str .= '</xml>';
	return $str;
}
$charge = $r['amount'] + $r['fee'];
$total_fee = $charge*100;
$post = array();
$post['appid'] = $PAY[$bank]['appid'];
$post['mch_id'] = $PAY[$bank]['partnerid'];
$post['nonce_str'] = md5(md5($itemid.$PAY[$bank]['keycode'].$total_fee));
$post['body'] = $charge_title ? $charge_title : '会员('.$_username.')充值(流水号:'.$orderid.')';
$post['out_trade_no'] = $itemid;
$post['total_fee'] = $total_fee;
$post['spbill_create_ip'] = $DT_IP;
$post['notify_url'] = DT_PATH.'api/pay/'.$bank.'/'.($PAY[$bank]['notify'] ? $PAY[$bank]['notify'] : 'notify.php');
$post['trade_type'] = 'JSAPI';
$post['product_id'] = $itemid;
$post['openid'] = $openid;
$post['sign'] = make_sign($post, $PAY[$bank]['keycode']);
$rec = dcurl('https://api.mch.weixin.qq.com/pay/unifiedorder', make_xml($post));
if(strpos($rec, 'prepay_id') !== false) {
	if(function_exists('libxml_disable_entity_loader')) libxml_disable_entity_loader(true);
	$x = simplexml_load_string($rec, 'SimpleXMLElement', LIBXML_NOCDATA);
} else {
	//发起失败，自动切换到二维码
	dheader(DT_PATH.'api/pay/weixin/qrcode.php?auth='.encrypt($orderid.'|'.$charge_title.'|'.$DT_IP, DT_KEY.'QRPAY'));
	if(strpos($rec, 'return_msg') !== false) {
		if(function_exists('libxml_disable_entity_loader')) libxml_disable_entity_loader(true);
		$x = simplexml_load_string($rec, 'SimpleXMLElement', LIBXML_NOCDATA);
		dalert($x->return_msg, $MODULE[2]['mobile'].'charge.php?action=record');
	} else {
		dalert('Can Not Connect weixin', $MODULE[2]['mobile'].'charge.php?action=record');
	}
}
$arr = array();
$arr['appId'] = $PAY[$bank]['appid'];
$arr['timeStamp'] = $DT_TIME;
$arr['nonceStr'] = $post['nonce_str'];
$arr['package'] = 'prepay_id='.$x->prepay_id;
$arr['signType'] = 'MD5';
$arr['paySign'] = make_sign($arr, $PAY[$bank]['keycode']);
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=<?php echo DT_CHARSET;?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>微信支付<?php echo $DT['seo_delimiter'];?><?php echo $DT['sitename'];?></title>
	<style type="text/css">
	* {word-break:break-all;font-family:"Segoe UI","Lucida Grande",Helvetica,Arial,Verdana,"Microsoft YaHei";}
	body {margin:0;font-size:14px;color:#333333;background:#EFEFF4;-webkit-user-select:none;}
	</style>
</head>
<body>
<div style="width:100%;text-align:center;">
	<div style="line-height:24px;font-weight:bold;margin-top:30px;font-size:20px;">微信支付</div>
	<div style="line-height:24px;font-weight:bold;"><span style="font-size:18px;"><?php echo $DT['money_sign'];?></span><span style="font-size:22px;"><?php echo str_replace('.', '</span><span style="font-size:16px;">.', strpos($charge, '.') === false ? $charge.'.00' : $charge);?></span></div>
	<div onclick="CallPay();" style="background:#04BE02;color:#FFFFFF;font-size:18px;width:auto;line-height:40px;border:#04B102 1px solid;border-radius:6px;text-align:center;margin:32px 16px;">立即支付</div>
	<div style="padding:0 16px;font-size:16px;margin-top:20px;">
	<a href="<?php echo $MODULE[2]['mobile'];?>charge.php" style="color:#2E7DC6;text-decoration:none;">已经支付</a>
	&nbsp;&nbsp;
	<a href="<?php echo $MODULE[2]['mobile'];?>charge.php?action=record" style="color:#2E7DC6;text-decoration:none;">取消支付</a>
	</div>
</div>
<script type="text/javascript">
function onBridgeReady() {
	WeixinJSBridge.invoke(			
	   'getBrandWCPayRequest', {
		   "appId":"<?php echo $arr['appId'];?>",
		   "timeStamp":"<?php echo $arr['timeStamp'];?>",
		   "nonceStr":"<?php echo $arr['nonceStr'];?>",
		   "package":"<?php echo $arr['package'];?>",
		   "signType":"<?php echo $arr['signType'];?>",
		   "paySign":"<?php echo $arr['paySign'];?>"
	   },
		function(res){
			if(res.err_msg == "get_brand_wcpay_request:ok") {window.location.href = '<?php echo $MODULE[2]['mobile'];?>charge.php';}
		}
	);
}
function CallPay() {
	if(typeof WeixinJSBridge == "undefined") {
		if(document.addEventListener) {
			document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
		} else if(document.attachEvent){
			document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
			document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
		}
	} else {
		onBridgeReady();
	}
}
CallPay();
</script>
</body>
</html>