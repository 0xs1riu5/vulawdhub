<?php
require '../../../common.inc.php';
require 'init.inc.php';
$_REQUEST['code'] or dalert('Error Request.', $MODULE[2]['linkurl'].$DT['file_login'].'?step=callback&site='.$site);
$_REQUEST['state'] == $_SESSION['state'] or dalert('Error Request.', $MODULE[2]['linkurl'].$DT['file_login'].'?step=callback&site='.$site);
$par = 'grant_type=authorization_code'
	 . '&client_id='.QQ_ID
	 . '&client_secret='.QQ_SECRET
	 . '&code='.$_REQUEST['code']
	 . '&redirect_uri='.urlencode(QQ_CALLBACK);
$rec = dcurl(QQ_TOKEN_URL, $par);
if(strpos($rec, 'access_token') !== false) {
	parse_str($rec, $arr);
	$_SESSION['qq_access_token'] = $arr['access_token'];
	$_SESSION['qq_access_time'] = $arr['expires_in'];
	if($OAUTH[$site]['sync']) set_cookie('qq_token', $arr['access_token'], $DT_TIME + $arr['expires_in']);
	dheader('index.php?time='.$DT_TIME);
} else {
	dalert('Error Token.', $MODULE[2]['linkurl'].$DT['file_login'].'?step=token&site='.$site);
}
?>