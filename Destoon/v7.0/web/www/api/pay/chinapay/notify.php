<?php
$_SERVER['REQUEST_URI'] = '';
$_DPOST = $_POST;
$_DGET = $_GET;
require '../../../common.inc.php';
$_POST = $_DPOST;
$_GET = $_DGET;
if(!$_POST && !$_GET) exit('fail');
$bank = 'chinapay';
$PAY = cache_read('pay.php');
if(!$PAY[$bank]['enable']) exit('fail');
if(!$PAY[$bank]['partnerid']) exit('fail');
$receive_url = '';
require DT_ROOT."/api/pay/".$bank."/netpayclient_config.php";
//加载 netpayclient 组件
require DT_ROOT."/api/pay/".$bank."/netpayclient.php";
//导入公钥文件
$flag = buildKey(PUB_KEY);
$flag or exit('导入公钥文件失败！');

//获取交易应答的各项值
$merid = $_POST["merid"];
$orderno = $_POST["orderno"];
$transdate = $_POST["transdate"];
$amount = $_POST["amount"];
$currencycode = $_POST["currencycode"];
$transtype = $_POST["transtype"];
$status = $_POST["status"];
$checkvalue = $_POST["checkvalue"];
$gateId = $_POST["GateId"];
$priv1 = $_POST["Priv1"];
$flag = verifyTransResponse($merid, $orderno, $amount, $currencycode, $transdate, $transtype, $status, $checkvalue);
if($flag) {
	if($status == '1001') {
		//您的处理逻辑请写在这里，如更新数据库等。
		//注意：如果您在提交时同时填写了页面返回地址和后台返回地址，且地址相同，请在这里先做一次数据库查询判断订单状态，以防止重复处理该笔订单
		$priv1 = intval($priv1);
		$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE itemid='$priv1'");
		if($r) {
			if($r['status'] == 0) {
				$charge_orderid = $r['itemid'];
				$charge_money = $r['amount'] + $r['fee'];
				$charge_amount = $r['amount'];
				$editor = 'N'.$bank;
				if($amount == padstr($charge_money*100, 12)) {
					require DT_ROOT.'/api/pay/success.inc.php';
					exit('success');
				} else {
					$note = '充值金额不匹配S:'.$charge_money.'R:'.$amount;
					$db->query("UPDATE {$DT_PRE}finance_charge SET status=1,receivetime='$DT_TIME',editor='$editor',note='$note' WHERE itemid=$charge_orderid");//支付失败
					exit('fail');
				}
			} else if($r['status'] == 1) {
				exit('fail');
			} else if($r['status'] == 2) {
				exit('fail');
			} else {
				exit('success');
			}
		} else {
			exit('fail');
		}
	}
}
exit('fail');
?>