<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：
 * $author：lucks
 */
define('IN_BLUE', true);

require_once dirname(__FILE__) . '/include/common.inc.php';
require BLUE_ROOT . 'include/ip.class.php';
$ip = new ip();
$bannedip = !empty($_REQUEST['ip']) ? trim($_REQUEST['ip']) : '';

$act = !empty($_REQUEST['act']) ? trim($_REQUEST['act']) : 'list';

if ($act == 'list') {
	$ip_arr = $ip->list_ip();
	template_assign(array('current_act', 'bannedip_arr'), array('禁止IP列表', $ip_arr));
	$smarty->display('ipbanned.htm');
}

elseif ($act == 'add') {
	template_assign(array('current_act', 'act'), array('添加禁止IP', $act));
	$smarty->display('ipbanned_info.htm');
}

elseif ($act == 'do_add') {
	$exp = !empty($_POST['exp']) ? trim($_POST['exp']) : '';
	if ($ip->check_exists($bannedip)) {
		showmsg('您已禁止该IP', 'ipbanned.php');
	} else {
		$ip->add_ip($bannedip, $exp);
	}
	showmsg('禁止该IP成功', 'ipbanned.php');
}

elseif ($act == 'edit') {
	$bannedip_info = $ip->get_ip($bannedip);
	template_assign(array('current_act', 'act', 'bannedip_info'), array('编辑禁止IP',$act, $bannedip_info));
	$smarty->display('ipbanned_info.htm');
}

elseif ($act == 'do_edit') {
	$old_ip = !empty($_POST['old_ip']) ? trim($_POST['old_ip']) : '';
	$exp = !empty($_POST['exp']) ? trim($_POST['exp']) : '';

	if($ip->edit_ip($old_ip, $bannedip, $exp)) {
		showmsg('恭喜您编辑禁止IP成功', 'ipbanned.php', true);
	} else {
		showmsg('编辑禁止IP出错', '', true);
	}
}

elseif ($act == 'del') {
	if ($ip->del_ip($bannedip)) {
		showmsg('恭喜您，删除禁止IP成功', 'ipbanned.php', true);
	} else {
		showmsg('删除禁止IP失败', '', true);
	}

}

 ?>