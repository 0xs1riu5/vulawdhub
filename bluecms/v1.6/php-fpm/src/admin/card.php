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
	$card_list = $db->getall("SELECT * FROM ".table('card_type')." ORDER BY id");
	template_assign(array('current_act', 'card_list'), array('充值卡列表', $card_list));
	$smarty->display('card.htm');
}

elseif($act == 'add'){
	template_assign(array('act', 'current_act'), array($act, '添加一种充值卡'));
	$smarty->display('card_info.htm');
}

elseif($act == 'do_add'){
	$name		=	!empty($_POST['name']) ? trim($_POST['name']) : '';
	$value		=	!empty($_POST['value']) ? intval($_POST['value']) : '';
	$price		=	!empty($_POST['price']) ? intval($_POST['price']) : '';
	$is_close	=	intval($_POST['is_close']);
	if(empty($name) || empty($value) || empty($price)){
		showmsg('信息填写不完整');
	}
	$sql = "INSERT INTO ".table('card_type')." (id, name, value, price, is_close) VALUES ('', '$name', '$value', '$price', '$is_close')";
	$db->query($sql);
	showmsg('添加一种充值卡成功', 'card.php');
}

elseif($act == 'edit'){
	if(empty($id)){
		return false;
	}
	$card = $db->getone("SELECT * FROM ".table('card_type')." WHERE id=".$id);
	template_assign(array('act', 'current_act', 'card'), array($act, '编辑充值卡', $card));
	$smarty->display('card_info.htm');
}

elseif($act == 'do_edit'){
	$name		=	!empty($_POST['name']) ? trim($_POST['name']) : '';
	$value		=	!empty($_POST['value']) ? intval($_POST['value']) : '';
	$price		=	!empty($_POST['price']) ? intval($_POST['price']) : '';
	$is_close	=	intval($_POST['is_close']);
	if(empty($name) || empty($value) || empty($price)){
		showmsg('信息填写不完整');
	}
	$sql = "UPDATE ".table('card_type')." SET name='$name', value='$value', price='$price', is_close='$is_close' WHERE id=".$id;
	$db->query($sql);
	showmsg('编辑充值卡 '.$name.' 成功', 'card.php');
}
	



?>