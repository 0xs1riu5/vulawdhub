<?php
//nps 网上支付接口
include_once DEDEMEMBER.'/paycenter/nps/nps_config.inc.php';
//支付手续费
if($payment_exp[1] < 0.01) $payment_exp[1] = 0;
$piice_ex = $price*$payment_exp[1];
$price = $price+$piice_ex;
// 公共函数定义
function HexToStr($hex)
{
    $string="";
    for($i=0;$i<strlen($hex)-1;$i+=2){ $string.=chr(hexdec($hex[$i].$hex[$i+1])); }
    return $string;
}

function StrToHex($string)
{
   $hex="";
   for($i=0;$i<strlen($string);$i++){ $hex.=dechex(ord($string[$i])); }
   $hex=strtoupper($hex);
   return $hex;
}

//nps信息
$m_language	=	1;
$s_name		=	"陈康";
$s_addr		=	"深圳";
$s_postcode	=	518000;
$s_tel		=	"0755-83791960";
$r_name		=	"陈大康";
$r_addr		=	"深圳";
$r_postcode	=	100080;
$r_tel		=	"010-81234567";
$r_eml		=	"service@nps.cn";
$m_status	= 	0;
$m_ocurrency    =	1;

$m_id		=	$cfg_merchant;
$m_orderid	=	$buyid;
$m_oamount	=	$price;
$m_url		=	$cfg_basehost."/paycenter/nps/pay_back_nps.php";
$m_ocomment	=	$cfg_ml->M_ID;
$modate		=	GetDateTimeMk($mtime);


//组织订单信息
$m_info = $m_id."|".$m_orderid."|".$m_oamount."|".$m_ocurrency."|".$m_url."|".$m_language;
$s_info = $s_name."|".$s_addr."|".$s_postcode."|".$s_tel."|".$s_eml;
$r_info = $r_name."|".$r_addr."|".$r_postcode."|".$r_tel."|".$r_eml."|".$m_ocomment."|".$m_status."|".$modate;

$OrderInfo = $m_info."|".$s_info."|".$r_info;

//订单信息先转换成HEX，然后再加密
$OrderInfo = StrToHex($OrderInfo);
$digest = strtoupper(md5($OrderInfo.$cfg_merpassword));

$strRequestUrl = $payment_url.'?OrderMessage='.$OrderInfo.'&digest='.$digest.'&M_ID='.$cfg_merchant;


echo '<html>
<head>
	<title>转到NPS支付页面</title>
</head>
<body onload="document.nps.submit();">
	<form name="nps" action="'.$strRequestUrl.'" method="post">
	</form>
</body>
</html>';
exit;
?>