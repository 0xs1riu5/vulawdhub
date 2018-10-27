<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/'.$module.'.class.php';
$do = new $module();
$menus = array (
    array('添加'.VIP, '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array(VIP.'列表', '?moduleid='.$moduleid.'&file='.$file),
    array('过期'.VIP, '?moduleid='.$moduleid.'&file='.$file.'&action=expire'),
);
$this_forward = '?moduleid='.$moduleid.'&file='.$file;
if($_catids || $_areaids) {
	if(isset($userid)) $itemid = $userid;
	if(isset($member['areaid'])) $post['areaid'] = $member['areaid'];
	require DT_ROOT.'/admin/admin_check.inc.php';
}
$fromtime = timetodate($DT_TIME, 3);
$GROUP = cache_read('group.php');
switch($action) {
	case 'add':	
		if($submit) {		
			if(!$vip['username']) msg('会员名不能为空');
			$vip['username'] = trim($vip['username']);
			$money = dround($money);
			$credit = intval($credit);
			$sms = intval($sms);

			$usernames = explode("\n", trim($vip['username']));
			foreach($usernames as $username) {
				$username = trim($username);
				if(!$username) continue;
				$vip['username'] = $username;
				$do->vip_edit($vip);
				if($money) {
					money_add($username, $money);
					money_record($username, $money, '站内', $_username, $reason, $GROUP[$vip['groupid']]['groupname']);
				}
				if($credit) {
					credit_add($username, $credit);
					credit_record($username, $credit, $_username, $reason, $GROUP[$vip['groupid']]['groupname']);
				}
				if($sms) {
					sms_add($username, $sms);
					sms_record($username, $sms, $_username, $reason, $GROUP[$vip['groupid']]['groupname']);
				}
			}

			dmsg('添加成功', $this_forward);
		} else {
			isset($username) or $username = '';
			if(isset($userid)) {
				if($userid) {
					$userids = is_array($userid) ? implode(',', $userid) : $userid;					
					$result = $db->query("SELECT username FROM {$DT_PRE}member WHERE userid IN ($userids)");
					while($r = $db->fetch_array($result)) {
						$username .= $r['username']."\n";
					}
				}
			}
			$totime = timetodate($DT_TIME+365*86400, 3);
			include tpl('vip_add', $module);
		}
	break;
	case 'edit':
		$userid or msg();
		$do->userid = $userid;
		if($submit) {
			if($do->vip_edit($vip)) {
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_one());
			$fromtime = timetodate($fromtime, 3);
			$totime = timetodate($totime, 3);
			$validtime = $validtime ? timetodate($validtime, 3) : '';
			include tpl('vip_edit', $module);
		}
	break;
	case 'delete':
		$userid or msg('请选择公司');
		$do->vip_delete($userid);
		dmsg('撤销成功', $forward);
	break;
	case 'update':
		is_array($userid) or msg('请选择公司');
		foreach($userid as $v) {
			$do->update($v);
		}
		dmsg('更新成功', $forward);
	break;
	default:
		$sfields = array('按条件', '公司名', '会员名');
		$dfields = array('keyword', 'company', 'username');
		$sorder  = array('结果排序方式', '服务开始降序', '服务开始升序', '服务结束降序', '服务结束升序', VIP.'指数降序', VIP.'指数升序', '理论值降序', '理论值升序', '修正值降序', '修正值升序', '会员ID降序', '会员ID升序');
		$dorder  = array('fromtime DESC', 'fromtime DESC', 'fromtime ASC', 'totime DESC', 'totime ASC', 'vip DESC', 'vip ASC', 'vipt DESC', 'vipt ASC', 'vipr DESC', 'vipr ASC', 'userid DESC', 'userid ASC');
	
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
		$fromdate = isset($fromdate) ? $fromdate : '';
		$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
		$todate = isset($todate) ? $todate : '';
		$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
		isset($vipt) or $vipt = '';
		isset($vipr) or $vipr = '';
		isset($timetype) or $timetype = 'fromtime';
		$vip = isset($vip) ? intval($vip) : 0;
		$groupid = isset($groupid) ? intval($groupid) : 0;
	
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select  = dselect($sorder, 'order', '', $order);
		$group_select = group_select('groupid', '会员组', $groupid);
		
		if($action == 'expire') {
			$condition = "groupid>4 AND totime>0 AND totime<$DT_TIME";
		} else {
			$condition = $vip ? "vip=$vip" : "vip>0";
		}
		if($_areaids) $condition .= " AND areaid IN (".$_areaids.")";//CITY
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($groupid) $condition .= " AND groupid=$groupid";
		if($fromtime) $condition .= " AND $timetype>=$fromtime";
		if($totime) $condition .= " AND $timetype<=$totime";
		if($vipt != '') $condition .= " AND vipt=".intval($vipt);
		if($vipr != '') $condition .= " AND vipr=".intval($vipr);
		$companys = $do->get_list($condition, $dorder[$order]);
		include tpl($action == 'expire' ? 'vip_expire' : 'vip', $module);
	break;
}
?>