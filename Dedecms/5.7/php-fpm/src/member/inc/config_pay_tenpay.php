<?php 
//财付通 网上支付接口
/*这里替换为您的实际商户号*/
$strSpid    = $payment_userid[0];
/*strSpkey是32位商户密钥, 请替换为您的实际密钥*/
$strSpkey   = $payment_key[0];
/*银行类型:    
        0        财付通
          1001    招商银行   
          1002    中国工商银行  
          1003    中国建设银行  
          1004    上海浦东发展银行   
          1005    中国农业银行  
          1006    中国民生银行  
          1008    深圳发展银行   
          1009    兴业银行   */
if(!isset($BankType)) $BankType = 0;
$BankType = preg_replace("#[^0-9]#","",$BankType);
if($BankType < 1) $BankType = 0;
$strBankType= $BankType;
$strCmdNo   = "1";
$strBillDate= date('Ymd');
/*商品名称*/
if(!isset($pname)) $pname = '服务购买';
$strDesc    = $pname;
/*用户QQ号码, 现在置为空串*/
$strBuyerId = "";
/*商户号*/
$strSaler   = $payment_userid[0];
//支付手续费
if($payment_exp[0] < 0) $payment_exp[0] = 0;
$piice_ex = $price*$payment_exp[0];
$price         = $price+$piice_ex;
//支付金额
$strTotalFee = $price*100;
if( $strTotalFee < 1){
    $dsql->Close();
    exit('金额不对');
}
$strSpBillNo = $buyid;;
/*重要: 交易单号
      交易单号(28位): 商户号(10位) + 日期(8位) + 流水号(10位), 必须按此格式生成, 且不能重复
      如果sp_billno超过10位, 则截取其中的流水号部分加到transaction_id后部(不足10位左补0)
      如果sp_billno不足10位, 则左补0, 加到transaction_id后部*/
$strTransactionId = $strSpid . $strBillDate . time();
/*货币类型: 1 – RMB(人民币) 2 - USD(美元) 3 - HKD(港币)*/
$strFeeType  = "1";
/*财付通回调页面地址, 推荐使用ip地址的方式(最长255个字符)*/
$strRetUrl  = $cfg_basehost."/member/paycenter/tenpay/notify_handler.php";
/*商户私有数据, 请求回调页面时原样返回*/
$strAttach  = "my_magic_string";
/*生成MD5签名*/
$strSignText = "cmdno=" . $strCmdNo . "&date=" . $strBillDate . "&bargainor_id=" . $strSaler .
          "&transaction_id=" . $strTransactionId . "&sp_billno=" . $strSpBillNo .        
          "&total_fee=" . $strTotalFee . "&fee_type=" . $strFeeType . "&return_url=" . $strRetUrl .
          "&attach=" . $strAttach . "&key=" . $strSpkey;
$strSign = strtoupper(md5($strSignText));

/*请求支付串*/
$strRequest = "cmdno=" . $strCmdNo . "&date=" . $strBillDate . "&bargainor_id=" . $strSaler .        
"&transaction_id=" . $strTransactionId . "&sp_billno=" . $strSpBillNo .        
"&total_fee=" . $strTotalFee . "&fee_type=" . $strFeeType . "&return_url=" . $strRetUrl .        
"&attach=" . $strAttach . "&bank_type=" . $strBankType . "&desc=" . $strDesc .        
"&purchaser_id=" . $strBuyerId .        
"&sign=" . $strSign ;
$strRequestUrl = "https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi?".$strRequest;


if($cfg_soft_lang == 'utf-8')
{
    $strRequestUrl = utf82gb($strRequestUrl);    
    echo '<html>
    <head>
        <title>转到财付通支付页面</title>
    </head>
    <body onload="document.tenpay.submit();">
        <form name="tenpay" action="paycenter/tenpay/tenpay_gbk_page.php?strReUrl='.urlencode($strRequestUrl).'" method="post">
        </form>
    </body>
    </html>';    
}else{
    echo '<html>
    <head>
        <title>转到财付通支付页面</title>
    </head>
    <body onload="document.tenpay.submit();">
        <form name="tenpay" action="'.$strRequestUrl.'" method="post">
        </form>
    </body>
    </html>';
}
exit;