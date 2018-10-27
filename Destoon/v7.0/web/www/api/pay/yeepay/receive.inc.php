<?php
defined('IN_DESTOON') or exit('Access Denied');
/*
 * @Description 易宝支付B2C在线支付接口范例 
 * @V3.0
 * @Author rui.xin
 */
 
include DT_ROOT.'/api/pay/'.$bank.'/yeepayCommon.php';	
	
#	只有支付成功时易宝支付才会通知商户.
##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

#	解析返回参数.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	判断返回签名是否正确（True/False）
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	以上代码和变量不需要修改.
	 	
#	校验码正确.
if($bRet){
	if($r1_Code=="1"){
		
	#	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
	#	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.      	  	
		
		if($r9_BType=="1"){
			if($r6_Order != $charge_orderid) {
				$charge_status = 2;
				$charge_errcode = '订单号不匹配';
				#$note = $charge_errcode.'S:'.$charge_orderid.'R:'.$r6_Order;
				#log_write($note, 'ryeepay');
			} else if($r3_Amt != $charge_money) {
				$charge_status = 2;
				$charge_errcode = '充值金额不匹配';
				$note = $charge_errcode.'S:'.$charge_money.'R:'.$r3_Amt;
				log_write($note, 'ryeepay');
			} else {
				$charge_status = 1;
			}
			//echo "交易成功";
			//echo  "<br />在线支付页面返回";
		}elseif($r9_BType=="2"){
			#如果需要应答机制则必须回写流,以success开头,大小写不敏感.
			//echo "success";
			//echo "<br />交易成功";
			//echo  "<br />在线支付服务器返回";      			 
		}
	}
	
}else{
	//echo "交易信息被篡改";
}
   
?>