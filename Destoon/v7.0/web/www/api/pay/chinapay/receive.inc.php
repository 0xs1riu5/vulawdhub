<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/api/pay/'.$bank.'/netpayclient_config.php';
//加载 netpayclient 组件
require DT_ROOT.'/api/pay/'.$bank.'/netpayclient.php';
//导入公钥文件
$flag = buildKey(PUB_KEY);
if(!$flag) message("导入公钥文件失败！");
//获取交易应答的各项值
$merid = $merid;
$orderno = $orderno;
$transdate = $transdate;
$amount = $amount;
$currencycode = $currencycode;
$transtype = $transtype;
$status = $status;
$checkvalue = $checkvalue;
$gateId = $GateId;
$priv1 = $Priv1;
/*	
	$merid = $_REQUEST["merid"];
	$orderno = $_REQUEST["orderno"];
	$transdate = $_REQUEST["transdate"];
	$amount = $_REQUEST["amount"];
	$currencycode = $_REQUEST["currencycode"];
	$transtype = $_REQUEST["transtype"];
	$status = $_REQUEST["status"];
	$checkvalue = $_REQUEST["checkvalue"];
	$gateId = $_REQUEST["GateId"];
	$priv1 = $_REQUEST["Priv1"];

	echo "商户号: [$merid]<br/>";
	echo "订单号: [$orderno]<br/>";
	echo "订单日期: [$transdate]<br/>";
	echo "订单金额: [$amount]<br/>";
	echo "货币代码: [$currencycode]<br/>";
	echo "交易类型: [$transtype]<br/>";
	echo "交易状态: [$status]<br/>";
	echo "网关号: [$gateId]<br/>";
	echo "备注: [$priv1]<br/>";
	echo "签名值: [$checkvalue]<br/>";
	echo "===============================<br/>";
*/	
//验证签名值，true 表示验证通过
$flag = verifyTransResponse($merid, $orderno, $amount, $currencycode, $transdate, $transtype, $status, $checkvalue);
if($flag) {
	if($status == '1001') {
		//您的处理逻辑请写在这里，如更新数据库等。
		//注意：如果您在提交时同时填写了页面返回地址和后台返回地址，且地址相同，请在这里先做一次数据库查询判断订单状态，以防止重复处理该笔订单
		if($priv1 != $charge_orderid) {
			$charge_status = 2;
			$charge_errcode = '订单号不匹配';
			$note = $charge_errcode.'S:'.$charge_orderid.'R:'.$priv1;
			log_write($note, 'rchinapay');
		} else if($amount != padstr($charge_money*100, 12)) {
			$charge_status = 2;
			$charge_errcode = '充值金额不匹配';
			$note = charge_errcode.'S:'.$charge_money.'R:'.$amount;
			log_write($note, 'rchinapay');
		} else {
			$charge_status = 1;
		}
	}
}