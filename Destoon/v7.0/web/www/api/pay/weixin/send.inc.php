<?php
defined('IN_DESTOON') or exit('Access Denied');
if($DT_MOB['browser'] == 'weixin' && $EXT['weixin']) {
	$openid = '';
	$t = $db->get_one("SELECT openid FROM {$DT_PRE}weixin_user WHERE username='$_username'");
	if($t) {
		$openid = $t['openid'];
	} else {
		$openid = get_cookie('weixin_openid');
		if($openid) $openid = decrypt($openid, DT_KEY.'WXID');
	}
	$t = explode('MicroMessenger/', $_SERVER['HTTP_USER_AGENT']);
	if(intval($t[1]) >= 5) {
		if(is_openid($openid)) {
			dheader(DT_PATH.'api/pay/weixin/jsapi.php?auth='.encrypt($orderid.'|'.$charge_title.'|'.$DT_IP.'|'.$openid, DT_KEY.'JSPAY'));
		} else {
			dheader(DT_MOB.'api/weixin.php?url='.urlencode(DT_PATH.'api/pay/weixin/openid.php?itemid='.$orderid));
		}
	}
}
dheader(DT_PATH.'api/pay/weixin/qrcode.php?auth='.encrypt($orderid.'|'.$charge_title.'|'.$DT_IP, DT_KEY.'QRPAY'));
?>