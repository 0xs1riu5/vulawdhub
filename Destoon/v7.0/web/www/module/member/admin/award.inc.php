<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('信息打赏', '?moduleid='.$moduleid.'&file='.$file),
    array('统计报表', '?moduleid='.$moduleid.'&file='.$file.'&action=stats'),
);
$table = $DT_PRE.'finance_award';
switch($action) {
	case 'stats':
		$year = isset($year) ? intval($year) : date('Y', $DT_TIME);
		$year or $year = date('Y', $DT_TIME);
		$month = isset($month) ? intval($month) : date('n', $DT_TIME);
		$chart_data = '';
		$T1 = $T2 = 0;
		if($month) {
			$L = date('t', strtotime($year.'-'.$month.'-01'));
			for($i = 1; $i <= $L; $i++) {
				if($i > 1) $chart_data .= '\n';
				$chart_data .= $i;
				$F = strtotime($year.'-'.$month.'-'.$i.' 00:00:00');
				$T = strtotime($year.'-'.$month.'-'.$i.' 23:59:59');
				$t = $db->get_one("SELECT SUM(`fee`) AS num FROM {$table} WHERE paytime>=$F AND paytime<=$T");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T1 += $num;
			}
			$title = $year.'年'.$month.'月会员支付统计报表';
		} else {
			for($i = 1; $i < 13; $i++) {
				if($i > 1) $chart_data .= '\n';
				$chart_data .= $i;
				$F = strtotime($year.'-'.$i.'-01 00:00:00');
				$T = strtotime($year.'-'.$i.'-'.date('t', $F).' 23:59:59');
				$t = $db->get_one("SELECT SUM(`fee`) AS num FROM {$table} WHERE paytime>=$F AND paytime<=$T");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T1 += $num;
			}
			$title = $year.'年会员支付统计报表';
		}
		include tpl('award_stats', $module);
	break;
	case 'delete':
		$itemid or msg('未选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$table} WHERE itemid IN ($itemids)");
		dmsg('删除成功', $forward);
	break;
	default:
		$sfields = array('按条件', '标题', '会员名', '金额', 'IP');
		$dfields = array('title', 'title', 'fee', 'ip');
		$sorder  = array('排序方式', '金额降序', '金额升序', '时间降序', '时间升序');
		$dorder  = array('itemid DESC', 'fee DESC', 'fee ASC', 'paytime DESC', 'paytime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		(isset($username) && check_name($username)) or $username = '';
		isset($tid) or $tid = '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		isset($currency) or $currency = '';
		isset($minamount) or $minamount = '';
		isset($maxamount) or $maxamount = '';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$module_select = module_select('mid', '模块', $mid);
		$order_select = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND paytime>=$fromtime";
		if($totime) $condition .= " AND paytime<=$totime";
		if($mid) $condition .= " AND mid=$mid";
		if($username) $condition .= " AND username='$username'";
		if($itemid) $condition .= " AND itemid=$itemid";
		if($minamount != '') $condition .= " AND fee>=$minamount";
		if($maxamount != '') $condition .= " AND fee<=$maxamount";
		if($tid) $condition .= " AND tid=$tid";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$lists = array();
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY $dorder[$order] LIMIT $offset,$pagesize");
		$fee = 0;
		while($r = $db->fetch_array($result)) {
			$r['paytime'] = timetodate($r['paytime'], 5);
			$fee += $r['fee'];
			$lists[] = $r;
		}
		include tpl('award', $module);
	break;
}
?>