<?php
$moduleid = 2;
require '../../common.inc.php';
require DT_ROOT.'/include/mobile.inc.php';
if($DT_BOT) dhttp(403);
if($mid) {
	$group_editor = $MG['editor'];
	in_array($group_editor, array('Default', 'Destoon', 'Simple', 'Basic')) or $group_editor = 'Destoon';
	$MST = cache_read('module-2.php');
	isset($admin_user) or $admin_user = false;
	$show_oauth = $MST['oauth'];
	$viewport = 0;
	if(!$_userid) $action = 'add';//Guest
	if($MG['type'] && !$_edittime && $action == 'add') dheader('edit.php?tab=2');
	if($_groupid > 4 && (($MST['vemail'] && $MG['vemail']) || ($MST['vmobile'] && $MG['vmobile']) || ($MST['vtruename'] && $MG['vtruename']) || ($MST['vcompany'] && $MG['vcompany']) || ($MST['deposit'] && $MG['vdeposit']))) {
		$V = $db->get_one("SELECT vemail,vmobile,vtruename,vcompany,deposit FROM {$DT_PRE}member WHERE userid=$_userid");
		if($MST['vemail'] && $MG['vemail']) {
			$V['vemail'] or dheader('validate.php?action=email&itemid=1');
		}
		if($MST['vmobile'] && $MG['vmobile']) {
			$V['vmobile'] or dheader('validate.php?action=mobile&itemid=1');
		}
		if($MST['vtruename'] && $MG['vtruename']) {
			$V['vtruename'] or dheader('validate.php?action=truename&itemid=1');
		}
		if($MST['vcompany'] && $MG['vcompany']) {
			$V['vcompany'] or dheader('validate.php?action=company&itemid=1');
		}
		if($MST['deposit'] && $MG['vdeposit']) {
			$V['deposit'] > 99 or dheader('deposit.php?action=add&itemid=1');
		}
	}
	if($_groupid > 4 && $MG['vweixin']) {
		$W = $db->get_one("SELECT subscribe FROM {$DT_PRE}weixin_user WHERE username='$_username'");
		($W && $W['subscribe']) or dheader('weixin.php?itemid=1');
	}
	if($_credit < 0 && $MST['credit_less'] && $action == 'add') dheader('credit.php?action=less');
	if($submit) {
		check_post() or dalert($L['bad_data']);//safe
		$BANWORD = cache_read('banword.php');
		if($BANWORD && isset($post)) {
			$keys = array('title', 'tag', 'introduce', 'content');
			foreach($keys as $v) {
				if(isset($post[$v])) $post[$v] = banword($BANWORD, $post[$v]);
			}
		}
	}

	$MYMODS = array();
	if(isset($MG['moduleids']) && $MG['moduleids']) {
		$MYMODS = explode(',', $MG['moduleids']);
	}
	if($MYMODS) {
		foreach($MYMODS as $k=>$v) {
			$v = abs($v);
			if(!isset($MODULE[$v])) unset($MYMODS[$k]);
		}
	}
	$MENUMODS = $MYMODS;
	$vid = $mid;
	if($mid == 9 && isset($resume)) $vid = -9;
	if(!$MYMODS || !in_array($vid, $MYMODS)) dheader($MODULE[2]['linkurl'].$DT['file_my']);

	$IMVIP = isset($MG['vip']) && $MG['vip']; 
	$moduleid = $mid;
	$module = $MODULE[$moduleid]['module'];
	if(!$module) message();
	$MOD = cache_read('module-'.$moduleid.'.php');
	$my_file = DT_ROOT.'/module/'.$module.'/my.inc.php';
	if(is_file($my_file)) {
		require $my_file;
	} else {
		dheader($MODULE[2]['linkurl']);
	}
} else {
	require DT_ROOT.'/module/'.$module.'/my.inc.php';
}
?>