<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/api/pay/'.$bank.'/notify.class.php';
require DT_ROOT.'/api/pay/'.$bank.'/config.inc.php';
/*
	*功能：付完款后跳转的页面
	*版本：2.0
	*日期：2008-08-01
	*作者：支付宝公司销售部技术支持团队
	*联系：0571-26888888
	*版权：支付宝公司
*/

$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
$verify_result = $alipay->return_verify();
/*
 //获取支付宝的反馈参数
   //$dingdan    = $out_trade_no;   //获取订单号
   //$total_fee  = $total_fee;      //获取总价格
 
    $receive_name    =$receive_name;    //获取收货人姓名
	$receive_address =$receive_address; //获取收货人地址
	$receive_zip     =$receive_zip;     //获取收货人邮编
	$receive_phone   =$receive_phone;   //获取收货人电话
	$receive_mobile  =$receive_mobile;  //获取收货人手机
*/

if($verify_result) {    //认证合格
	if($out_trade_no != $charge_orderid) {
		$charge_status = 2;
		$charge_errcode = '订单号不匹配';
		$note = $charge_errcode.'S:'.$charge_orderid.'R:'.$out_trade_no;
		log_result($note);
	} else if($total_fee != $charge_money) {
		$charge_status = 2;
		$charge_errcode = '充值金额不匹配';
		$note = $charge_errcode.'S:'.$charge_money.'R:'.$total_fee;
		log_result($note);
	} else {
		$charge_status = 1;
	}
	//这里放入你自定义代码,比如根据不同的trade_status进行不同操作
	//log_result("verify_success"); 
}
function log_result($word) {
	log_write($word, 'ralipay');
}
?>