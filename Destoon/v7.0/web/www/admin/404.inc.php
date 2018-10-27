<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('404日志', '?file='.$file),
    array('日志清理', '?file='.$file.'&action=clear', 'onclick="if(!confirm(\'为了系统安全,系统仅删除30天之前的日志\')) return false"'),
);
switch($action) {
	case 'clear':
		$time = $today_endtime - 30*86400;
		$db->query("DELETE FROM {$DT_PRE}404 WHERE addtime<$time");
		dmsg('清理成功', '?file='.$file);
	break;
	case 'delete':
		$itemid or msg('请选择记录');
		$ids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$DT_PRE}404 WHERE itemid IN ($ids)");
		dmsg('删除成功', $forward);
	break;
	default:
		include DT_ROOT.'/file/config/robot.inc.php';
		$sfields = array('按条件', '网址', '来源', '搜索引擎', '会员', 'IP');
		$dfields = array('url', 'url', 'refer', 'robot', 'username', 'ip');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$ip = isset($ip) ? $ip : '';
		$robot = isset($robot) ? $robot : '';
		$username = isset($username) ? $username : '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND addtime>=$fromtime";
		if($totime) $condition .= " AND addtime<=$totime";
		if($ip) $condition .= " AND ip='$ip'";
		if($robot) $condition .= " AND robot='$robot'";
		if($username) $condition .= " AND username='$username'";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}404 WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}404 WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$tmp = parse_url($r['url']);
			$r['addtime'] = timetodate($r['addtime'], 6);
			$lists[] = $r;
		}
		include tpl('404');
	break;
}
?>