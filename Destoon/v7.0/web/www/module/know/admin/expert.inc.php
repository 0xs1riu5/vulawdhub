<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/expert.class.php';
$do = new expert();
$menus = array (
    array('添加专家', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('专家列表', '?moduleid='.$moduleid.'&file='.$file),
);
if(in_array($action, array('', 'check'))) {
	$level = isset($level) ? intval($level) : 0;
	$sfields = array('按条件', '姓名', '会员名', '昵称', '擅长领域', '专家介绍');
	$dfields = array('title', 'title', 'username', 'passport', 'major', 'content');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '修改时间降序', '修改时间升序', '浏览人气降序', '浏览人气升序', '被提问数降序', '被提问数升序', '回答次数降序', '回答次数升序', '被采纳数降序', '被采纳数升序');
	$dorder  = array('addtime DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC', 'hits DESC', 'hits ASC', 'ask DESC', 'ask ASC', 'answer DESC', 'answer ASC', 'best DESC', 'best ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);
	$level_select = level_select('level', '级别', $level);

	$condition = '';
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($level) $condition .= " AND level=$level";
}
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
			$content = '';
			$addtime = timetodate($DT_TIME);
			$menuid = 0;
			include tpl('expert_edit', $module);
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
			$menuid = 1;
			include tpl('expert_edit', $module);
		}
	break;
	case 'delete':
		$itemid or msg('请选择专家');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	default:
		$lists = $do->get_list('1 '.$condition, $dorder[$order]);
		include tpl('expert', $module);
	break;
}
?>