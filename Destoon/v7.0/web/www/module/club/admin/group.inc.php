<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('添加商圈', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('商圈列表', '?moduleid='.$moduleid.'&file='.$file),
    array('待审核', '?moduleid='.$moduleid.'&file='.$file.'&action=check'),
    array('未通过', '?moduleid='.$moduleid.'&file='.$file.'&action=reject'),
    array('回收站', '?moduleid='.$moduleid.'&file='.$file.'&action=recycle'),
);
$MOD['level'] = '';
if(in_array($action, array('', 'check', 'reject', 'recycle'))) {
	$level = isset($level) ? intval($level) : 0;
	$sfields = array('按条件', '商圈名称', '商圈介绍', '创建理由', '创始人', '版主', '编辑', '静态目录');
	$dfields = array('title', 'title', 'content', 'reason', 'username', 'manager', 'editor', 'filepath');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '修改时间降序', '修改时间升序', '帖子数量降序', '帖子数量升序', '粉丝数量降序', '粉丝数量升序');
	$dorder  = array('addtime DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC', 'post DESC', 'post ASC', 'fans DESC', 'fans ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);
	$level_select = level_select('level', '级别', $level, 'all');

	$condition = '';
	if($_childs) $condition .= " AND catid IN (".$_childs.")";//CATE
	if($_areaids) $condition .= " AND areaid IN (".$_areaids.")";//CITY
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($catid) $condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
	if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	if($level) $condition .= $level > 9 ? " AND level>0" : " AND level=$level";
}
require DT_ROOT.'/module/'.$module.'/group.class.php';
$do = new group();
switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file);
			} else {
				msg($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				isset($$v) or $$v = '';
			}
			$status = 3;
			$content = '';
			$addtime = timetodate($DT_TIME);
			$menuid = 0;
			include tpl('group_edit', $module);
		}
	break;
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		$item = $do->get_one();
		if($submit) {
			if($MOD['list_html']) {
				if(preg_match("/^[0-9a-z_\-\/]+$/i", $post['filepath'])) {
					$t = $db->get_one("SELECT itemid FROM {$table_group} WHERE filepath='$post[filepath]' AND itemid<>$itemid");
					if($t) msg('静态目录有重复');
				} else {
					msg('静态目录规则错误');
				}
			}
			if($do->pass($post)) {
				$do->edit($post);
				if($post['catid'] != $item['catid']) $db->query("UPDATE {$table} SET catid=$post[catid] WHERE gid=$itemid");
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($item);
			$addtime = timetodate($addtime);
			$menuid = 1;
			include tpl('group_edit', $module);
		}
	break;
	case 'update':
		is_array($itemid) or msg('请选择商圈');
		foreach($itemid as $v) {
			$do->update($v);
		}
		dmsg('更新成功', $forward);
	break;
	case 'tohtml':
		is_array($itemid) or msg('请选择商圈');
		$html_itemids = $itemid;
		foreach($html_itemids as $itemid) {
			tohtml('group', $module);
		}
		dmsg('生成成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择商圈');
		isset($recycle) ? $do->recycle($itemid) : $do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'restore':
		$itemid or msg('请选择商圈');
		$do->restore($itemid);
		dmsg('还原成功', $forward);
	break;
	case 'clear':
		$do->clear();
		dmsg('清空成功', $forward);
	break;
	case 'level':
		$itemid or msg('请选择商圈');
		$level = intval($level);
		$do->level($itemid, $level);
		dmsg('级别设置成功', $forward);
	break;
	case 'recycle':
		$lists = $do->get_list('status=0'.$condition, $dorder[$order]);
		$menuid = 4;
		include tpl('group', $module);
	break;
	case 'reject':
		if($itemid && !$psize) {
			$do->reject($itemid);
			dmsg('拒绝成功', $forward);
		} else {
			$lists = $do->get_list('status=1'.$condition, $dorder[$order]);
			$menuid = 3;
			include tpl('group', $module);
		}
	break;
	case 'check':
		if($itemid && !$psize) {
			$do->check($itemid);
			dmsg('审核成功', $forward);
		} else {
			$lists = $do->get_list('status=2'.$condition, $dorder[$order]);
			$menuid = 2;
			include tpl('group', $module);
		}
	break;
	default:
		$lists = $do->get_list('status=3'.$condition, $dorder[$order]);
		$menuid = 1;
		include tpl('group', $module);
	break;
}
?>