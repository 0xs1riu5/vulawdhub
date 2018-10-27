<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/link.class.php';
$do = new dlink();
$menus = array (
    array('添加链接', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('链接列表', '?moduleid='.$moduleid.'&file='.$file),
    array('审核链接', '?moduleid='.$moduleid.'&file='.$file.'&action=check'),
);
if(in_array($action, array('', 'check'))) {
	$sfields = array('按条件', '网站', '链接', '会员名');
	$dfields = array('title', 'title', 'linkurl', 'username');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '修改时间降序', '修改时间升序');
	$dorder  = array('addtime DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
}
switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action);
			} else {
				msg($do->errmsg);
			}
		} else {
			$title = $linkurl = $style = $username = '';
			$status = 3;
			$menuid = 0;
			include tpl('link_edit', $module);
		}
	break;
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			if($do->pass($post)) {
				$do->edit($post);
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_one());
			$menuid = $status == 3 ? 1 : 2;
			include tpl('link_edit', $module);
		}
	break;
	case 'check':
		if($itemid) {
			//$status = $status == 3 ? 3 : 2;
			$status = 3;
			$do->check($itemid, $status);
			dmsg($status == 3 ? '审核成功' : '取消成功', $forward);
		} else {
			$lists = $do->get_list("username<>'' AND status=2".$condition, $dorder[$order]);
			$menuid = 2;
			include tpl('link', $module);
		}
	break;
	case 'delete':
		$itemid or msg('请选择链接');
		isset($recycle) ? $do->recycle($itemid) : $do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	default:
		$lists = $do->get_list("username<>'' AND status=3".$condition, $dorder[$order]);
		$menuid = 1;
		include tpl('link', $module);
	break;
}
?>