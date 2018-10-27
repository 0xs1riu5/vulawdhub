<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('在线会员', '?moduleid='.$moduleid.'&file=online'),
    array('在线管理员', '?moduleid='.$moduleid.'&file=online&action=admin'),
);
if($action == 'admin') {
	$DT['admin_online'] or msg('系统未开启此功能', '?file=setting&kw='.urlencode('后台在线').'#high');
	$lastime = $DT_TIME - $DT['online'];
	$db->query("DELETE FROM {$DT_PRE}admin_online WHERE lasttime<$lastime");
	$sid = session_id();
	$lists = array();
	$result = $db->query("SELECT * FROM {$DT_PRE}admin_online ORDER BY lasttime DESC");
	while($r = $db->fetch_array($result)) {
		$r['lasttime'] = timetodate($r['lasttime'], 'H:i:s');
		$lists[] = $r;
	}
	include tpl('online_admin', $module);
} else {
	$sfields = array('按条件', '会员名', '会员ID');
	$dfields = array('username', 'username', 'userid');
	$sorder  = array('结果排序方式', '访问时间降序', '访问时间升序', '会员ID降序', '会员ID升序');
	$dorder  = array('lasttime DESC', 'lasttime DESC', 'lasttime ASC', 'userid DESC', 'userid ASC');
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	$online = isset($online) ? intval($online) : 2;
	isset($order) && isset($dorder[$order]) or $order = 0;
	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);
	$condition = '1';
	if($keyword) $condition .= " AND $dfields[$fields]='$kw'";
	if($mid) $condition .= " AND moduleid=$mid";
	if($online < 2) $condition .= " AND online=$online";
	$order = $dorder[$order];
	$lastime = $DT_TIME - $DT['online'];
	$db->query("DELETE FROM {$DT_PRE}online WHERE lasttime<$lastime");
	if($page > 1 && $sum) {
		$items = $sum;
	} else {
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}online WHERE $condition");
		$items = $r['num'];
	}
	$pages = pages($items, $page, $pagesize);
	$lists = array();
	$result = $db->query("SELECT * FROM {$DT_PRE}online WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
	while($r = $db->fetch_array($result)) {
		$r['lasttime'] = timetodate($r['lasttime'], 'H:i:s');
		$lists[] = $r;
	}
	include tpl('online', $module);
}
?>