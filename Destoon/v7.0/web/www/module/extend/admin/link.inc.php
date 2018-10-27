<?php
defined('DT_ADMIN') or exit('Access Denied');
$TYPE = get_type('link', 1);
require DT_ROOT.'/module/'.$module.'/link.class.php';
$do = new dlink();
$menus = array (
    array('添加链接', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('链接列表', '?moduleid='.$moduleid.'&file='.$file),
    array('审核链接', '?moduleid='.$moduleid.'&file='.$file.'&action=check'),
    array('链接分类', 'javascript:Dwidget(\'?file=type&item='.$file.'\', \'链接分类\');'),
    array('模块设置', 'javascript:Dwidget(\'?moduleid='.$moduleid.'&file=setting&action='.$file.'\', \'模块设置\');'),
);
if($_catids || $_areaids) require DT_ROOT.'/admin/admin_check.inc.php';
$this_forward = '?moduleid='.$moduleid.'&file='.$file;
if(in_array($action, array('', 'check'))) {
	$sfields = array('按条件', '网站名称', '网站地址', '网站介绍');
	$dfields = array('title','title','linkurl','introduce');
	$sorder  = array('结果排序方式', '更新时间降序', '更新时间升序', '是否文字降序', '是否文字升序', '是否推荐降序', '是否推荐升序');
	$dorder  = array('listorder DESC,itemid DESC', 'edittime DESC', 'eidttime ASC', 'type DESC', 'type ASC', 'elite DESC', 'elite ASC');
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	$stype  = array('类型', '文字', 'LOGO');
	$dtype  = array('0', '1', '2');
	$level = isset($level) ? intval($level) : 0;
	$typeid = isset($typeid) ? intval($typeid) : 0;
	$type = isset($type) ? intval($type) : 0;
	isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
	$type_select = type_select('link', 1, 'typeid', '请选择分类', $typeid);
	$order_select  = dselect($sorder, 'order', '', $order);
	$level_select = level_select('level', '级别', $level);
	$_type_select  = dselect($stype, 'type', '', $type);
	$condition = '';
	if($_areaids) $condition .= " AND areaid IN (".$_areaids.")";//CITY
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($typeid) $condition .= " AND typeid IN (".type_child($typeid, $TYPE).")";
	if($type) $condition .= $type == 1 ? " AND thumb=''" : " AND thumb<>''";
	if($level) $condition .= " AND level=$level";
	if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
}
switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&typeid='.$post['typeid']);
			} else {
				msg($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				isset($$v) or $$v = '';
			}
			$linkurl = 'http://';
			$status = 3;
			$addtime = timetodate($DT_TIME);
			$typeid = 0;
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
			$do->check($itemid);
			dmsg('审核成功', $forward);
		} else {
			$menuid = 2;
			$lists = $do->get_list("status=2 AND username=''".$condition, $dorder[$order]);
			include tpl('link', $module);
		}
	break;
	case 'order':
		$do->order($listorder); 
		dmsg('排序成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择链接');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'level':
		$itemid or msg('请选择链接');
		$level = intval($level);
		$do->level($itemid, $level);
		dmsg('级别设置成功', $forward);
	break;
	default:
		$menuid = 1;
		$lists = $do->get_list("status=3 AND username=''".$condition, $dorder[$order]);
		include tpl('link', $module);
	break;
}
?>