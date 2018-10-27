<?php 
defined('IN_DESTOON') or exit('Access Denied');
$notify_url = DT_PATH.'api/pay/'.$bank.'/'.($PAY[$bank]['notify'] ? $PAY[$bank]['notify'] : 'notify.php');
$item_name = $charge_title ? $charge_title : '会员('.$_username.')充值(流水号:'.$orderid.')';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo DT_CHARSET;?>">
<title>正在跳转到<?php echo $PAY[$bank]['name'];?>在线支付平台...</title>
</head>
<body onload="document.getElementById('pay').submit();">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="pay">
<input type="hidden" name="return" value="<?php echo $receive_url;?>" />
<input type="hidden" name="notify_url" value="<?php echo $notify_url;?>" />
<input type="hidden" name="cancel_return" value="<?php echo $MODULE[2]['linkurl'];?>charge.php?action=record" />
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $PAY[$bank]['partnerid'];?>">
<input type="hidden" name="item_name" value="<?php echo $item_name;?>" />
<input type="hidden" name="item_number" value="<?php echo $orderid;?>" />
<input type="hidden" name="charset" value="<?php echo DT_CHARSET;?>" />
<input type="hidden" name="currency_code" value="<?php echo $PAY[$bank]['currency'];?>">
<input type="hidden" name="amount" value="<?php echo $charge;?>">
<input type="hidden" name="image_url" value="<?php echo DT_SKIN;?>image/logo.gif" />
<input type="hidden" name="email" value="<?php echo $_email;?>" />
<input type="hidden" name="custom" value="<?php echo $_email;?>" />
</form>
</body>
</html>