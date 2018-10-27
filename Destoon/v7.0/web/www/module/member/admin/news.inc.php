<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/news.class.php';
$do = new news();
$menus = array (
    array('添加新闻', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('新闻列表', '?moduleid='.$moduleid.'&file='.$file),
    array('审核新闻', '?moduleid='.$moduleid.'&file='.$file.'&action=check'),
    array('未通过新闻', '?moduleid='.$moduleid.'&file='.$file.'&action=reject'),
    array('回收站', '?moduleid='.$moduleid.'&file='.$file.'&action=recycle'),
    array('更新地址', '?moduleid='.$moduleid.'&file='.$file.'&action=update'),
);
if(in_array($action, array('', 'check', 'reject', 'recycle'))) {
	$level = isset($level) ? intval($level) : 0;
	$sfields = array('按条件', '标题', '会员名');
	$dfields = array('title', 'title', 'username');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '修改时间降序', '修改时间升序', '浏览次数降序', '浏览次数升序');
	$dorder  = array('addtime DESC', 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC', 'hits DESC', 'hits ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;
	$thumb = isset($thumb) ? intval($thumb) : 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);
	$level_select = level_select('level', '级别', $level);

	$condition = '';
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($level) $condition .= " AND level=$level";
	if($thumb) $condition .= " AND thumb<>''";
}
switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				$do->add($post);
				dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&catid='.$post['catid']);
			} else {
				msg($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				isset($$v) or $$v = '';
			}
			$content = '';
			$username = $_username;
			$status = 3;
			$addtime = timetodate($DT_TIME);
			$menuid = 0;
			include tpl('news_edit', $module);
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
			$menuon = array('4', '3', '2', '1');
			$menuid = $menuon[$status];
			include tpl('news_edit', $module);
		}
	break;
	case 'push':
		$MODULE[$aid]['module'] == 'article' or msg('请选择文章模型');
		if($submit) {
			$catid or msg('请选择分类');
			$tb = get_table($aid);
			$tb_data = get_table($aid, 1);
			$result = $db->query("SELECT * FROM {$DT_PRE}news WHERE itemid IN ($ids)");
			$i = 0;
			while($r = $db->fetch_array($result)) {
				$r = daddslashes($r);
				$t = $db->get_one("SELECT itemid FROM {$tb} WHERE linkurl='$r[linkurl]'");
				if($t) continue;
				$db->query("INSERT {$tb} (catid,title,linkurl,islink,addtime,username,edittime,editor,status) VALUES ('$catid', '$r[title]', '$r[linkurl]', '1', '$r[addtime]', '$r[username]', '$DT_TIME', '$_username','3')");
				$itemid = $db->insert_id();
				$db->query("INSERT {$tb_data} (itemid) VALUES ('$itemid')");
				$i++;
			}
			dmsg('推送成功'.$i.'条新闻', "?moduleid=$moduleid&file=$file");
		} else {
			$itemid or msg('请选择新闻');
			$ids = implode(',', $itemid);
			include tpl('news_push', $module);
		}
	break;		
	case 'update':
		if(!isset($num)) {
			$num = 500;
		}
		if(!isset($fid)) {
			$r = $db->get_one("SELECT min(itemid) AS fid FROM {$DT_PRE}news");
			$fid = $r['fid'] ? $r['fid'] : 0;
		}
		if(!isset($tid)) {
			$r = $db->get_one("SELECT max(itemid) AS tid FROM {$DT_PRE}news");
			$tid = $r['tid'] ? $r['tid'] : 0;
		}
		isset($sid) or $sid = $fid;
		if($fid <= $tid) {
			$result = $db->query("SELECT itemid FROM {$DT_PRE}news WHERE itemid>=$fid ORDER BY itemid LIMIT 0,$num");
			if($db->affected_rows($result)) {
				while($r = $db->fetch_array($result)) {
					$itemid = $r['itemid'];
					$do->update($itemid);
				}
				$itemid += 1;
			} else {
				$itemid = $fid + $num;
			}
		} else {
			dmsg('更新成功', "?moduleid=$moduleid&file=$file");
		}
		msg('ID从'.$fid.'至'.($itemid-1).'更新成功'.progress($sid, $fid, $tid), "?moduleid=$moduleid&file=$file&action=$action&sid=$sid&fid=$itemid&tid=$tid&num=$num");
	break;
	case 'delete':
		$itemid or msg('请选择新闻');
		isset($recycle) ? $do->recycle($itemid) : $do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'restore':
		$itemid or msg('请选择新闻');
		$do->restore($itemid);
		dmsg('还原成功', $forward);
	break;
	case 'clear':
		$do->clear();
		dmsg('清空成功', $forward);
	break;
	case 'level':
		$itemid or msg('请选择新闻');
		$level = intval($level);
		$do->level($itemid, $level);
		dmsg('级别设置成功', $forward);
	break;
	case 'recycle':
		$lists = $do->get_list('status=0'.$condition, $dorder[$order]);
		$menuid = 4;
		include tpl('news', $module);
	break;
	case 'reject':
		if($itemid && !$psize) {
			$do->reject($itemid);
			dmsg('拒绝成功', $forward);
		} else {
			$lists = $do->get_list('status=1'.$condition, $dorder[$order]);
			$menuid = 3;
			include tpl('news', $module);
		}
	break;
	case 'check':
		if($itemid && !$psize) {
			$do->check($itemid);
			dmsg('审核成功', $forward);
		} else {
			$lists = $do->get_list('status=2'.$condition, $dorder[$order]);
			$menuid = 2;
			include tpl('news', $module);
		}
	break;
	default:
		$lists = $do->get_list('status=3'.$condition, $dorder[$order]);
		$menuid = 1;
		include tpl('news', $module);
	break;
}
?>