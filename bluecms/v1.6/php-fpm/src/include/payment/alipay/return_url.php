<?php
require_once dirname(__FILE__)."/../../../include/common.inc.php";
require_once BLUE_ROOT.'data/pay.cache.php';
require_once BLUE_ROOT."include/payment/alipay/alipay_config.php";
require_once BLUE_ROOT."include/payment/alipay/alipay_notify.php";

$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
$verify_result = $alipay->return_verify();
//获取支付宝的反馈参数
$dingdan		=$_GET['out_trade_no'];   //获取订单号
$total_fee		=$_GET['total_fee'];    //获取总价格

if($verify_result) {
	$id = $dingdan;

	$db->query("UPDATE ".table('card_order')." SET is_pay=1 WHERE id=$id");
	$order = $db->query("SELECT user_id, value FROM ".table('card_order')." WHERE id=$id");
	if (!is_array($order)) {
		showmsg('充值错误，请联系网站管理员!', 'user.php');
	}
	$db->query("UPDATE ".table('user')." SET money=money+$order[value] WHERE user_id=$order[user_id]");
	showmsg('恭喜您充值成功', 'user.php');

	log_result("verify_success"); 
}
else
{
	showmsg('充值失败');
	log_result ("verify_failed");
	exit;
}

//日志消息,把支付宝反馈的参数记录下来
function log_result($word)
{
	$fp = fopen(BLUE_ROOT."include/payment/alipay/log.txt","a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,$word."：执行日期：".strftime("%Y%m%d%H%I%S",time())."\t\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}
?>