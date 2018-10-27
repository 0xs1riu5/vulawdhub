<?php
$_SERVER['REQUEST_URI'] = '';
require '../../../common.inc.php';
function wx_exit($type = '') {
	exit($type == 'ok' ? '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>' : '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA['.$type.']]></return_msg></xml>');
}
function make_sign($arr, $key) {
	ksort($arr);
	$str = '';
	foreach($arr as $k=>$v) {
		if($v) $str .= $k.'='.$v.'&';
	}
	$str .= 'key='.$key;
	return strtoupper(md5($str));
}
$xml = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : file_get_contents("php://input");
$xml or wx_exit();
$bank = 'weixin';
$PAY = cache_read('pay.php');
if(!$PAY[$bank]['enable']) wx_exit();
if(strlen($PAY[$bank]['keycode']) < 7) wx_exit();
if(strpos($xml, 'result_code') !== false && preg_match("/<out_trade_no>(.*)<\/out_trade_no>/", $xml, $par1) && preg_match("/<total_fee>(.*)<\/total_fee>/", $xml, $par2) && preg_match("/<nonce_str>(.*)<\/nonce_str>/", $xml, $par3)) {
	$_out_trade_no = strpos($par1[1], '[CDATA[') !== false ? substr($par1[1], 9, -3) : $par1[1];
	$_total_fee = strpos($par2[1], '[CDATA[') !== false ? substr($par2[1], 9, -3) : $par2[1];
	$_nonce_str = strpos($par3[1], '[CDATA[') !== false ? substr($par3[1], 9, -3) : $par3[1];
	if($_nonce_str != md5(md5($_out_trade_no.$PAY[$bank]['keycode'].$_total_fee))) wx_exit();
	if(function_exists('libxml_disable_entity_loader')) libxml_disable_entity_loader(true);
	$x = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	$x = (array)$x;
	$post = array();
	foreach($x as $k=>$v) {
		$post[$k] = $v;
	}
	unset($post['sign']);
	if($post['result_code'] == 'SUCCESS' && make_sign($post, $PAY[$bank]['keycode']) == $x['sign']) {
		$itemid = intval($post['out_trade_no']);
		$total_fee = $post['total_fee']/100;
		$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE itemid='$itemid'");
		if($r) {
			if($r['status'] == 3) wx_exit('ok');
			if($r['status'] == 0) {
				$charge_orderid = $r['itemid'];
				$charge_money = $r['amount'] + $r['fee'];
				$charge_amount = $r['amount'];
				$editor = 'N'.$bank;
				if($total_fee == $charge_money) {
					require DT_ROOT.'/api/pay/success.inc.php';
					wx_exit('ok');
				} else {
					$note = '充值金额不匹配S:'.$charge_money.'R:'.$total_fee;
					$db->query("UPDATE {$DT_PRE}finance_charge SET status=1,receivetime='$DT_TIME',editor='$editor',note='$note' WHERE itemid=$charge_orderid");//支付失败
				}
			}
		}
	}
}
wx_exit();
?>