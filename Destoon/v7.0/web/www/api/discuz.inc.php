<?php 
defined('IN_DESTOON') or exit('Access Denied');
$forward = preg_replace("/([?|&])sid=(.*)/", '', $forward);
if($action == 'login') {
	$txt = "time=$DT_TIME&cookietime=$cookietime&username=$passport&password=$password&secques=".substr(md5(random(8)), 16, 8)."&gender=$gender&email=$email&regip=$regip&regdate=$regtime&oicq=$qq&msn=$msn&showemail=0";
	$auth = encrypt($txt, $MOD['passport_key']);
	$verify = md5('login'.$auth.$forward.$MOD['passport_key']);
	$api_url = $MOD['passport_url'].'/api/passport.php?action=login&auth='.urlencode($auth).'&forward='.urlencode($forward).'&verify='.$verify;
} else if($action == 'logout') {
	$auth = encrypt('', $MOD['passport_key']);
	$verify = md5('logout'.$auth.$forward.$MOD['passport_key']);
	$api_url = $MOD['passport_url'].'/api/passport.php?action=logout&auth='.urlencode($auth).'&forward='.urlencode($forward).'&verify='.$verify;
}
?>