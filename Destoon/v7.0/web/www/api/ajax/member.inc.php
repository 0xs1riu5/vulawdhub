<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
isset($value) or $value = '';
require DT_ROOT.'/module/'.$module.'/member.class.php';
$do = new member;
if(isset($userid) && $userid) $do->userid = $userid;
switch($job) {
	case 'username':
		if(!$value) exit($L['member_username_match']);
		if(!$do->is_username($value)) exit($do->errmsg);
	break;
	case 'passport':
		if(!$value) exit;
		if($_userid) $do->userid = $_userid;
		if(!$do->is_passport($value)) exit($do->errmsg);
	break;
	case 'password':
		if(!$do->is_password($value, $value)) exit($do->errmsg);
	break;
	case 'payword':
		if(!$do->is_payword($value, $value)) exit($do->errmsg);
	break;
	case 'email':
		$value = trim($value);
		if(!$do->is_email($value)) exit($do->errmsg);
		if($do->email_exists($value)) exit($L['member_email_reg']);
	break;
	case 'mobile':
		$value = trim($value);
		if(!is_mobile($value)) exit($L['member_mobile_null']);
		if($do->mobile_exists($value)) exit($L['member_mobile_reg']);
	break;
	case 'company':
		if(!$value) exit($L['member_company_null']);
		if(!$do->is_company($value)) exit($do->errmsg);
		if($do->company_exists($value)) exit($L['member_company_reg']);
	break;
	case 'get_company':
		$user = $do->get_one($value);
		if($user) {
			echo '<a href="'.$user['linkurl'].'" target="_blank" class="t">'.$user['company'].'</a>'.( $user['vip'] ? ' <img src="'.DT_SKIN.'image/vip.gif" align="absmiddle"/> <img src="'.DT_SKIN.'image/vip_'.$user['vip'].'.gif" align="absmiddle"/>' : '');
		} else {
			echo '1';
		}
		exit;
	break;
}
?>