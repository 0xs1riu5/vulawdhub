<?php
defined('DT_ADMIN') or exit('Access Denied');
isset($username) or $username = '';
$menus = array (
    array('保证金增减', '?moduleid='.$moduleid.'&file='.$file.'&username='.$username.'&action=add'),
    array('保证金流水', '?moduleid='.$moduleid.'&file='.$file.'&username='.$username),
);
$BANKS = explode('|', trim($MOD['pay_banks']));
$table = $DT_PRE.'finance_deposit';
switch($action) {
	case 'add':
		if($submit) {
			$username or msg('请填写会员名');
			$amount or msg('请填写金额');
			$reason or msg('请填写事由');
			$amount = dround($amount);
			if($amount <= 0) msg('金额格式错误');
			if(!$type) $amount = -$amount;
			$error = '';
			$success = 0;
			$usernames = explode("\n", trim($username));
			foreach($usernames as $username) {
				$username = trim($username);
				if(!$username) continue;
				$r = $db->get_one("SELECT username,deposit FROM {$DT_PRE}member WHERE username='$username'");
				if(!$r) {
					$error .= '<br/>会员['.$username.']不存在';
					continue;
				}
				if(!$type && $r['deposit'] < abs($amount)) {
					$error .= '<br/>会员['.$username.']保证金余额不足，当前余额为:'.$r['deposit'];
					continue;
				}
				$db->query("INSERT INTO {$DT_PRE}finance_deposit (username,amount,addtime,editor,reason,note) VALUES ('$username','$amount','$DT_TIME','$_username','$reason','$note')");
				$db->query("UPDATE {$DT_PRE}member SET deposit=deposit+$amount WHERE username='$username'");
				$success++;
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
			include tpl('deposit_add', $module);
		}
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
		if($minamount != '') $condition .= " AND amount>=$minamount";
		if($maxamount != '') $condition .= " AND amount<=$maxamount";
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
		include tpl('deposit', $module);
	break;
}
?>