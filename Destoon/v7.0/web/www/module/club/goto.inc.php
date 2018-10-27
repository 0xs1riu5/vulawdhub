<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($action == 'master') {
	$name = isset($name) ? urldecode($name) : '';
	if($name && $catid) {
		$T = $db->get_one("SELECT manager FROM {$table_group} WHERE itemid=$catid");
		if($T && $T['manager'] && strpos($T['manager'], $name) !== false) {
			$username = get_user($name, 'passport', 'username');
			if($username) dheader(userurl($username));
		}
	}
	dheader($DT_PC ? $MOD['linkurl'] : $MOD['mobile']);
} else {
	$itemid or dheader($DT_PC ? $MOD['linkurl'] : $MOD['mobile']);
	$R = $db->get_one("SELECT * FROM {$table_reply} WHERE itemid=$itemid");
	$R or dheader($DT_PC ? $MOD['linkurl'] : $MOD['mobile']);
	$tid = $R['tid'];
	$T = $db->get_one("SELECT * FROM {$table} WHERE itemid=$tid");
	$T or dheader($DT_PC ? $MOD['linkurl'] : $MOD['mobile']);
	if($MOD['reply_pagesize']) $pagesize = $MOD['reply_pagesize'];
	if($R['fid']) {
		$page = ceil($R['fid']/$pagesize);
	} else {
		$page = ceil(($T['reply']+1)/$pagesize);
	}
	if($page == 1) {
		$linkurl = $T['linkurl'];
	} else {
		$linkurl = itemurl($T, $page);
	}
	dheader(($DT_PC ? $MOD['linkurl'] : $MOD['mobile']).$linkurl.'#R'.$itemid);
}
?>