<?php
defined('IN_DESTOON') or exit('Access Denied');
/*请按照您的实际情况配置以下各参数*/

//私钥文件，在CHINAPAY申请商户号时获取，请相应修改此处，可填相对路径，下同
define("PRI_KEY", DT_ROOT.'/api/pay/'.$bank.'/'.$PAY[$bank]['partnerid']);
//公钥文件，示例中已经包含
define("PUB_KEY", DT_ROOT.'/api/pay/'.$bank.'/PgPubk.key');

/*如您已有生产密钥，请修改以下配置，默认为测试环境*/

//支付请求地址(测试)
//define("REQ_URL_PAY","http://payment-test.ChinaPay.com/pay/TransGet");
//支付请求地址(生产)
define("REQ_URL_PAY","https://payment.ChinaPay.com/pay/TransGet");

//查询请求地址(测试)
//define("REQ_URL_QRY","http://payment-test.chinapay.com/QueryWeb/processQuery.jsp");
//查询请求地址(生产)
define("REQ_URL_QRY","http://console.chinapay.com/QueryWeb/processQuery.jsp");

//退款请求地址(测试)
//define("REQ_URL_REF","http://payment-test.chinapay.com/refund/SingleRefund.jsp");
//退款请求地址(生产)
define("REQ_URL_REF","https://bak.chinapay.com/refund/SingleRefund.jsp");

function getcwdOL(){
$total = $_SERVER[PHP_SELF];
$file = explode("/", $total);
$file = $file[sizeof($file)-1];
return substr($total, 0, strlen($total)-strlen($file)-1);
}

function getSiteUrl(){
	$host = $_SERVER[SERVER_NAME];
	$port = ($_SERVER[SERVER_PORT]=="80")?"":":$_SERVER[SERVER_PORT]";
	return "http://" . $host . $port . getcwdOL();
}

function traceLog($file, $log){
	$f = fopen($file, 'a'); 
	if($f){
		fwrite($f, date('Y-m-d H:i:s') . " => $log\n");
		fclose($f);
	} 
}

//取得本示例安装位置
$site_url = getSiteUrl();
?>