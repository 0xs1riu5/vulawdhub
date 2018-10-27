<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
$itemid or dheader($MOD['linkurl']);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
include load('misc.lang');
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
$item or message($L['not_exists']);
if($item['fromtime'] && $DT_TIME > $item['fromtime']) message($L['has_started']);
if($item['totime'] && $DT_TIME > $item['totime']) message($L['has_expired']);
$item['status'] == 3 or message($L['not_exists']);
$item['username'] or message($L['com_not_member']);
$_username != $item['username'] or message($L['sign_self']);

$today = $today_endtime - 86400;
$sql = $_userid ? "username='$_username'" : "addtime>$today AND ip='$DT_IP'";
$t = $db->get_one("SELECT id FROM {$table_sign} WHERE id=$itemid AND $sql");
if($t) message($L['sign_again']);

$linkurl = $MOD['linkurl'].$item['linkurl'];
$need_captcha = $MOD['captcha_sign'] == 2 ? $MG['captcha'] : $MOD['captcha_sign'];
require DT_ROOT.'/include/post.func.php';

if($submit) {
	captcha($captcha, $need_captcha);
	$amount = intval($amount);
	if($amount < 1) $amount = 1;
	$company = dhtmlspecialchars($company);
	$truename = dhtmlspecialchars($truename);
	if(strlen($truename) < 6) message($L['msg_type_truename']);
	if(!is_mobile($mobile)) message($L['msg_type_mobile']);
	$areaid = intval($areaid);
	$address = dhtmlspecialchars($address);
	preg_match("/^[0-9]{6}$/", $postcode) or $postcode = '';
	is_email($email) or $email = '';
	is_qq($qq) or $qq = '';
	is_wx($wx) or $wx = '';
	$content = dhtmlspecialchars($content);
	$user = $item['username'];
	$title = addslashes($item['title']);
	$db->query("INSERT INTO {$table_sign} (id,user,title,amount,company,truename,mobile,areaid,address,postcode,email,qq,wx,content,addtime,username,ip) VALUES ('$itemid','$user','$title','$amount','$company','$truename','$mobile','$areaid','$address','$postcode','$email','$qq','$wx','$content','$DT_TIME','$_username','$DT_IP')");
	$db->query("UPDATE {$table} SET orders=orders+1 WHERE itemid=$itemid");
	$forward = $DT_PC ? $linkurl : str_replace($MOD['linkurl'], $MOD['mobile'], $linkurl);
	message($L['msg_sign_success'], $forward, 3);
}
if($_userid) {
	$user = userinfo($_username);
	$company = $user['company'];
	$truename = $user['truename'];
	$mobile = $user['mobile'];
	$areaid = $user['areaid'];
	$address = $user['address'];
	$postcode = $user['postcode'];
	$email = $user['mail'] ? $user['mail'] : $user['email'];
	$qq = $user['qq'];
	$wx = $user['wx'];
} else {	
	$company = $truename = $mobile = $areaid = $address = $postcode = $email = $qq = $wx = '';
}
$head_title = $L['sign_title'];
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	$back_link = $forward = $MOD['mobile'].$item['linkurl'];
	$head_name = $L['sign_title'];
	$foot = '';
}
include template($MOD['template_sign'] ? $MOD['template_sign'] : 'sign', $module);
?>