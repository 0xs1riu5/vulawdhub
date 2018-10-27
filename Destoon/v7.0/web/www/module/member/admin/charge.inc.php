<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('支付记录', '?moduleid='.$moduleid.'&file='.$file),
    array('统计报表', '?moduleid='.$moduleid.'&file='.$file.'&action=stats'),
);
$PAY = cache_read('pay.php');
$PAY['card']['name'] = '充值卡';
$dstatus = array('等待支付', '支付失败', '记录作废', '支付成功', '人工审核');
$table = $DT_PRE.'finance_charge';
switch($action) {
	case 'stats':
		$year = isset($year) ? intval($year) : date('Y', $DT_TIME);
		$year or $year = date('Y', $DT_TIME);
		$month = isset($month) ? intval($month) : date('n', $DT_TIME);
		$chart_data = '';
		$T1 = $T2 = $T3 = $T4 = 0;
		if($month) {
			$L = date('t', strtotime($year.'-'.$month.'-01'));
			for($i = 1; $i <= $L; $i++) {
				if($i > 1) $chart_data .= '\n';
				$chart_data .= $i;
				$F = strtotime($year.'-'.$month.'-'.$i.' 00:00:00');
				$T = strtotime($year.'-'.$month.'-'.$i.' 23:59:59');
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE sendtime>=$F AND sendtime<=$T AND status>2");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T1 += $num;
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE sendtime>=$F AND sendtime<=$T AND status=0");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T2 += $num;
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE sendtime>=$F AND sendtime<=$T AND status=1");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T3 += $num;
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE sendtime>=$F AND sendtime<=$T AND status=2");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T4 += $num;
			}
			$title = $year.'年'.$month.'月会员支付统计报表';
		} else {
			for($i = 1; $i < 13; $i++) {
				if($i > 1) $chart_data .= '\n';
				$chart_data .= $i;
				$F = strtotime($year.'-'.$i.'-01 00:00:00');
				$T = strtotime($year.'-'.$i.'-'.date('t', $F).' 23:59:59');
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE sendtime>=$F AND sendtime<=$T AND status>2");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T1 += $num;
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE sendtime>=$F AND sendtime<=$T AND status=0");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T2 += $num;
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE sendtime>=$F AND sendtime<=$T AND status=1");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T3 += $num;
				$t = $db->get_one("SELECT SUM(`amount`) AS num FROM {$table} WHERE sendtime>=$F AND sendtime<=$T AND status=2");
				$num = $t['num'] ? dround($t['num']) : 0;
				$chart_data .= ';'.$num;
				$T4 += $num;
			}
			$title = $year.'年会员支付统计报表';
		}
		include tpl('charge_stats', $module);
	break;
	case 'check':	
		$itemid or msg('请选择记录');
		$itemid = implode(',', $itemid);
		$result = $db->query("SELECT * FROM {$table} WHERE itemid IN ($itemid) AND status<2");
		$i = 0;
		while($r = $db->fetch_array($result)) {
			$money = $r['amount'] + $r['fee'];
			money_add($r['username'], $r['amount']);
			money_record($r['username'], $r['amount'], $PAY[$r['bank']]['name'], $_username, '在线支付', '人工');
			$db->query("UPDATE {$table} SET money='$money',status=4,editor='$_username',receivetime=$DT_TIME WHERE itemid=$r[itemid]");
			$i++;
		}
		dmsg('审核成功'.$i.'条记录', $forward);
	break;
	case 'recycle':
		$itemid or msg('请选择记录');
		$itemid = implode(',', $itemid);
		$db->query("UPDATE {$table} SET status=2,editor='$_username',receivetime=$DT_TIME WHERE itemid IN ($itemid) AND status=0");
		dmsg('作废成功'.$db->affected_rows().'条记录', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择记录');
		$itemid = implode(',', $itemid);
		$db->query("DELETE FROM {$table} WHERE itemid IN ($itemid) AND status=0");
		dmsg('删除成功'.$db->affected_rows().'条记录', $forward);
	break;
	default:
		$_status = array('<span style="color:blue;">等待支付</span>', '<span style="color:red;">支付失败</span>', '<span style="color:#FF00FF;">记录作废</span>', '<span style="color:green;">支付成功</span>', '<span style="color:green;">人工审核</span>');
		$sfields = array('按条件', '会员名', '支付金额', '手续费', '实收金额', '备注', '操作人');
		$dfields = array('username', 'username', 'amount', 'fee', 'money', 'note', 'editor');
		$sorder  = array('结果排序方式', '支付金额降序', '支付金额升序', '手续费降序', '手续费升序', '实收金额降序', '实收金额升序', '下单时间降序', '下单时间升序', '支付时间降序', '支付时间升序');
		$dorder  = array('itemid DESC', 'amount DESC', 'amount ASC', 'fee DESC', 'fee ASC', 'money DESC', 'money ASC', 'sendtime DESC', 'sendtime ASC', 'reveicetime DESC', 'reveicetime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		(isset($username) && check_name($username)) or $username = '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		isset($bank) or $bank = '';
		isset($timetype) or $timetype = 'sendtime';
		isset($mtype) or $mtype = 'amount';
		isset($minamount) or $minamount = '';
		isset($maxamount) or $maxamount = '';

		$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$status_select = dselect($dstatus, 'status', '状态', $status, '', 1, '', 1);
		$order_select  = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($bank) $condition .= " AND bank='$bank'";
		if($fromtime) $condition .= " AND $timetype>=$fromtime";
		if($totime) $condition .= " AND $timetype<=$totime";
		if($status !== '') $condition .= " AND status=$status";
		if($username) $condition .= " AND username='$username'";
		if($itemid) $condition .= " AND itemid=$itemid";
		if($minamount != '') $condition .= " AND $mtype>=$minamount";
		if($maxamount != '') $condition .= " AND $mtype<=$maxamount";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);	
		$charges = array();
		$amount = $fee = $money = 0;
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY $dorder[$order] LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['sendtime'] = timetodate($r['sendtime'], 5);
			$r['receivetime'] = $r['receivetime'] ? timetodate($r['receivetime'], 5) : '--';
			$r['editor'] or $r['editor'] = 'system';
			$r['dstatus'] = $_status[$r['status']];
			$amount += $r['amount'];
			$fee += $r['fee'];
			$money += $r['money'];
			$charges[] = $r;
		}
		include tpl('charge', $module);
	break;
}
?>