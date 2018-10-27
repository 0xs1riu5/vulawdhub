<?php
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('更新数据', '?moduleid='.$moduleid.'&file='.$file),
    array('模块首页', $MOD['linkurl'], ' target="_blank"'),
);
$all = (isset($all) && $all) ? 1 : 0;
$one = (isset($one) && $one) ? 1 : 0;
$this_forward = '?moduleid='.$moduleid.'&file='.$file;
switch($action) {
	case 'all':
		msg('', '?moduleid='.$moduleid.'&file='.$file.'&action=show&update=1&all=1&one='.$one);
	break;
	case 'index':
		tohtml('index', $module);
		$all ? msg($MOD['name'].'首页生成成功', '?moduleid='.$moduleid.'&file='.$file.'&action=list&all=1&one='.$one) : dmsg($MOD['name'].'首页生成成功', $this_forward);
	break;
	case 'list':
		if(!$MOD['list_html']) {
			$all ? msg($MOD['name'].'列表生成成功', '?moduleid='.$moduleid.'&file='.$file.'&action=show&all='.$all.'&one='.$one) : msg($MOD['name'].'列表生成成功', $this_forward);
		}
		if(isset($catids)) {
			$CAT = $db->get_one("SELECT * FROM {$DT_PRE}category WHERE moduleid=$moduleid AND catid>$catids ORDER BY catid");
			if($CAT) {
				$bcatid = $catid = $CAT['catid'];
				$total = max(ceil($CAT['item']/$MOD['pagesize']), 1);
				$num = 50;
				$bfid = $fid;
				isset($fpage) or $fpage = 1;
				if($fpage <= $total) {
					$fid = $fpage;
					tohtml('list', $module);
					$fid = $bfid;
					msg($MOD['name'].' ['.$CAT['catname'].'] 第'.$fpage.'页至第'.($fpage+$num-1).'页生成成功'.progress(0, $fid, $tid), '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&catids='.$catids.'&fid='.$fid.'&tid='.$tid.'&all='.$all.'&one='.$one.'&fpage='.($fpage+$num));
				}
				$fid++;
				msg($MOD['name'].' ['.$CAT['catname'].'] 生成成功'.progress(0, $fid, $tid), '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&catids='.$catid.'&fid='.$fid.'&tid='.$tid.'&all='.$all.'&one='.$one);
			} else {
				$all ? msg($MOD['name'].'列表生成成功', '?moduleid='.$moduleid.'&file='.$file.'&action=show&all='.$all.'&one='.$one) : msg($MOD['name'].'列表生成成功', $this_forward);
			}		
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}category WHERE moduleid=$moduleid");
			$tid = $r['num'];
			msg('', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&catids=0&fid=1&&tid='.$tid.'&all='.$all.'&one='.$one);
		}
	break;
	case 'show':
		$update = (isset($update) && $update) ? 1 : 0;
		if(!$update && !$MOD['show_html']) {
			if($one) dheader( '?file=html&action=back&mid='.$moduleid);
			$all ? msg($MOD['name'].'生成成功', $this_forward) : dmsg($MOD['name'].'生成成功', $this_forward);
		}
		$catid = isset($catid) ? intval($catid) : '';
		$sql = $catid ? " AND catid=$catid" : '';
		if(!isset($fid)) {
			$r = $db->get_one("SELECT min(itemid) AS fid FROM {$table} WHERE status>2 {$sql}");
			$fid = $r['fid'] ? $r['fid'] : 0;
		}
		isset($sid) or $sid = $fid;
		if(!isset($tid)) {
			$r = $db->get_one("SELECT max(itemid) AS tid FROM {$table} WHERE status>2 {$sql}");
			$tid = $r['tid'] ? $r['tid'] : 0;
		}
		if($update) {
			require DT_ROOT.'/module/'.$module.'/'.$module.'.class.php';
			$do = new $module($moduleid);
		}
		isset($num) or $num = 100;
		if($fid <= $tid) {
			$result = $db->query("SELECT itemid FROM {$table} WHERE status>2 AND itemid>=$fid {$sql} ORDER BY itemid LIMIT 0,$num ");
			if($db->affected_rows($result)) {
				while($r = $db->fetch_array($result)) {
					$itemid = $r['itemid'];
					$update ? $do->update($itemid) : tohtml('show', $module);
				}
				$itemid += 1;
			} else {
				$itemid = $fid + $num;
			}
		} else {
			if($update) {
				$all ? msg('', '?moduleid='.$moduleid.'&file='.$file.'&action=index&all=1&one='.$one) : dmsg('更新成功', $this_forward);
			} else {
				if($one) dheader( '?file=html&action=back&mid='.$moduleid);
				$all ? msg($MOD['name'].'生成成功', $this_forward) : dmsg($MOD['name'].'生成成功', $this_forward);
			}
		}
		msg('ID从'.$fid.'至'.($itemid-1).$MOD['name'].($update ? '更新' : '生成').'成功'.progress($sid, $fid, $tid), "?moduleid=$moduleid&file=$file&action=$action&sid=$sid&fid=$itemid&tid=$tid&num=$num&update=$update&all=$all&one=$one");
	break;
	case 'update_resume':
		$table = $table_resume;
		$catid = isset($catid) ? intval($catid) : '';
		$sql = $catid ? " AND catid=$catid" : '';
		if(!isset($fid)) {
			$r = $db->get_one("SELECT min(itemid) AS fid FROM {$table} WHERE status>2 {$sql}");
			$fid = $r['fid'] ? $r['fid'] : 0;
		}
		if(!isset($tid)) {
			$r = $db->get_one("SELECT max(itemid) AS tid FROM {$table} WHERE status>2 {$sql}");
			$tid = $r['tid'] ? $r['tid'] : 0;
		}
		require DT_ROOT.'/module/'.$module.'/resume.class.php';
		$do = new resume($moduleid);
		isset($num) or $num = 100;
		if($fid <= $tid) {
			$result = $db->query("SELECT itemid FROM {$table} WHERE status>2 AND itemid>=$fid {$sql} ORDER BY itemid LIMIT 0,$num ");
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
			dmsg('更新成功', $this_forward);
		}
		msg('ID从'.$fid.'至'.($itemid-1).'简历更新成功', "?moduleid=$moduleid&file=$file&action=$action&fid=$itemid&tid=$tid&num=$num");
	break;
	case 'cate':
		$catid or msg('请选择分类');
		isset($num) or $num = 50;
		isset($fid) or $fid = 1;
		$total = max(ceil($CAT['item']/$MOD['pagesize']), 1);
		if($fpage && $tpage) {
			$fid = $fpage;
			$num = $tpage - $fpage + 1;
			tohtml('list', $module);
			dmsg('生成成功', $this_forward);
		}
		if($fid <= $total) {
			tohtml('list', $module);
			msg('第'.$fid.'页至第'.($fid+$num-1).'页生成成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&catid='.$catid.'&fid='.($fid+$num).'&num='.$num.'&fpage='.$fpage.'&tpage='.$tpage);
		} else {
			dmsg('生成成功', $this_forward);
		}
	break;
	case 'item':
		$catid or msg('请选择分类');
		msg('', '?moduleid='.$moduleid.'&file='.$file.'&action=show&catid='.$catid.'&num='.$num);
	break;
	default:
		$r = $db->get_one("SELECT min(itemid) AS fid,max(itemid) AS tid FROM {$table} WHERE status=3");
		$fid = $r['fid'] ? $r['fid'] : 0;
		$tid = $r['tid'] ? $r['tid'] : 0;
		$r = $db->get_one("SELECT min(itemid) AS fid,max(itemid) AS tid FROM {$table_resume} WHERE status>2");
		$rfid = $r['fid'] ? $r['fid'] : 0;
		$rtid = $r['tid'] ? $r['tid'] : 0;
		include tpl('html', $module);
	break;
}
?>