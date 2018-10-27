<?php
require '../../../common.inc.php';
require 'init.inc.php';
$_REQUEST['code'] or dalert('Error Request.', $MODULE[2]['linkurl'].$DT['file_login'].'?step=callback&site='.$site);
$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
$token = '';
if(isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken('code', $keys);
	} catch (OAuthException $e) {
		
	}
}
if($token) {
	$_SESSION['token'] = $token;
	setcookie('weibojs_'.$o->client_id, http_build_query($token));
	if($OAUTH[$site]['sync']) set_cookie('sina_token', $token['access_token'], $DT_TIME + $token['expires_in']);
	dheader('index.php?time='.$DT_TIME);
} else {
	dalert('Error Token.', $MODULE[2]['linkurl'].$DT['file_login'].'?step=token&site='.$site);
}
?>