<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/honor.class.php';
$do = new honor();
$menus = array (
    array('添加证书', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('证书列表', '?moduleid='.$moduleid.'&file='.$file),
    array('审核证书', '?moduleid='.$moduleid.'&file='.$file.'&action=check'),
    array('过期证书', '?moduleid='.$moduleid.'&file='.$file.'&action=expire'),
    array('未通过证书', '?moduleid='.$moduleid.'&file='.$file.'&action=reject'),
    array('回收站', '?moduleid='.$moduleid.'&file='.$file.'&action=recycle'),
);
if(in_array($action, array('', 'check', 'expire', 'reject', 'recycle'))) {
	$sfields = array('按条件', '证书名称', '发证机构', '会员名');
	$dfields = array('title', 'title', 'authority', 'username');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '修改时间降序', '修改时间升序', '发证时间降序', '发证时间升序', '到期时间降序', '到期时间升序');
	$dorder  = array('addtime DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC', 'fromtime DESC', 'fromtime ASC', 'totime DESC', 'totime ASC');

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
				dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&catid='.$post['catid']);
			} else {
				msg($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				isset($$v) or $$v = '';
			}
			$content = '';
			$username = $_username;
			$status = 3;
			$addtime = timetodate($DT_TIME);
			$menuid = 0;
			include tpl('honor_edit', $module);
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
			$addtime = timetodate($addtime);
			$fromtime = timetodate($fromtime, 3);
			$totime = $totime ? timetodate($totime, 3) : '';
			$menuon = array('5', '4', '2', '1', '3');
			$menuid = $menuon[$status];
			include tpl('honor_edit', $module);
		}
	break;
	case 'delete':
		$itemid or msg('请选择证书');
		isset($recycle) ? $do->recycle($itemid) : $do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'restore':
		$itemid or msg('请选择证书');
		$do->restore($itemid);
		dmsg('还原成功', $forward);
	break;
	case 'clear':
		$do->clear();
		dmsg('清空成功', $forward);
	break;
	case 'recycle':
		$lists = $do->get_list('status=0'.$condition, $dorder[$order]);
		$menuid = 5;
		include tpl('honor', $module);
	break;
	case 'reject':
		if($itemid && !$psize) {
			$do->reject($itemid);
			dmsg('拒绝成功', $forward);
		} else {
			$lists = $do->get_list('status=1'.$condition, $dorder[$order]);
			$menuid = 4;
			include tpl('honor', $module);
		}
	break;
	case 'expire':
		if(isset($refresh)) {
			if(isset($delete)) {
				$days = isset($days) ? intval($days) : 0;
				$days or msg('请填写天数');
				$do->clear("status=4 AND totime>0 AND totime<$DT_TIME-$days*86400");
				dmsg('删除成功', $forward);
			} else {
				$do->expire();
				dmsg('刷新成功', $forward);
			}
		} else {
			$lists = $do->get_list('status=4'.$condition);
			$menuid = 3;
			include tpl('honor', $module);
		}
	break;
	case 'check':
		if($itemid && !$psize) {
			$do->check($itemid);
			dmsg('审核成功', $forward);
		} else {
			$lists = $do->get_list('status=2'.$condition, $dorder[$order]);
			$menuid = 2;
			include tpl('honor', $module);
		}
	break;
	default:
		$lists = $do->get_list('status=3'.$condition, $dorder[$order]);
		$menuid = 1;
		include tpl('honor', $module);
	break;
}
?>