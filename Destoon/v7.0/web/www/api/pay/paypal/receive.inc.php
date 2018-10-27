<?php
defined('IN_DESTOON') or exit('Access Denied');
if($PAY[$bank]['keycode'] && isset($_GET['tx'])) {//PDT
	$tx_token = $_GET['tx'];
	$auth_token = $PAY[$bank]['keycode'];
	//形成验证字符串
	$req = "cmd=_notify-synch&tx=$tx_token&at=$auth_token";
	//将交易流水号及身份标记返回 PayPal 验证
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);
	#$fp = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30);
	if(!$fp) {
		//HTTP ERROR
		$charge_status = 2;
		$charge_errcode = 'PayPal HTTP ERROR';
	} else {
		fputs($fp, $header.$req);
		//获取返回数据
		$res = '';
		$headerdone = false;
		while(!feof($fp)) {
			$line = fgets($fp, 1024);
			if(strcmp($line, "\r\n") == 0) {
				//获取头
				$headerdone = true;
			} else if($headerdone){
				//获取主体内容
				$res .= $line;
			}
		}
		//解析获取内容
		$lines = explode("\n", $res);
		$keyarray = array();
		if(strcmp($lines[0], "SUCCESS") == 0) {
			for($i = 1; $i < count($lines); $i++) {
				list($key, $val) = explode("=", $lines[$i]);
				$keyarray[urldecode($key)] = urldecode($val);
			}
			//检查交易付款状态 payment_status 是否为 Completed
			//检查交易流水号 txn_id 是否已经被处理过
			//检查接收 EMAIL receiver_email 是否为您的 PayPal 中已经注册的 EMAIL
			//检查金额 mc_gross 是否正确
			//……
			//处理此次付款明细
			//该付款明细所有变量可参考：
			//https://www.paypal.com/IntegrationCenter/ic_ipn-pdt-variable-reference.html
			$item_number = intval($keyarray['item_number']);
			$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE itemid='$item_number' AND status=0");
			if($charge_orderid == $item_number) {
				$payment_status = $keyarray['payment_status'];
				$payment_amount = $keyarray['mc_gross'];
				$payment_currency = $keyarray['mc_currency'];
				$receiver_email = $keyarray['receiver_email'];
				if($payment_amount != $charge_money) {
					$charge_status = 2;
					$charge_errcode = '充值金额不匹配';
				} else if($payment_currency != $PAY[$bank]['currency']) {
					$charge_status = 2;
					$charge_errcode = '充值币种不匹配';
				} else if($receiver_email != $PAY[$bank]['partnerid']) {
					$charge_status = 2;
					$charge_errcode = '收款帐号不匹配';
				} else if($payment_status == 'Completed') {
					$charge_status = 1;
				}
			}

		} else if(strcmp($lines[0], "FAIL") == 0) {
			//获取付款明细失败，记录并检查		
			$charge_status = 2;
			$charge_errcode = '支付失败';
		}
	}
	fclose($fp);
} else {
	dheader('?action=record');
}
?>