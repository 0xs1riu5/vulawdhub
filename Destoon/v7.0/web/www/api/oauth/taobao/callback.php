<?php
require '../../../common.inc.php';
require 'init.inc.php';
$_REQUEST['code'] or dalert('Error Request.', $MODULE[2]['linkurl'].$DT['file_login'].'?step=callback&site='.$site);
$par = 'grant_type=authorization_code'
	 . '&code='.$_REQUEST['code']
	 . '&client_id='.TB_ID
	 . '&client_secret='.TB_SECRET
	 . '&redirect_uri='.urlencode(TB_CALLBACK);
$rec = dcurl(TB_TOKEN_URL, $par);
if(strpos($rec, 'access_token') !== false) {
	$arr = json_decode($rec, true);
	$_SESSION['tb_access_token'] = $arr['access_token'];
	$_SESSION['tb_openid'] = $arr['taobao_user_id'];
	$_SESSION['tb_nickname'] = urldecode($arr['taobao_user_nick']);
	dheader('index.php?time='.$DT_TIME);
} else {
	dalert('Error Token.', $MODULE[2]['linkurl'].$DT['file_login'].'?step=token&site='.$site);
}
?>