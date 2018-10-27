<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$reason = $L['invite_title'];
$userurl = '';
if(isset($user) && check_name($user)) {
	$c = $db->get_one("SELECT linkurl,username FROM {$DT_PRE}company WHERE username='$user'");
	if($c) {
		$userurl = $c['linkurl'];
		$user = $username = $c['username'];
		$could_credit = true;
		if($MOD['credit_ip'] <= 0) $could_credit = false;
		if($could_credit) {
			$r = $db->get_one("SELECT itemid FROM {$DT_PRE}finance_credit WHERE note='$DT_IP' AND addtime>$DT_TIME-86400");
			if($r) $could_credit = false;
		}
		if($could_credit && $MOD['credit_maxip'] > 0) {
			$r = $db->get_one("SELECT SUM(amount) AS total FROM {$DT_PRE}finance_credit WHERE username='$username' AND addtime>$DT_TIME-86400 AND reason='$reason'");
			if($r['total'] > $MOD['credit_maxip']) $could_credit = false;
		}
		if($could_credit) {
			credit_add($username, $MOD['credit_ip']);
			credit_record($username, $MOD['credit_ip'], 'system', $reason, $DT_IP);
		}
		set_cookie('inviter', encrypt($username, DT_KEY.'INVITER'), $DT_TIME + 30*86400);
	} else {
		dheader(DT_PATH);
	}
} else {
	dheader(DT_PATH);
}
$goto = isset($goto) ? trim($goto) : '';
$URI = DT_PATH;
if($goto == 'register') {
	$URI = $MODULE[2]['linkurl'].$DT['file_register'];
} else if($goto == 'homepage') {
	if($userurl) $URI = $userurl;
}
dheader($URI);
?>