<?php
defined('DT_ADMIN') or exit('Access Denied');
$TYPE = get_type('poll', 1);
require DT_ROOT.'/module/'.$module.'/poll.class.php';
$do = new poll();
$menus = array (
    array('添加票选', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('票选列表', '?moduleid='.$moduleid.'&file='.$file),
    array('更新地址', '?moduleid='.$moduleid.'&file='.$file.'&action=html'),
    array('票选分类', 'javascript:Dwidget(\'?file=type&item='.$file.'\', \'票选分类\');'),
    array('模块设置', 'javascript:Dwidget(\'?moduleid='.$moduleid.'&file=setting&action='.$file.'\', \'模块设置\');'),
);
if($_catids || $_areaids) require DT_ROOT.'/admin/admin_check.inc.php';
switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				isset($$v) or $$v = '';
			}
			$poll_max = 0;
			$poll_page = 30;
			$poll_cols = 3;
			$poll_order = 0;
			$thumb_width = 120;
			$thumb_height = 90;
			$addtime = timetodate($DT_TIME);
			$typeid = 0;
			$menuid = 0;
			include tpl('poll_edit', $module);
		}
	break;
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			if($do->pass($post)) {
				$do->edit($post);
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_one());
			$addtime = timetodate($addtime);
			$fromtime = $fromtime ? timetodate($fromtime, 3) : '';
			$totime = $totime ? timetodate($totime, 3) : '';
			$menuid = 1;
			include tpl('poll_edit', $module);
		}
	break;
	case 'html':
		$all = (isset($all) && $all) ? 1 : 0;
		$one = (isset($one) && $one) ? 1 : 0;
		if(!isset($num)) {
			$num = 50;
		}
		if(!isset($fid)) {
			$r = $db->get_one("SELECT min(itemid) AS fid FROM {$DT_PRE}vote");
			$fid = $r['fid'] ? $r['fid'] : 0;
		}
		isset($sid) or $sid = $fid;
		if(!isset($tid)) {
			$r = $db->get_one("SELECT max(itemid) AS tid FROM {$DT_PRE}poll");
			$tid = $r['tid'] ? $r['tid'] : 0;
		}
		if($fid <= $tid) {
			$result = $db->query("SELECT itemid,linkurl FROM {$DT_PRE}poll WHERE itemid>=$fid ORDER BY itemid LIMIT 0,$num");
			if($db->affected_rows($result)) {
				while($r = $db->fetch_array($result)) {
					$itemid = $r['itemid'];
					$linkurl = $do->linkurl($itemid);
					if($linkurl != $r['linkurl']) $db->query("UPDATE {$DT_PRE}poll SET linkurl='$linkurl' WHERE itemid=$itemid");
				}
				$itemid += 1;
			} else {
				$itemid = $fid + $num;
			}
		} else {
			if($all) dheader('?moduleid=3&file=form&action=html&all=1&one='.$one);
			dmsg('更新成功', "?moduleid=$moduleid&file=$file");
		}
		msg('ID从'.$fid.'至'.($itemid-1).'[票选]更新成功'.progress($sid, $fid, $tid), "?moduleid=$moduleid&file=$file&action=$action&sid=$sid&fid=$itemid&tid=$tid&num=$num&all=$all&one=$one");
	break;
	case 'delete':
		$itemid or msg('请选择票选');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'level':
		$itemid or msg('请选择票选');
		$level = intval($level);
		$do->level($itemid, $level);
		dmsg('级别设置成功', $forward);
	break;
	case 'record':
		$pollid = isset($pollid) ? intval($pollid) : 0;
		$pollid or msg();
		$menus = array (
			array('选项管理', '?moduleid='.$moduleid.'&file='.$file.'&pollid='.$pollid.'&action=item'),
			array('投票记录', '?moduleid='.$moduleid.'&file='.$file.'&pollid='.$pollid.'&action=record'),
			array('统计报表', '?moduleid='.$moduleid.'&file='.$file.'&pollid='.$pollid.'&action=stats'),
		);
		$do->itemid = $pollid;		
		$P = $do->get_one();
		$P or msg('票选不存在');
		$I = $do->item_all("pollid=$pollid");
		$sfields = array('按条件', '会员', 'IP');
		$dfields = array('username','username','ip');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = "pollid=$pollid";
		if($itemid) $condition .= " AND itemid=$itemid";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		$lists = $do->get_list_record($condition);
		include tpl('poll_record', $module);
	break;
	case 'stats':
		$pollid = isset($pollid) ? intval($pollid) : 0;
		$pollid or msg();
		$menus = array (
			array('选项管理', '?moduleid='.$moduleid.'&file='.$file.'&pollid='.$pollid.'&action=item'),
			array('投票记录', '?moduleid='.$moduleid.'&file='.$file.'&pollid='.$pollid.'&action=record'),
			array('统计报表', '?moduleid='.$moduleid.'&file='.$file.'&pollid='.$pollid.'&action=stats'),
		);
		$do->itemid = $pollid;		
		$P = $do->get_one();
		$P or msg('票选不存在');
		$title = $P['title'];
		$I = $do->item_all("pollid=$pollid");
		$chart_data = '';
		foreach($I as $k=>$v) {
			if($k) $chart_data .= '\n';
			$chart_data .= $v['title'].';'.$v['polls'];
		}
		include tpl('poll_stats', $module);
	break;
	case 'item':
		$pollid = isset($pollid) ? intval($pollid) : 0;
		$pollid or msg();
		$menus = array (
			array('选项管理', '?moduleid='.$moduleid.'&file='.$file.'&pollid='.$pollid.'&action=item'),
			array('投票记录', '?moduleid='.$moduleid.'&file='.$file.'&pollid='.$pollid.'&action=record'),
			array('统计报表', '?moduleid='.$moduleid.'&file='.$file.'&pollid='.$pollid.'&action=stats'),
		);
		$itemid = $pollid;
		$do->itemid = $itemid;
		$P = $do->get_one();
		$P or msg('票选不存在');
		if($submit) {
			$do->item_update($post);
			$t = $db->get_one("SELECT SUM(polls) AS total FROM {$DT_PRE}poll_item WHERE pollid=$itemid");
			if($t['total'] != $P['poll']) $db->query("UPDATE {$DT_PRE}poll SET polls=$t[total] WHERE itemid=$itemid");
			dmsg('更新成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&pollid='.$itemid);
		} else {
			$sorder  = array('结果排序方式', '投票次数降序', '投票次数升序');
			$dorder  = array('listorder DESC,itemid DESC', 'polls DESC', 'polls ASC');
			$sfields = array('标题', '简介', '链接');
			$dfields = array('title', 'introduce', 'linkurl');
			isset($fields) && isset($dfields[$fields]) or $fields = 0;
			isset($order) && isset($dorder[$order]) or $order = 0;
			$fields_select = dselect($sfields, 'fields', '', $fields);
			$order_select  = dselect($sorder, 'order', '', $order);
			$condition = "pollid=$itemid";
			if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
			$lists = $do->item_list($condition, $dorder[$order]);
			include tpl('poll_item', $module);
		}
	break;
	default:
		$sfields = array('按条件', '标题', '内容');
		$dfields = array('title','title','content');
		$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '投票总数降序', '投票总数升序', '浏览次数降序', '浏览次数升序', '选项总数降序', '选项总数升序', '开始时间降序', '开始时间升序', '到期时间降序', '到期时间升序');
		$dorder  = array('itemid DESC', 'addtime DESC', 'addtime ASC', 'polls DESC', 'polls ASC', 'hits DESC', 'hits ASC', 'items DESC', 'items ASC', 'fromtime DESC', 'fromtime ASC', 'totime DESC', 'totime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
		isset($typeid) or $typeid = 0;
		$level = isset($level) ? intval($level) : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$type_select = type_select('poll', 1, 'typeid', '请选择分类', $typeid);
		$order_select  = dselect($sorder, 'order', '', $order);
		$level_select = level_select('level', '级别', $level);
		$condition = '1';
		if($_areaids) $condition .= " AND areaid IN (".$_areaids.")";//CITY
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($typeid) $condition .= " AND typeid IN (".type_child($typeid, $TYPE).")";
		if($level) $condition .= " AND level=$level";
		if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
		$lists = $do->get_list($condition, $dorder[$order]);
		include tpl('poll', $module);
	break;
}
?>