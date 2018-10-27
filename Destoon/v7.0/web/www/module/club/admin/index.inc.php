<?php
defined('DT_ADMIN') or exit('Access Denied');
$gid = isset($gid) ? intval($gid) : 0;

function need_notice() {
	global $reason, $msg, $eml, $sms, $wec;
	if(isset($reason) && $reason == '操作原因') $reason = '';
	if(strlen($reason) > 2) {
		$msg = 1;
		return true;
	}
	if(isset($msg)) return true;
	if(isset($eml)) return true;
	if(isset($sms)) return true;
	if(isset($wec)) return true;
	return false;
}

function send_notice($username, $subject, $body) {
	global $DT, $msg, $eml, $sms, $wec;
	if(!$username || !$subject || !$body) return;
	if(isset($msg)) send_message($username, $subject, $body);
	if(isset($wec)) send_weixin($username, $subject);
	if(isset($eml) || isset($sms)) {
		$user = userinfo($username);
		if(isset($eml)) send_mail($user['email'], $subject, $body);
		if(isset($sms)) send_sms($user['mobile'], $subject.$DT['sms_sign']);
	}
}

$menus = array (
    array('发表帖子', '?moduleid='.$moduleid.'&gid='.$gid.'&action=add'),
    array('帖子列表', '?moduleid='.$moduleid.'&gid='.$gid),
    array('待审核', '?moduleid='.$moduleid.'&gid='.$gid.'&action=check'),
    array('未通过', '?moduleid='.$moduleid.'&gid='.$gid.'&action=reject'),
    array('回收站', '?moduleid='.$moduleid.'&gid='.$gid.'&action=recycle'),
    array('移动帖子', '?moduleid='.$moduleid.'&gid='.$gid.'&action=move'),
);

require DT_ROOT.'/module/'.$module.'/'.$module.'.class.php';
$do = new $module($moduleid);

if(in_array($action, array('add', 'edit'))) {
	$FD = cache_read('fields-'.substr($table, strlen($DT_PRE)).'.php');
	if($FD) require DT_ROOT.'/include/fields.func.php';
	isset($post_fields) or $post_fields = array();
	$CP = $MOD['cat_property'];
	if($CP) require DT_ROOT.'/include/property.func.php';
	isset($post_ppt) or $post_ppt = array();
}

if($_catids || $_areaids) require DT_ROOT.'/admin/admin_check.inc.php';

if(in_array($action, array('', 'check', 'reject', 'recycle'))) {
	$sfields = array('模糊', '标题', '简介', '会员名', '昵称', '编辑', 'IP', '文件路径', '内容模板');
	$dfields = array('keyword', 'title', 'introduce', 'username', 'passport', 'editor', 'ip', 'filepath', 'template');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '回复时间降序', '回复时间升序', '浏览次数降序', '浏览次数升序', '回复数量降序', '回复数量升序', '信息ID降序', '信息ID升序');
	$dorder  = array($MOD['order'], 'addtime DESC', 'addtime ASC', 'replytime DESC', 'replytime ASC', 'hits DESC', 'hits ASC', 'reply DESC', 'reply ASC', 'itemid DESC', 'itemid ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;
	$level = isset($level) ? intval($level) : 0;
	$ontop = isset($ontop) ? intval($ontop) : 0;
	isset($style) or $style = 0;
	$style = isset($COLOR[$style]) ? '#'.$style : '';

	isset($datetype) && in_array($datetype, array('edittime', 'addtime', 'replytime')) or $datetype = 'addtime';
	(isset($fromdate) && is_date($fromdate)) or $fromdate = '';
	$fromtime = $fromdate ? strtotime($fromdate.' 0:0:0') : 0;
	(isset($todate) && is_date($todate)) or $todate = '';
	$totime = $todate ? strtotime($todate.' 23:59:59') : 0;


	$thumb = isset($thumb) ? intval($thumb) : 0;
	$guest = isset($guest) ? intval($guest) : 0;
	$itemid or $itemid = '';

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$level_select = level_select('level', '精华', $level, 'all');
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($_childs) $condition .= " AND catid IN (".$_childs.")";//CATE
	if($_areaids) $condition .= " AND areaid IN (".$_areaids.")";//CITY
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($catid) $condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
	if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	if($level) $condition .= $level > 9 ? " AND level>0" : " AND level=$level";
	if($ontop) $condition .= " AND ontop=$ontop";
	if($style) $condition .= " AND style='$style'";
	if($fromtime) $condition .= " AND `$datetype`>=$fromtime";
	if($totime) $condition .= " AND `$datetype`<=$totime";

	if($thumb) $condition .= " AND thumb<>''";
	if($guest) $condition .= " AND username=''";
	if($gid) $condition .= " AND gid=$gid";
	if($itemid) $condition .= " AND itemid=$itemid";

	$timetype = strpos($dorder[$order], 'replytime') === false ? 'add' : '';
}
switch($action) {
	case 'add':
		if($submit) {
			$GRP = get_group($post['gid']);
			$GRP or msg('指定商圈ID不存在');
			$post['catid'] = $GRP['catid'];
			if($do->pass($post)) {
				if($FD) fields_check($post_fields);
				if($CP) property_check($post_ppt);
				$do->add($post);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				if($CP) property_update($post_ppt, $moduleid, $post['catid'], $do->itemid);
				if($MOD['show_html'] && $post['status'] > 2) $do->tohtml($do->itemid);
				dmsg('添加成功', '?moduleid='.$moduleid.'&action='.$action.'&catid='.$post['catid']);
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
			$username = $_username;
			$item = array();
			$menuid = 0;
			isset($url) or $url = '';
			if($url) {
				$tmp = fetch_url($url);
				if($tmp) extract($tmp);
			}
			include tpl('edit', $module);
		}
	break;
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			if($do->pass($post)) {
				if($FD) fields_check($post_fields);
				if($CP) property_check($post_ppt);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				if($CP) property_update($post_ppt, $moduleid, $post['catid'], $do->itemid);
				$do->edit($post);
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			$item = $do->get_one();
			extract($item);
			$addtime = timetodate($addtime);
			$menuon = array('4', '3', '2', '1');
			$menuid = $menuon[$status];
			include tpl($action, $module);
		}
	break;
	case 'move':
		if($submit) {
			$fromids or msg('请填写来源ID');
			in_array($fromtype, array('gid', 'itemid')) or msg('请选择ID类型');
			$tocatid = intval($tocatid);
			$GRP = get_group($tocatid);
			($GRP && $GRP['status'] == 3) or msg('目标商圈不存在');
			if($tocatid) {
				$db->query("UPDATE {$table} SET gid=$tocatid WHERE `{$fromtype}` IN ($fromids)");
				$fromtype = $fromtype == 'itemid' ? 'tid' : 'gid';
				$db->query("UPDATE {$table_reply} SET gid=$tocatid WHERE `{$fromtype}` IN ($fromids)");
				dmsg('移动成功', $forward);
			} else {
				msg('请填写目标商圈ID');
			}
		} else {
			$itemid = $itemid ? implode(',', $itemid) : '';
			$menuid = 5;
			include tpl($action, $module);
		}
	break;
	case 'update':
		is_array($itemid) or msg('请选择帖子');
		foreach($itemid as $v) {
			$do->update($v);
		}
		dmsg('更新成功', $forward);
	break;
	case 'tohtml':
		is_array($itemid) or msg('请选择帖子');
		$html_itemids = $itemid;
		foreach($html_itemids as $itemid) {
			tohtml('show', $module);
		}
		dmsg('生成成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择帖子');
		isset($recycle) ? $do->recycle($itemid) : $do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'restore':
		$itemid or msg('请选择帖子');
		$do->restore($itemid);
		dmsg('还原成功', $forward);
	break;
	case 'clear':
		$do->clear();
		dmsg('清空成功', $forward);
	break;
	case 'level':
		$itemid or msg('请选择帖子');
		$level = intval($level);
		($level >= 0 && $level <= 9) or $level = 0;
		#$do->level($itemid, $level);
		foreach($itemid as $tid) {
			$db->query("UPDATE {$table} SET level=$level WHERE itemid=$tid");
		}
		if(need_notice()) {
			foreach($itemid as $tid) {
				$T = $db->get_one("SELECT title,linkurl,username FROM {$table} WHERE itemid=$tid");
				$body = lang($L['manage_msg_content'], array($MOD['linkurl'].$T['linkurl'], nl2br($reason), $_username));
				send_notice($T['username'], lang($L['manage_msg_title'], array('帖子', dsubstr($T['title'], 20, '...'), $level ? '加入精华' : '取消精华')), lang($L['manage_msg_content'], array($MOD['linkurl'].$T['linkurl'], nl2br($reason), $_username)));
			}
		}
		dmsg($level ? '精华'.$level.'设置成功' : '精华取消成功', $forward);
	break;
	case 'ontop':
		$itemid or msg('请选择帖子');
		$ontop = intval($ontop);
		in_array($ontop, array(0, 1, 2)) or $ontop = 0;
		foreach($itemid as $tid) {
			$db->query("UPDATE {$table} SET ontop=$ontop WHERE itemid=$tid");
		}
		if(need_notice()) {
			foreach($itemid as $tid) {
				$T = $db->get_one("SELECT title,linkurl,username FROM {$table} WHERE itemid=$tid");
				$body = lang($L['manage_msg_content'], array($MOD['linkurl'].$T['linkurl'], nl2br($reason), $_username));
				send_notice($T['username'], lang($L['manage_msg_title'], array('帖子', dsubstr($T['title'], 20, '...'), $ontop ? '置顶' : '取消置顶')), lang($L['manage_msg_content'], array($MOD['linkurl'].$T['linkurl'], nl2br($reason), $_username)));
			}
		}
		dmsg($ontop ? '置顶设置成功' : '置顶取消成功', $forward);
	break;
	case 'style':
		$itemid or msg('请选择帖子');
		$style = isset($COLOR[$style]) ? '#'.$style : '';
		foreach($itemid as $tid) {
			$db->query("UPDATE {$table} SET style='$style' WHERE itemid=$tid");
		}
		if(need_notice()) {
			foreach($itemid as $tid) {
				$T = $db->get_one("SELECT title,linkurl,username FROM {$table} WHERE itemid=$tid");
				$body = lang($L['manage_msg_content'], array($MOD['linkurl'].$T['linkurl'], nl2br($reason), $_username));
				send_notice($T['username'], lang($L['manage_msg_title'], array('帖子', dsubstr($T['title'], 20, '...'), $style ? '高亮' : '取消高亮')), lang($L['manage_msg_content'], array($MOD['linkurl'].$T['linkurl'], nl2br($reason), $_username)));
			}
		}
		dmsg($style ? '高亮设置成功' : '高亮取消成功', $forward);
	break;
	case 'recycle':
		$lists = $do->get_list('status=0'.$condition, $dorder[$order]);
		$menuid = 4;
		include tpl('index', $module);
	break;
	case 'reject':
		if($itemid && !$psize) {
			$do->reject($itemid);
			dmsg('拒绝成功', $forward);
		} else {
			$lists = $do->get_list('status=1'.$condition, $dorder[$order]);
			$menuid = 3;
			include tpl('index', $module);
		}
	break;
	case 'check':
		if($itemid && !$psize) {
			$do->check($itemid);
			dmsg('审核成功', $forward);
		} else {
			$lists = $do->get_list('status=2'.$condition, $dorder[$order]);
			$menuid = 2;
			include tpl('index', $module);
		}
	break;
	default:
		$lists = $do->get_list('status=3'.$condition, $dorder[$order]);
		$menuid = 1;
		include tpl('index', $module);
	break;
}
?>