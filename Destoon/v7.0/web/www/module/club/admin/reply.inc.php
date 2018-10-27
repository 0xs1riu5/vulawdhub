<?php
defined('DT_ADMIN') or exit('Access Denied');
$tid = isset($tid) ? intval($tid) : 0;
$gid = isset($gid) ? intval($gid) : 0;
require DT_ROOT.'/module/'.$module.'/reply.class.php';
$do = new reply();
$menus = array (
    array('回复列表', '?moduleid='.$moduleid.'&file='.$file.'&tid='.$tid),
    array('待审核', '?moduleid='.$moduleid.'&file='.$file.'&tid='.$tid.'&action=check'),
    array('未通过', '?moduleid='.$moduleid.'&file='.$file.'&tid='.$tid.'&action=reject'),
    array('回收站', '?moduleid='.$moduleid.'&file='.$file.'&tid='.$tid.'&action=recycle'),
);
if(in_array($action, array('', 'check', 'reject', 'recycle'))) {
	$sfields = array('内容', '会员名', '昵称', '编辑', 'IP');
	$dfields = array('content', 'username', 'passport', 'editor', 'ip');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序');
	$dorder  = array('itemid desc', 'addtime DESC', 'addtime ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;
	isset($ip) or $ip = '';
	(isset($fromdate) && is_date($fromdate)) or $fromdate = '';
	$fromtime = $fromdate ? strtotime($fromdate.' 0:0:0') : 0;
	(isset($todate) && is_date($todate)) or $todate = '';
	$totime = $todate ? strtotime($todate.' 23:59:59') : 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($keyword) $condition .= in_array($dfields[$fields], array('gid', 'itemid', 'ip')) ? " AND $dfields[$fields]='$kw'" : " AND $dfields[$fields] LIKE '%$keyword%'";
	if($tid) $condition .= " AND tid='$tid'";
	if($gid) $condition .= " AND gid='$gid'";
	if($ip) $condition .= " AND ip='$ip'";
	if($fromtime) $condition .= " AND addtime>=$fromtime";
	if($totime) $condition .= " AND addtime<=$totime";
}
switch($action) {
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
			$addtime = timetodate($addtime);
			include tpl('reply_edit', $module);
		}
	break;
	case 'delete':
		$itemid or msg('请选择回复');		
		isset($recycle) ? $do->recycle($itemid) : $do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'recycle':
		$lists = $do->get_list('status=0'.$condition, $dorder[$order]);
		$menuid = 3;
		include tpl('reply', $module);
	break;
	case 'reject':
		if($itemid && !$psize) {
			$do->reject($itemid);
			dmsg('拒绝成功', $forward);
		} else {
			$lists = $do->get_list('status=1'.$condition, $dorder[$order]);
			$menuid = 2;
			include tpl('reply', $module);
		}
	break;
	case 'check':
		if($itemid) {
			$do->check($itemid, 3);
			dmsg('审核成功', $forward);
		} else {
			$lists = $do->get_list('status=2'.$condition, $dorder[$order]);
			$menuid = 1;
			include tpl('reply', $module);
		}
	break;
	case 'cancel':
		$itemid or msg('请选择回复');
		$do->check($itemid, 2);
		dmsg('取消成功', $forward);
	break;
	default:
		$lists = $do->get_list('status=3'.$condition, $dorder[$order]);
		$menuid = 0;
		include tpl('reply', $module);
	break;
}
?>