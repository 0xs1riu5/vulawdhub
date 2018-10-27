<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/module/'.$module.'/member.class.php';
require DT_ROOT.'/include/post.func.php';
$do = new member;
$do->userid = $_userid;
$do->username = $_username;
$user = $do->get_one();
$GROUP = cache_read('group.php');
$MFD = cache_read('fields-member.php');
$CFD = cache_read('fields-company.php');
isset($post_fields) or $post_fields = array();
if($MFD || $CFD) require DT_ROOT.'/include/fields.func.php';
$group_editor = $MG['editor'];
in_array($group_editor, array('Default', 'Destoon', 'Simple', 'Basic')) or $group_editor = 'Destoon';
$tab = isset($tab) ? intval($tab) : 0;
$is_company = $MG['type'] || ($_groupid == 4 && $GROUP[$user['regid']]['type']);
$_E = ($MOD['edit_check'] && $user['edittime'] > 0 && $is_company) ? explode(',', $MOD['edit_check']) : array();
if(in_array('capital', $_E)) $_E[] = 'regunit';
$content_table = content_table(4, $_userid, is_file(DT_CACHE.'/4.part'), $DT_PRE.'company_data');
$t = $db->get_one("SELECT * FROM {$content_table} WHERE userid=$_userid");
if($t) {
	$user['content'] = $content = $t['content'];
} else {
	$user['content'] = $content = '';
	$db->query("REPLACE INTO {$content_table} (userid,content) VALUES ('$_userid','')");
}
$need_captcha = $MOD['captcha_edit'] == 2 ? $MG['captcha'] : $MOD['captcha_edit'];
if($submit) {
	captcha($captcha, $need_captcha);
	if($post['password'] && $user['password'] != dpassword($post['oldpassword'], $user['passsalt'])) message($L['error_password']);
	if($post['payword'] && $user['payword'] != dpassword($post['oldpayword'], $user['paysalt'])) message($L['error_payword']);
	$post['groupid'] = $user['groupid'];
	$post['email'] = $user['email'];
	$post['passport'] = $user['passport'];
	$post['company'] = $user['company'];
	$post['domain'] = $user['domain'];
	$post['icp'] = $user['icp'];
	$post['skin'] = $user['skin'];
	$post['template'] = $user['template'];
	$post['edittime'] = $DT_TIME;
	$post['bank'] = $user['bank'];
	$post['banktype'] = $user['banktype'];
	$post['branch'] = $user['branch'];
	$post['account'] = $user['account'];
	$post['validated'] = $user['validated'];
	$post['validator'] = $user['validator'];
	$post['validtime'] = $user['validtime'];
	$post['vemail'] = $user['vemail'];
	$post['vmobile'] = $user['vmobile'];
	$post['vtruename'] = $user['vtruename'];
	$post['vbank'] = $user['vbank'];
	$post['vcompany'] = $user['vcompany'];
	$post['vtrade'] = $user['vtrade'];
	$post['trade'] = $user['trade'];
	$post['support'] = $user['support'];
	$post['inviter'] = $user['inviter'];
	if($post['vmobile']) $post['mobile'] = $user['mobile'];
	if($post['vtruename']) $post['truename'] = $user['truename'];
	if($MFD) fields_check($post_fields, $MFD);
	if($CFD) fields_check($post_fields, $CFD);
	$post = dstripslashes($post);
	$post_check = array();
	if($_E) {
		if(in_array('thumb', $_E) || in_array('gzhqr', $_E) || in_array('content', $_E)) clear_upload($post['thumb'].$post['gzhqr'].$post['content'], $_userid, $do->table_company);
		foreach($_E as $k) {
			if($user[$k] && $post[$k] != $user[$k]) {
				$post_check[$k] = $post[$k];
				$post[$k] = $user[$k];
			}
		}
	}
	$post = daddslashes($post);
	$post_check = daddslashes($post_check);
	if($do->edit($post)) {
		if($MFD) fields_update($post_fields, $do->table_member, $do->userid, 'userid', $MFD);
		if($CFD) fields_update($post_fields, $do->table_company, $do->userid, 'userid', $CFD);
		if($post_check) $do->check_add($post_check);
		if($user['edittime'] == 0 && $user['inviter'] && $MOD['credit_user']) {
			$inviter = $user['inviter'];
			$r = $db->get_one("SELECT itemid FROM {$DT_PRE}finance_credit WHERE note='$_username' AND username='$inviter'");
			if(!$r) {
				credit_add($inviter, $MOD['credit_user']);
				credit_record($inviter, $MOD['credit_user'], 'system', $L['edit_invite'], $_username);
			}
		}
		if($user['edittime'] == 0 && $MOD['credit_edit']) {
			credit_add($_username, $MOD['credit_edit']);
			credit_record($_username, $MOD['credit_edit'], 'system', $L['edit_profile'], $DT_IP);
		}
		if($post['password']) dheader($DT['file_login'].'?auth='.encrypt('LOGIN|'.$_username.'|'.$post['password'].'|'.$DT_TIME, DT_KEY.'LOGIN').'&forward='.urlencode($MOD['linkurl'].'edit.php?success=1&tab='.$tab));
		dmsg($L['edit_msg_success'], '?tab='.$tab.'&success=1');
	} else {
		message($do->errmsg);
	}
}
$COM_TYPE = explode('|', $MOD['com_type']);
$COM_SIZE = explode('|', $MOD['com_size']);
$COM_MODE = explode('|', $MOD['com_mode']);
$MONEY_UNIT = explode('|', $MOD['money_unit']);
$head_title = $L['edit_title'];
$_U = $_E ? $do->check_get() : array();
if($_U) {
	foreach($_U as $k=>$v) {
		$user[$k] = $v;
	}
}
extract($user);
$mode_check = dcheckbox($COM_MODE, 'post[mode][]', $mode, 'onclick="check_mode(this, '.$MOD['mode_max'].');"', 0);
$cates = $catid ? explode(',', substr($catid, 1, -1)) : array();
$tab = isset($tab) ? intval($tab) : -1;
if($tab == 2 && !$is_company) $tab = 0;
if($DT_PC) {	
	//
} else {
	$foot = '';
	$back_link = 'index.php';
}
include template('edit', $module);
?>