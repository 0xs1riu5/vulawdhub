<?php
defined('DT_ADMIN') or exit('Access Denied');
isset($username) or $username = '';
$menus = array (
    array($DT['credit_name'].'奖惩', '?moduleid='.$moduleid.'&file='.$file.'&username='.$username.'&action=add'),
    array($DT['credit_name'].'流水', '?moduleid='.$moduleid.'&file='.$file.'&username='.$username),
    array('记录清理', '?moduleid='.$moduleid.'&file='.$file.'&action=clear', 'onclick="if(!confirm(\'为了系统安全,系统仅删除90天之前的记录\n此操作不可撤销，请谨慎操作\')) return false"'),
);
$BANKS = explode('|', trim($MOD['pay_banks']));
$table = $DT_PRE.'finance_credit';
switch($action) {
	case 'clear':
		$time = $today_endtime - 90*86400;
		$db->query("DELETE FROM {$table} WHERE addtime<$time");
		dmsg('清理成功', $forward);
	break;
	case 'add':
		if($submit) {
			$username or msg('请填写会员名');
			$amount or msg('请填写分值');
			$reason or msg('请填写事由');
			$amount = intval($amount);
			if($amount <= 0) msg('分值格式错误');
			if(!$type) $amount = -$amount;
			$error = '';
			$success = 0;
			$usernames = explode("\n", trim($username));
			foreach($usernames as $username) {
				$username = trim($username);
				if(!$username) continue;
				$r = $db->get_one("SELECT username,credit FROM {$DT_PRE}member WHERE username='$username'");
				if(!$r) {
					$error .= '<br/>会员['.$username.']不存在';
					continue;
				}
				if(!$type && $r['credit'] < abs($amount)) {
					$error .= '<br/>会员['.$username.']'.$DT['credit_name'].'不足，当前'.$DT['credit_name'].'为:'.$r['credit'];
					continue;
				}
				$reason or $reason = '奖励';
				$note or $note = '手工';
				credit_add($username, $amount);
				credit_record($username, $amount, $_username, $reason, $note);
			}
			if($error) msg('操作成功 '.$success.' 位会员，发生以下错误：'.$error);
			dmsg('操作成功', '?moduleid='.$moduleid.'&file='.$file);
		} else {
			if(isset($userid)) {
				if($userid) {
					$userids = is_array($userid) ? implode(',', $userid) : $userid;					
					$result = $db->query("SELECT username FROM {$DT_PRE}member WHERE userid IN ($userids)");
					while($r = $db->fetch_array($result)) {
						$username .= $r['username']."\n";
					}
				}
			}
			include tpl('credit_add', $module);
		}
	break;
	case 'delete':
		$itemid or msg('未选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$table} WHERE itemid IN ($itemids)");
		dmsg('删除成功', $forward);
	break;
	default:
		$sfields = array('按条件', '会员名', '金额', '事由', '备注', '操作人');
		$dfields = array('username', 'username', 'amount', 'reason', 'note', 'editor');
		$sorder  = array('排序方式', '金额降序', '金额升序', '时间降序', '时间升序');
		$dorder  = array('itemid DESC', 'amount DESC', 'amount ASC', 'addtime DESC', 'addtime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		(isset($username) && check_name($username)) or $username = '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		isset($type) or $type = 0;
		isset($mtype) or $mtype = 'amount';
		isset($minamount) or $minamount = '';
		isset($maxamount) or $maxamount = '';

		isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND addtime>=$fromtime";
		if($totime) $condition .= " AND addtime<=$totime";
		if($type) $condition .= $type == 1 ? " AND amount>0" : " AND amount<0";
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
		$records = array();
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY $dorder[$order] LIMIT $offset,$pagesize");
		$income = $expense = 0;
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['amount'] > 0 ? $income += $r['amount'] : $expense += $r['amount'];
			$records[] = $r;
		}
		include tpl('credit', $module);
	break;
}
?>