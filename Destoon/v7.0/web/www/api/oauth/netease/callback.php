<?php
require '../../../common.inc.php';
require 'init.inc.php';
$_REQUEST['code'] or dalert('Error Request.', $MODULE[2]['linkurl'].$DT['file_login'].'?step=callback&site='.$site);
$par = 'grant_type=authorization_code'
	 . '&code='.$_REQUEST['code']
	 . '&client_id='.NE_ID
	 . '&client_secret='.NE_SECRET
	 . '&redirect_uri='.urlencode(NE_CALLBACK);
$rec = dcurl(NE_TOKEN_URL, $par);
if(strpos($rec, 'access_token') !== false) {
	$arr = json_decode($rec, true);
	$_SESSION['ne_access_token'] = $arr['access_token'];
	dheader('index.php?time='.$DT_TIME);
} else {
	dalert('Error Token.', $MODULE[2]['linkurl'].$DT['file_login'].'?step=token&site='.$site);
}
?>