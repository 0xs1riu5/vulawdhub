<?php
defined('DT_ADMIN') or exit('Access Denied');
$gid = isset($gid) ? intval($gid) : 0;
require DT_ROOT.'/module/'.$module.'/fans.class.php';
$do = new fans();
$menus = array (
    array('粉丝列表', '?moduleid='.$moduleid.'&file='.$file.'&gid='.$gid),
    array('待审核', '?moduleid='.$moduleid.'&file='.$file.'&gid='.$gid.'&action=check'),
    array('未通过', '?moduleid='.$moduleid.'&file='.$file.'&gid='.$gid.'&action=reject'),
    array('回收站', '?moduleid='.$moduleid.'&file='.$file.'&gid='.$gid.'&action=recycle'),
);
if(in_array($action, array('', 'check', 'reject', 'recycle'))) {
	$sfields = array('加入理由', '会员名', '昵称');
	$dfields = array('content', 'username', 'passport');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序');
	$dorder  = array('itemid desc', 'addtime DESC', 'addtime ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($gid) $condition .= " AND gid='$gid'";
}
switch($action) {
	case 'delete':
		$itemid or msg('请选择粉丝');
		isset($recycle) ? $do->recycle($itemid) : $do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'cancel':
		$itemid or msg('请选择粉丝');
		$do->check($itemid, 2);
		dmsg('取消成功', $forward);
	break;
	case 'restore':
		$itemid or msg('请选择粉丝');
		$do->restore($itemid);
		dmsg('还原成功', $forward);
	break;
	case 'clear':
		$do->clear();
		dmsg('清空成功', $forward);
	break;
	case 'recycle':
		$lists = $do->get_list('status=0'.$condition, $dorder[$order]);
		$menuid = 3;
		include tpl('fans', $module);
	break;
	case 'reject':
		if($itemid && !$psize) {
			$do->reject($itemid);
			dmsg('拒绝成功', $forward);
		} else {
			$lists = $do->get_list('status=1'.$condition, $dorder[$order]);
			$menuid = 2;
			include tpl('fans', $module);
		}
	break;
	case 'check':
		if($itemid) {
			$do->check($itemid);
			dmsg('审核成功', $forward);
		} else {
			$lists = $do->get_list('status=2'.$condition, $dorder[$order]);
			$menuid = 1;
			include tpl('fans', $module);
		}
	break;
	default:
		$lists = $do->get_list('status=3'.$condition, $dorder[$order]);
		$menuid = 0;
		include tpl('fans', $module);
	break;
}
?>