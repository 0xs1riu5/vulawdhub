<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
$itemid or dheader($DT_PC ? $MOD['linkurl'] : $MOD['mobile']);
login();
if(!check_group($_groupid, $MOD['group_apply'])) include load('403.inc');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
include load('misc.lang');
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
$item or message($L['not_exists']);
if($item['totime'] && $DT_TIME > $item['totime']) message($L['has_expired']);
$item['status'] == 3 or message($L['not_exists']);
$item['username'] or message($L['com_not_member']);
$_username != $item['username'] or message($L['send_self']);

$app = $db->get_one("SELECT * FROM {$table_apply} WHERE jobid=$itemid AND apply_username='$_username'");
if($app) message($L['apply_again']);

$linkurl = ($DT_PC ? $MOD['linkurl'] : $MOD['mobile']).$item['linkurl'];
if($submit) {
	$resumeid = intval($resumeid);
	$resumeid or dheader($linkurl);
	$resume = $db->get_one("SELECT * FROM {$table_resume} WHERE itemid=$resumeid AND status=3 AND open=3 AND username='$_username'");
	$resume or message($L['not_resume'], $linkurl);
	$db->query("INSERT INTO {$table_apply} (jobid,resumeid,job_username,apply_username,applytime,status) VALUES ('$itemid','$resumeid','$item[username]','$_username','$DT_TIME','1')");
	$db->query("UPDATE {$table} SET apply=apply+1 WHERE itemid=$itemid");
	$resumeurl = $MOD['linkurl'].$resume['linkurl'];
	send_message($item['username'], lang($L['apply_msg_title'], array(dsubstr($item['title'], 20, '...'))), lang($L['apply_msg_content'], array($resumeurl)));
	message($L['apply_success'], $linkurl);
} else {
	$lists = array();
	$result = $db->query("SELECT * FROM {$table_resume} WHERE username='$_username' AND status=3 AND open=3 ORDER BY edittime DESC");
	while($r = $db->fetch_array($result)) {
		$r['mobile'] = $MOD['mobile'].$r['linkurl'];
		$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
		$lists[] = $r;
	}
	if($lists) {
		$head_title = $L['apply_title'].$DT['seo_delimiter'].$item['title'].$DT['seo_delimiter'].$MOD['name'];
	} else {
		message($L['make_resume'], ($DT_PC ? $MODULE[2]['linkurl'] : $MODULE[2]['mobile']).$DT['file_my'].'?action=add&job=resume&mid='.$moduleid);
	}
}
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	$back_link = $linkurl;
}
include template($MOD['template_apply'] ? $MOD['template_apply'] : 'apply', $module);
?>