<?php 
defined('IN_DESTOON') or exit('Access Denied');
$admin_user = false;
if($_groupid == 1) {
	$admin_user = decrypt(get_cookie('admin_user'), DT_KEY.'ADMIN');
	if($admin_user) {
		$_USER = explode('|', $admin_user);
		if($_userid && $_username == $_USER[1]) {
			$__userid = intval($_USER[0]);
			if($__userid && !is_founder($__userid)) {
				$USER = $db->get_one("SELECT username,passport,company,truename,mobile,password,groupid,email,message,chat,sound,online,sms,credit,money,loginip,admin,aid,edittime,trade FROM {$DT_PRE}member WHERE userid=$__userid");
				if($USER) {
					if($USER['groupid'] == 1 && !is_founder($_userid)) exit('Request Denied');
					$_userid = $__userid;
					extract($USER, EXTR_PREFIX_ALL, '');
					$MG = cache_read('group-'.$_groupid.'.php');
					$admin_user = true;
				}
			}
		}
	}
}
?>