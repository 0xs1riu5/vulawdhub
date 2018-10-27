<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('登录日志', '?moduleid='.$moduleid.'&file='.$file),
    array('日志清理', '?moduleid='.$moduleid.'&file='.$file.'&action=clear', 'onclick="if(!confirm(\'为了系统安全,系统仅删除30天之前的日志\n此操作不可撤销，请谨慎操作\')) return false"'),
);
switch($action) {
	case 'clear':
		$time = $today_endtime - 30*86400;
		$db->query("DELETE FROM {$DT_PRE}login WHERE logintime<$time");
		dmsg('清理成功', $forward);
	break;
	case 'cp':
		$r = $db->get_one("SELECT password,passsalt FROM {$DT_PRE}login WHERE logid='$logid'");
		echo ($r['password'] == dpassword($password, $r['passsalt'])) ? '匹配' : '不匹配';
		exit;
	break;
	default:
		$sfields = array('按条件', '结果', '会员', '密码', 'IP', '客户端');
		$dfields = array('message', 'message', 'username', 'password', 'loginip', 'agent');
		isset($admin) or $admin = -1;
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$ip = isset($ip) ? $ip : '';
		(isset($username) && check_name($username)) or $username = '';
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND logintime>=$fromtime";
		if($totime) $condition .= " AND logintime<=$totime";
		if($ip) $condition .= " AND loginip='$ip'";
		if($username) $condition .= " AND username='$username'";
		if($admin > -1) $condition .= " AND admin='$admin'";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {	
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}login WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);		
		$logs = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}login WHERE $condition ORDER BY logid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['password'] = substr($r['password'], 0, 10).'************'.substr($r['password'], 20);
			$r['logintime'] = timetodate($r['logintime'], 6);
			$logs[] = $r;
		}
		include tpl('loginlog', $module);
	break;
}
?>