<?php
$_SERVER['REQUEST_URI'] = '';
require '../../../common.inc.php';
if(!$_REQUEST) exit('fail');
$bank = 'kq99bill';
$PAY = cache_read('pay.php');
if(!$PAY[$bank]['enable']) exit('fail');
function kq_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
//人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
$kq_check_all_para=kq_ck_null($_REQUEST[merchantAcctId],'merchantAcctId');
//网关版本，固定值：v2.0,该值与提交时相同。
$kq_check_all_para.=kq_ck_null($_REQUEST[version],'version');
//语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
$kq_check_all_para.=kq_ck_null($_REQUEST[language],'language');
//签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
$kq_check_all_para.=kq_ck_null($_REQUEST[signType],'signType');
//支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
$kq_check_all_para.=kq_ck_null($_REQUEST[payType],'payType');
//银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
$kq_check_all_para.=kq_ck_null($_REQUEST[bankId],'bankId');
//商户订单号，,该值与提交时相同。
$kq_check_all_para.=kq_ck_null($_REQUEST[orderId],'orderId');
//订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
$kq_check_all_para.=kq_ck_null($_REQUEST[orderTime],'orderTime');
//订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
$kq_check_all_para.=kq_ck_null($_REQUEST[orderAmount],'orderAmount');
$kq_check_all_para.=kq_ck_null($_REQUEST[bindCard],'bindCard');
$kq_check_all_para.=kq_ck_null($_REQUEST[bindMobile],'bindMobile');
// 快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
$kq_check_all_para.=kq_ck_null($_REQUEST[dealId],'dealId');
//银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
$kq_check_all_para.=kq_ck_null($_REQUEST[bankDealId],'bankDealId');
//快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
$kq_check_all_para.=kq_ck_null($_REQUEST[dealTime],'dealTime');
//商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
$kq_check_all_para.=kq_ck_null($_REQUEST[payAmount],'payAmount');
//费用，快钱收取商户的手续费，单位为分。
$kq_check_all_para.=kq_ck_null($_REQUEST[fee],'fee');
//扩展字段1，该值与提交时相同
$kq_check_all_para.=kq_ck_null($_REQUEST[ext1],'ext1');
//扩展字段2，该值与提交时相同。
$kq_check_all_para.=kq_ck_null($_REQUEST[ext2],'ext2');
//处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
$kq_check_all_para.=kq_ck_null($_REQUEST[payResult],'payResult');
//错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
$kq_check_all_para.=kq_ck_null($_REQUEST[errCode],'errCode');

$trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
$MAC=base64_decode($_REQUEST[signMsg]);

$fp = fopen(DT_ROOT."/api/pay/".$bank."/".$PAY[$bank]['cert'], "r"); 
$cert = fread($fp, 8192); 
fclose($fp); 
$pubkeyid = openssl_get_publickey($cert); 
$ok = openssl_verify($trans_body, $MAC, $pubkeyid); 
if($ok == 1) { 
	switch($_REQUEST[payResult]){
		case '10':
			//此处做商户逻辑处理
			$itemid = intval($_REQUEST['orderId']);
			$amount = $_REQUEST['payAmount']/100;
			$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE itemid='$itemid'");
			if($r) {
				if($r['status'] == 0) {
					$charge_orderid = $r['itemid'];
					$charge_money = $r['amount'] + $r['fee'];
					$charge_amount = $r['amount'];
					$editor = 'N'.$bank;
					if($amount == $charge_money) {
						require DT_ROOT.'/api/pay/success.inc.php';
					} else {
						$note = '充值金额不匹配S:'.$charge_money.'R:'.$amount;
						$db->query("UPDATE {$DT_PRE}finance_charge SET status=1,receivetime='$DT_TIME',editor='$editor',note='$note' WHERE itemid=$charge_orderid");//支付失败
					}
				}
			}
			$rtnOK=1;
			//以下是我们快钱设置的show页面，商户需要自己定义该页面。
			$rtnUrl = $MODULE[2]['linkurl'].'charge.php';
		break;
		default:
			$rtnOK=1;
			//以下是我们快钱设置的show页面，商户需要自己定义该页面。
			$rtnUrl = $MODULE[2]['linkurl'].'charge.php';
		break;
	}

} else {
	$rtnOK=1;
	//以下是我们快钱设置的show页面，商户需要自己定义该页面。
	$rtnUrl = $MODULE[2]['linkurl'].'charge.php';					
}
?>
<result><?php echo $rtnOK; ?></result> <redirecturl><?php echo $rtnUrl; ?></redirecturl>