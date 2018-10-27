<?php
defined('DT_ADMIN') or exit('Access Denied');
$table = $table_resume;
require DT_ROOT.'/module/'.$module.'/resume.class.php';
$do = new resume($moduleid);
$menus = array (
    array('添加简历', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('简历列表', '?moduleid='.$moduleid.'&file='.$file),
    array('审核简历', '?moduleid='.$moduleid.'&file='.$file.'&action=check'),
    array('未通过简历', '?moduleid='.$moduleid.'&file='.$file.'&action=reject'),
    array('回收站', '?moduleid='.$moduleid.'&file='.$file.'&action=recycle'),
    array('移动简历', '?moduleid='.$moduleid.'&file='.$file.'&action=move'),
);
if(in_array($action, array('add', 'edit'))) {
	$FD = cache_read('fields-'.substr($table, strlen($DT_PRE)).'.php');
	if($FD) require DT_ROOT.'/include/fields.func.php';
	isset($post_fields) or $post_fields = array();
}

if(in_array($action, array('', 'check', 'expire', 'reject', 'recycle'))) {
	$GENDER[0] = '性别';
	$TYPE[0] = '工作';
	$MARRIAGE[0] = '婚姻';
	$EDUCATION[0] = '学历';
	$sfields = array('模糊', '标题', '简介', '会员名', '真实姓名', '毕业院校', '所学专业', '专业技能', '语言水平', '联系手机', '联系电话', '联系地址', 'Email', 'QQ', '微信', '模板', 'IP');
	$dfields = array('keyword', 'title', 'introduce', 'username', 'truename', 'school', 'major', 'skill', 'language', 'mobile', 'telephone', 'address', 'email', 'qq', 'wx', 'template', 'ip');
	$sorder  = array('结果排序方式', '更新时间降序', '更新时间升序', '添加时间降序', '添加时间升序', '浏览次数降序', '浏览次数升序', '最低待遇降序', '最低待遇升序', '最高待遇降序', '最高待遇升序', '学历高低降序', '学历高低升序', '信息ID降序', '信息ID升序');
	$dorder  = array($MOD['order'], 'edittime DESC', 'edittime ASC', 'addtime DESC', 'addtime ASC', 'hits DESC', 'hits ASC', 'minsalary DESC', 'minsalary ASC', 'maxalary DESC', 'maxsalary ASC', 'education DESC', 'education ASC', 'itemid DESC', 'itemid ASC');

	$level = isset($level) ? intval($level) : 0;
	$gender = isset($gender) ? intval($gender) : 0;
	$type = isset($type) ? intval($type) : 0;
	$marriage = isset($marriage) ? intval($marriage) : 0;
	$education = isset($education) ? intval($education) : 0;
	$experience = isset($experience) ? intval($experience) : 0;
	$areaid = isset($areaid) ? intval($areaid) : 0;
	$minsalary = isset($minsalary) ? intval($minsalary) : 0;
	$maxsalary = isset($maxsalary) ? intval($maxsalary) : 0;
	$open = isset($open) ? intval($open) : 0;
	$thumb = isset($thumb) ? intval($thumb) : 0;

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;
	
	isset($datetype) && in_array($datetype, array('edittime', 'addtime', 'totime')) or $datetype = 'edittime';
	(isset($fromdate) && is_date($fromdate)) or $fromdate = '';
	$fromtime = $fromdate ? strtotime($fromdate.' 0:0:0') : 0;
	(isset($todate) && is_date($todate)) or $todate = '';
	$totime = $todate ? strtotime($todate.' 23:59:59') : 0;

	$areaid = isset($areaid) ? intval($areaid) : 0;
	$itemid or $itemid = '';

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$level_select = level_select('level', '级别', $level);
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($catid) $condition .= ($CATEGORY[$catid]['child']) ? " AND catid IN (".$CATEGORY[$catid]['arrchildid'].")" : " AND catid=$catid";
	if($areaid) $condition .= ($AREA[$areaid]['child']) ? " AND areaid IN (".$AREA[$areaid]['arrchildid'].")" : " AND areaid=$areaid";
	if($level) $condition .= " AND level=$level";
	if($gender) $condition .= " AND gender=$gender";
	if($type) $condition .= " AND type=$type";
	if($marriage) $condition .= " AND marriage=$marriage";
	if($education) $condition .= " AND education>=$education";
	if($experience) $condition .= " AND experience>=$experience";
	if($minsalary) $condition .= " AND minsalary>=$minsalary";
	if($maxsalary) $condition .= " AND maxsalary<=$maxsalary";
	if($open) $condition .= " AND open=$open";
	if($thumb) $condition .= " AND thumb<>''";
	if($fromtime) $condition .= " AND `$datetype`>=$fromtime";
	if($totime) $condition .= " AND `$datetype`<=$totime";
	if($itemid) $condition .= " AND itemid=$itemid";

	$timetype = strpos($dorder[$order], 'add') !== false ? 'add' : '';
}

switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				if($FD) fields_check($post_fields);
				$do->add($post);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action);
			} else {
				msg($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				isset($$v) or $$v = '';
			}
			$content = '';
			$status = 3;
			$addtime = timetodate($DT_TIME);
			$gender = 1;
			$byear = 19;
			$bmonth = $bday = $experience = $marriage = $type = 1;
			$education = 3;
			$minsalary = 1000;
			$maxsalary = 0;
			$open = 3;
			$item = array();
			$menuid = 0;
			isset($url) or $url = '';
			if($url) {
				$tmp = fetch_url($url);
				if($tmp) extract($tmp);
			}
			include tpl('resume_edit', $module);
		}
	break;
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			if($do->pass($post)) {
				if($FD) fields_check($post_fields);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				$do->edit($post);
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			$item = $do->get_one();
			extract($item);
			$addtime = timetodate($addtime);
			list($byear, $bmonth, $bday) = explode('-', $birthday);
			$menuon = array('4', '3', '2', '1');
			$menuid = $menuon[$status];
			include tpl('resume_'.$action, $module);
		}
	break;
	case 'move':
		if($submit) {
			$fromids or msg('请填写来源ID');
			if($tocatid) {
				$db->query("UPDATE {$table} SET catid=$tocatid WHERE `{$fromtype}` IN ($fromids)");
				dmsg('移动成功', $forward);
			} else {
				msg('请选择目标分类');
			}
		} else {
			$itemid = $itemid ? implode(',', $itemid) : '';
			$menuid = 5;
			include tpl($action);
		}
	break;
	case 'update':
		is_array($itemid) or msg('请选择简历');
		foreach($itemid as $v) {
			$do->update($v);
		}
		dmsg('更新成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择简历');
		isset($recycle) ? $do->recycle($itemid) : $do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'restore':
		$itemid or msg('请选择简历');
		$do->restore($itemid);
		dmsg('还原成功', $forward);
	break;
	case 'refresh':
		$itemid or msg('请选择简历');
		$do->refresh($itemid);
		dmsg('刷新成功', $forward);
	break;
	case 'clear':
		$do->clear();
		dmsg('清空成功', $forward);
	break;
	case 'level':
		$itemid or msg('请选择简历');
		$level = intval($level);
		$do->level($itemid, $level);
		dmsg('级别设置成功', $forward);
	break;
	case 'recycle':
		$lists = $do->get_list('status=0'.$condition);
		$menuid = 4;
		include tpl('resume', $module);
	break;
	case 'reject':
		if($itemid && !$psize) {
			$do->reject($itemid);
			dmsg('拒绝成功', $forward);
		} else {
			$lists = $do->get_list('status=1'.$condition);
			$menuid = 3;
			include tpl('resume', $module);
		}
	break;
	case 'check':
		if($itemid && !$psize) {
			$do->check($itemid);
			dmsg('审核成功', $forward);
		} else {
			$lists = $do->get_list('status=2'.$condition);
			$menuid = 2;
			include tpl('resume', $module);
		}
	break;
	default:
		$lists = $do->get_list('status=3'.$condition);
		$menuid = 1;
		include tpl('resume', $module);
	break;
}
?>