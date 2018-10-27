<?php 
defined('IN_DESTOON') or exit('Access Denied');
$userdb = array();
if(strpos($MOD['passport_url'], ',') !== false) {
	$clienturl = explode(',', $MOD['passport_url']);
	$jumpurl = array_shift($clienturl);
	$userdb['url'] = implode(',', $clienturl);
} else {
	$jumpurl = $MOD['passport_url'];
}
if(isset($user)) {
	$userdb['uid']		= $user['userid'];
	$userdb['username']	= $user['passport'];
	$userdb['password']	= $user['password'];
	$userdb['email']	= $user['email'];
	$userdb['gender']	= $user['gender'];
	$userdb['credit']	= $user['credit'];
	$userdb['time']		= $DT_TIME;
	$userdb['cktime']	= $cookietime > 0 ? ($DT_TIME + $cookietime) : 0;
}
$userdb_encode = '';
foreach($userdb as $key=>$val) {
	$userdb_encode .= $userdb_encode ? "&$key=$val" : "$key=$val";
}
$userdb_encode = str_replace('=', '', strcode($userdb_encode));

if($action == 'login') {
	$verify = md5('login'.$userdb_encode.$forward.$MOD['passport_key']);
	$api_url = $jumpurl.'/passport_client.php?action=login&userdb='.rawurlencode($userdb_encode).'&forward='.rawurlencode($forward).'&verify='.rawurlencode($verify);
} else if($action == 'logout') {
	$verify = md5('quit'.$userdb_encode.$forward.$MOD['passport_key']);
    $api_url = $jumpurl.'/passport_client.php?action=quit&userdb='.rawurlencode($userdb_encode).'&forward='.rawurlencode($forward).'&verify='.rawurlencode($verify);
}
function strcode($string, $action = 'ENCODE') {
	global $MOD;
	$key	= substr(md5($_SERVER["HTTP_USER_AGENT"].$MOD['passport_key']), 8, 18);
	$string	= $action == 'ENCODE' ? $string : base64_decode($string);
	$len	= strlen($key);
	$code	= '';
	for($i = 0; $i < strlen($string); $i++) {
		$k		= $i % $len;
		$code  .= $string[$i] ^ $key[$k];
	}
	$code = $action == 'DECODE' ? $code : base64_encode($code);
	return $code;
}
?>