<?php
require '../../../common.inc.php';
require 'init.inc.php';
$_REQUEST['code'] or dalert('Error Request.', $MODULE[2]['linkurl'].$DT['file_login'].'?step=callback&site='.$site);
$par = 'grant_type=authorization_code'
	 . '&code='.$_REQUEST['code']
	 . '&appid='.WX_ID
	 . '&secret='.WX_SECRET;
$rec = dcurl(WX_TOKEN_URL, $par);
if(strpos($rec, 'access_token') !== false) {
	$arr = json_decode($rec, true);
	$_SESSION['wx_access_token'] = $arr['access_token'];
	$_SESSION['wx_openid'] = $arr['openid'];
	dheader('index.php?time='.$DT_TIME);
} else {
	dalert('Error Token.', $MODULE[2]['linkurl'].$DT['file_login'].'?step=token&site='.$site);
}
?>