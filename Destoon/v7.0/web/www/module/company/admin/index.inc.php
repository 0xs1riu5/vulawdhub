<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/'.$module.'.class.php';
$do = new $module();
$menus = array (
    array($MOD['name'].'列表', '?moduleid='.$moduleid),
    array('绑定域名', '?moduleid='.$moduleid.'&action=domain'),
    array('移动地区', '?moduleid='.$moduleid.'&action=move'),
);
$this_forward = '?moduleid='.$moduleid.'&file='.$file;

if($_catids || $_areaids) {
	if(isset($userid)) $itemid = $userid;
	if(isset($member['areaid'])) $post['areaid'] = $member['areaid'];
	require DT_ROOT.'/admin/admin_check.inc.php';
}

if(in_array($action, array('', 'domain'))) {
	$sfields = array('按条件', '公司名', '会员名', '公司类型', '公司规模', '销售', '采购', '主营行业', '经营模式', '电话', '传真',  'Email',  '地址',  '邮编', '主页', '风格目录', '模板目录', '绑定域名', '备案号');
	$dfields = array('keyword', 'company', 'username', 'type', 'size', 'sell', 'buy', 'business', 'mode', 'telephone', 'fax', 'mail', 'address', 'postcode', 'homepage', 'skin', 'template', 'domain', 'icp');
	$sorder  = array('结果排序方式', VIP.'指数降序', VIP.'指数升序', '注册年份降序', '注册年份升序', '注册资本降序', '注册资本升序', '服务开始降序', '服务开始升序', '服务结束降序', '服务结束升序','浏览人气降序','浏览人气升序', '评论数量降序', '评论数量升序');
	$dorder  = array('userid DESC', 'vip DESC', 'vip ASC', 'regyear DESC', 'regyear ASC', 'capital DESC', 'capital ASC', 'fromtime DESC', 'fromtime ASC', 'totime DESC', 'totime ASC', 'hits DESC', 'hits ASC', 'comments DESC', 'comments ASC');
	$svalid = array('认证', '已通过' , '未通过');
	$MS = cache_read('module-2.php');
	$modes = explode('|', '经营模式|'.$MS['com_mode']);
	$types = explode('|', '公司类型|'.$MS['com_type']);
	$sizes = explode('|', '公司规模|'.$MS['com_size']);
	
	$thumb = isset($thumb) ? intval($thumb) : 0;
	$mincapital = isset($mincapital) ? dround($mincapital) : '';
	$mincapital or $mincapital = '';
	$maxcapital = isset($maxcapital) ? dround($maxcapital) : '';
	$maxcapital or $maxcapital = '';
	$areaid = isset($areaid) ? intval($areaid) : 0;
	isset($mode) && isset($modes[$mode]) or $mode = 0;
	isset($type) && isset($types[$type]) or $type = 0;
	isset($size) && isset($sizes[$size]) or $size = 0;

	$vip = isset($vip) ? ($vip === '' ? -1 : intval($vip)) : -1;
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;
	$groupid = isset($groupid) ? intval($groupid) : 0;
	$valid = isset($valid) ? intval($valid) : 0;
	$level = isset($level) ? intval($level) : 0;
	$uid = isset($uid) ? intval($uid) : '';
	(isset($username) && check_name($username)) or $username = '';
	$fromdate = isset($fromdate) ? $fromdate : '';
	$fromtime = is_date($fromdate) ? strtotime($fromdate.' 0:0:0') : 0;
	$todate = isset($todate) ? $todate : '';
	$totime = is_date($todate) ? strtotime($todate.' 23:59:59') : 0;
	isset($timetype) or $timetype = 'totime';

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$level_select = level_select('level', '级别', $level, 'all');
	$order_select  = dselect($sorder, 'order', '', $order);
	$valid_select = dselect($svalid, 'valid', '', $valid);
	$group_select = group_select('groupid', '会员组', $groupid);
	$mode_select = dselect($modes, 'mode', '', $mode);
	$type_select = dselect($types, 'type', '', $type);
	$size_select = dselect($sizes, 'size', '', $size);

	$condition = 'groupid>5';
	if($action == 'domain') $condition .= " AND domain<>''";
	if($_areaids) $condition .= " AND areaid IN (".$_areaids.")";//CITY
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($groupid) $condition .= " AND groupid=$groupid";
	if($vip > -1) $condition .= " AND vip=$vip";
	if($level) $condition .= $level > 9 ? " AND level>0" : " AND level=$level";
	if($valid) $condition .= $valid == 1 ? " AND validated=1" : " AND validated=0";
	if($catid) $condition .= " AND catids LIKE '%,".$catid.",%'";
	if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	if($mode) $condition .= " AND mode LIKE '%$modes[$mode]%'";
	if($type) $condition .= " AND type='$types[$type]'";
	if($size) $condition .= " AND size='$sizes[$size]'";
	if($mincapital) $condition .= " AND capital>$mincapital";
	if($maxcapital) $condition .= " AND capital<$maxcapital";
	if($thumb)  $condition .= " AND thumb<>''";
	if($uid) $condition .= " AND userid=$uid";
	if($username) $condition .= " AND username='$username'";
	if($fromtime) $condition .= " AND $timetype>=$fromtime";
	if($totime) $condition .= " AND $timetype<=$totime";
}

switch($action) {
	case 'update':
		is_array($userid) or msg('请选择'.$MOD['name']);
		foreach($userid as $v) {
			$do->update($v);
		}
		dmsg('更新成功', $forward);
	break;
	case 'move':
		if($submit) {
			$fromids or msg('请填写来源ID');
			if($toareaid) {
				$db->query("UPDATE {$table} SET areaid=$toareaid WHERE `{$fromtype}` IN ($fromids)");
				$db->query("UPDATE {$DT_PRE}member SET areaid=$toareaid WHERE `{$fromtype}` IN ($fromids)");
			}
			dmsg('移动成功', $forward);
		} else {
			$userid = isset($userid) ? implode(',', $userid) : '';
			$menuid = 2;
			include tpl($action, $module);
		}
	break;
	case 'level':
		$userid or msg('请选择'.$MOD['name']);
		$level = intval($level);
		$do->level($userid, $level);
		dmsg('级别设置成功', $forward);
	break;
	case 'domain':
		$lists = $do->get_list($condition, $dorder[$order]);
		$menuid = 1;
		include tpl('index', $module);
	break;
	default:
		$lists = $do->get_list($condition, $dorder[$order]);
		$menuid = 0;
		include tpl('index', $module);
	break;
}
?>