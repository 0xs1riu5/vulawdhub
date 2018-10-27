<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/webpage.class.php';
isset($item) or $item = 1;
$do = new webpage();
$do->item = $item;
$menus = array (
    array('添加单页', '?moduleid='.$moduleid.'&file='.$file.'&item='.$item.'&action=add'),
    array('单页列表', '?moduleid='.$moduleid.'&file='.$file.'&item='.$item),
    array('全部单页', '?moduleid='.$moduleid.'&file='.$file.'&item='.$item.'&itemid=1'),
    array('创建新组', '?moduleid='.$moduleid.'&file='.$file.'&item='.$item.'&action=group'),
    array('更新数据', '?moduleid='.$moduleid.'&file='.$file.'&item='.$item.'&action=html'),
);
if($_catids || $_areaids) require DT_ROOT.'/admin/admin_check.inc.php';
$this_forward = '?moduleid='.$moduleid.'&file='.$file.'&item='.$item;
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
			$filepath = 'about/';
			$filename = '';
			$menuid = 0;
			include tpl('webpage_edit', $module);
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
			if($islink) {
				$filepath = $filename = '';
			} else {
				$filestr = str_replace(DT_PATH, '', $linkurl);
				$filepath = strpos($filestr, '/') !== false ? dirname($filestr).'/' : '';
				$filename = basename($filestr);
			}
			$menuid = 1;
			include tpl('webpage_edit', $module);
		}
	break;
	case 'group':
		if($submit) {
			$name or msg('请填写新组名称');
			preg_match("/^[a-z0-9]{1,}$/", $item) or msg('新组标识应为数字和字母的组合');
			$name = addslashes($name);
			$url = '?moduleid=3&file=webpage&item='.$item;
			$db->query("INSERT INTO {$DT_PRE}admin (userid,title,url,style) VALUES('$_userid','$name','$url','#FF0000')");
			require_once DT_ROOT.'/admin/admin.class.php';
			$do = new admin;
			$do->cache_menu($_userid);
			msg('添加成功<script type="text/javascript">window.parent.frames[0].location.reload();</script>', $url);
		} else {
			$name = '新组名称';
			$item = 'new';
			include tpl('webpage_group', $module);
		}
	break;
	case 'order':
		$do->order($listorder);
		dmsg('排序成功', $forward);
	break;
	case 'html':
		$all = (isset($all) && $all) ? 1 : 0;
		$one = (isset($one) && $one) ? 1 : 0;
		if(!isset($num)) {
			$num = 50;
		}
		if(!isset($fid)) {
			$r = $db->get_one("SELECT min(itemid) AS fid FROM {$DT_PRE}webpage");
			$fid = $r['fid'] ? $r['fid'] : 0;
		}
		isset($sid) or $sid = $fid;
		if(!isset($tid)) {
			$r = $db->get_one("SELECT max(itemid) AS tid FROM {$DT_PRE}webpage");
			$tid = $r['tid'] ? $r['tid'] : 0;
		}
		if($fid <= $tid) {
			$result = $db->query("SELECT itemid FROM {$DT_PRE}webpage WHERE itemid>=$fid ORDER BY itemid LIMIT 0,$num");
			if($db->affected_rows($result)) {
				while($r = $db->fetch_array($result)) {
					$itemid = $r['itemid'];
					tohtml('webpage', $module);
				}
				$itemid += 1;
			} else {
				$itemid = $fid + $num;
			}
		} else {
			if($all) dheader("?moduleid=3&file=gift&action=html&all=1&item=$item&one=$one");
			dmsg('生成成功', "?moduleid=$moduleid&file=$file&item=$item");
		}
		msg('ID从'.$fid.'至'.($itemid-1).'[单页]生成成功'.progress($sid, $fid, $tid), "?moduleid=$moduleid&file=$file&action=$action&sid=$sid&fid=$itemid&tid=$tid&num=$num&item=$item&all=$all&one=$one");
	break;
	case 'delete':
		$itemid or msg('请选择单页');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'level':
		$itemid or msg('请选择单页');
		$level = intval($level);
		$do->level($itemid, $level);
		dmsg('级别设置成功', $forward);
	break;
	default:
		$sfields = array('按条件', '标题', '链接地址', '内容', '绑定域名');
		$dfields = array('title','title','linkurl','content','domain');
		$sorder  = array('结果排序方式', '更新时间降序', '更新时间升序', '浏览次数降序', '浏览次数升序');
		$dorder  = array('listorder DESC,itemid DESC', 'edittime DESC', 'edittime ASC', 'hits DESC', 'hits ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
		$level = isset($level) ? intval($level) : 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select  = dselect($sorder, 'order', '', $order);
		$level_select = level_select('level', '级别', $level);
		$condition = $itemid ? "1" : "item='$item'";
		if($_areaids) $condition .= " AND areaid IN (".$_areaids.")";//CITY
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($level) $condition .= " AND level=$level";
		if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
		$lists = $do->get_list($condition, $dorder[$order]);
		include tpl('webpage', $module);
	break;
}
?>