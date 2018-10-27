<?php
defined('IN_DESTOON') or exit('Access Denied');
//---------------------------------------------------------
//财付通即时到帐支付应答（处理回调）示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once DT_ROOT.'/api/pay/'.$bank.'/ResponseHandler.class.php';
require_once DT_ROOT.'/api/pay/'.$bank.'/function.php';
require_once DT_ROOT.'/api/pay/'.$bank.'/config.inc.php';

#log_result("进入前台回调页面");


/* 创建支付应答对象 */
$resHandler = new ResponseHandler();
$resHandler->setKey($key);

//判断签名
if($resHandler->isTenpaySign()) {
	
	//通知id
	$notify_id = $resHandler->getParameter("notify_id");
	//商户订单号
	$out_trade_no = $resHandler->getParameter("out_trade_no");
	//财付通订单号
	$transaction_id = $resHandler->getParameter("transaction_id");
	//金额,以分为单位
	$total_fee = $resHandler->getParameter("total_fee");
	//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
	$discount = $resHandler->getParameter("discount");
	//支付结果
	$trade_state = $resHandler->getParameter("trade_state");
	//交易模式,1即时到账
	$trade_mode = $resHandler->getParameter("trade_mode");

	$total_fee = ($total_fee+$discount)/100;
	
	
	if("1" == $trade_mode ) {
		if( "0" == $trade_state){ 
			//------------------------------
			//处理业务开始
			//------------------------------
			
			//注意交易单不要重复处理
			//注意判断返回金额
			
			//------------------------------
			//处理业务完毕
			//------------------------------	
			if($out_trade_no != $charge_orderid) {
				$charge_status = 2;
				$charge_errcode = '订单号不匹配';
				#$note = $charge_errcode.'S:'.$charge_orderid.'R:'.$out_trade_no;
				#log_write($note, 'rtenpay');
			} else if($total_fee != $charge_money) {
				$charge_status = 2;
				$charge_errcode = '充值金额不匹配';
				$note = $charge_errcode.'S:'.$charge_money.'R:'.$total_fee;
				log_write($note, 'rtenpay');
			} else {
				$charge_status = 1;
			}			
			#echo "<br/>" . "即时到帐支付成功" . "<br/>";
	
		} else {
			//当做不成功处理
			#echo "<br/>" . "即时到帐支付失败" . "<br/>";
		}
	}elseif( "2" == $trade_mode  ) {
		if( "0" == $trade_state) {
		
			//------------------------------
			//处理业务开始
			//------------------------------
			
			//注意交易单不要重复处理
			//注意判断返回金额
			
			//------------------------------
			//处理业务完毕
			//------------------------------	
			
			//echo "<br/>" . "中介担保支付成功" . "<br/>";
		
		} else {
			//当做不成功处理
			//echo "<br/>" . "中介担保支付失败" . "<br/>";
		}
	}
	
} else {
	//echo "<br/>" . "认证签名失败" . "<br/>";
	//echo $resHandler->getDebugInfo() . "<br>";
}

?>