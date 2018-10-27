<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('浏览历史', '?moduleid='.$moduleid.'&file='.$file),
    array('记录清理', '?moduleid='.$moduleid.'&file='.$file.'&action=clear', 'onclick="if(!confirm(\'为了用户体验,系统仅删除30天之前的记录\')) return false"'),
);
switch($action) {
	case 'clear':
		$time = $today_endtime - 30*86400;
		$db->query("DELETE FROM {$table_view} WHERE lasttime<$time");
		dmsg('清理成功', '?moduleid='.$moduleid.'&file='.$file);
	break;
	case 'delete':
		isset($uids) or $uids = '';
		($uids && is_array($uids)) or msg('未选择记录');
		foreach($uids as $uid) {
			$db->query("DELETE FROM {$table_view} WHERE uid='$uid'");
		}
		dmsg('删除成功', $forward);
	break;
	default:
		$itemid or $itemid = '';
		isset($seller) or $seller = '';
		isset($buyer) or $buyer = '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$condition = "1";
		if($fromtime) $condition .= " AND lastime>=$fromtime";
		if($totime) $condition .= " AND lastime<=$totime";
		if($seller) $condition .= " AND seller='$seller'";
		if($buyer) $condition .= " AND username='$buyer'";
		if($itemid) $condition .= " AND itemid=$itemid";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table_view} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$lists = $tags = $views = $ids = array();
		$result = $db->query("SELECT * FROM {$table_view} WHERE $condition ORDER BY lasttime DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$ids[] = $r['itemid'];
			$views[] = $r;
		}
		if($ids) {
			$result = $db->query("SELECT * FROM {$table} WHERE itemid IN (".implode(',', $ids).") ORDER BY addtime DESC");
			while($r = $db->fetch_array($result)) {
				if($r['status'] != 3) continue;
				$r['alt'] = $r['title'];
				$r['title'] = set_style($r['title'], $r['style']);
				$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
				$tags[$r['itemid']] = $r;
			}
			foreach($views as $v) {
				$tags[$v['itemid']]['uid'] = $v['uid'];
				$tags[$v['itemid']]['buyer'] = $v['username'];
				$tags[$v['itemid']]['lasttime'] = $v['lasttime'];
				$lists[] = $tags[$v['itemid']];
			}
		}
		include tpl('view', $module);
	break;
}
?>