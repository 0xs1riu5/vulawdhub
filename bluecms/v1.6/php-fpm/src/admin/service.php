<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：card.php
 * $author：lucks
 */
define('IN_BLUE', true);
require_once(dirname(__FILE__) . '/include/common.inc.php');

$act = $_REQUEST['act'] ? trim($_REQUEST['act']) : 'list';
$id = $_REQUEST['id'] ? intval($_REQUEST['id']) : '';
if($act == 'list'){
	$price_list = $db->getall("SELECT * FROM ".table('service')." ORDER BY id");
	template_assign(array('current_act', 'price_list'), array('服务价格表', $price_list));
	$smarty->display('service.htm');
}

elseif($act == 'edit'){
	if(empty($id)){
		return false;
	}
	$service = $db->getone("SELECT * FROM ".table('service')." WHERE id=$id");
	template_assign(array('act', 'current_act', 'service'), array($act, '编辑一种服务价格', $service));
	$smarty->display('service_info.htm');
}

elseif($act == 'do_edit'){
	if(empty($id)){
		return false;
	}
	$price = $_POST['price'];
	$db->query("UPDATE ".table('service')." SET price='$price' WHERE id=$id");
	showmsg('调整服务价格成功', 'service.php');
}


?>