<?php
defined('DT_ADMIN') or exit('Access Denied');
$gid = isset($gid) ? intval($gid) : 0;
require DT_ROOT.'/module/'.$module.'/manage.class.php';
$do = new manage();
$menus = array (
    array('记录列表', '?moduleid='.$moduleid.'&file='.$file.'&gid='.$gid),
    array('记录清理', '?moduleid='.$moduleid.'&file='.$file.'&action=clear', 'onclick="if(!confirm(\'为了系统安全,系统仅删除30天之前的记录\n此操作不可撤销，请谨慎操作\')) return false"'),
);
switch($action) {
	case 'clear':
		$time = $today_endtime - 30*86400;
		$db->query("DELETE FROM {$table_manage} WHERE addtime<$time");
		dmsg('清理成功', $forward);
	break;
	default:
		$sfields = array('主题/回复', '操作原因', '操作内容', '操作人');
		$dfields = array('title', 'reason', 'content', 'username');

		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$typeid = isset($typeid) ? intval($typeid) : 0;
		$tid = isset($tid) ? intval($tid) : 0;
		$rid = isset($rid) ? intval($rid) : 0;
		$message = isset($message) ? intval($message) : -1;

		$fields_select = dselect($sfields, 'fields', '', $fields);

		$condition = '';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($typeid) $condition .= " AND typeid='$typeid'";
		if($gid) $condition .= " AND gid='$gid'";
		if($tid) $condition .= " AND tid='$tid'";
		if($rid) $condition .= " AND rid='$rid'";
		if($message > -1) $condition .= " AND message='$message'";
		$lists = $do->get_list('1'.$condition);
		$menuid = 0;
		include tpl('manage', $module);
	break;
}
?>