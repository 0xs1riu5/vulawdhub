<?php
defined('DT_ADMIN') or exit('Access Denied');
$all = (isset($all) && $all) ? 1 : 0;
$menus = array (
    array('更新数据', '?moduleid='.$moduleid.'&file='.$file),
    array('模块首页', $MOD['linkurl'], ' target="_blank"'),
);
$all = (isset($all) && $all) ? 1 : 0;
$one = (isset($one) && $one) ? 1 : 0;
$this_forward = '?moduleid='.$moduleid.'&file='.$file;
$MOD['show_html'] = 0;
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
			$r = $db->get_one("SELECT min(userid) AS fid FROM {$table} WHERE groupid>0 {$sql}");
			$fid = $r['fid'] ? $r['fid'] : 0;
		}
		isset($sid) or $sid = $fid;
		if(!isset($tid)) {
			$r = $db->get_one("SELECT max(userid) AS tid FROM {$table} WHERE groupid>0 {$sql}");
			$tid = $r['tid'] ? $r['tid'] : 0;
		}
		if($update) {
			require DT_ROOT.'/module/'.$module.'/'.$module.'.class.php';
			$do = new $module();
		}
		isset($num) or $num = 50;
		if($fid <= $tid) {
			$result = $db->query("SELECT userid FROM {$table} WHERE userid>=$fid {$sql} ORDER BY userid LIMIT 0,$num ");
			if($db->affected_rows($result)) {
				while($r = $db->fetch_array($result)) {
					$userid = $r['userid'];
					$update ? $do->update($userid) : tohtml('show', $module);
				}
				$userid += 1;
			} else {
				$userid = $fid + $num;
			}
		} else {
			if($update) {
				$all ? msg('', '?moduleid='.$moduleid.'&file='.$file.'&action=index&all=1&one='.$one) : dmsg('更新成功', $this_forward);
			} else {
				if($one) dheader( '?file=html&action=back&mid='.$moduleid);
				$all ? msg($MOD['name'].'生成成功', $this_forward) : dmsg($MOD['name'].'生成成功', $this_forward);
			}
		}
		msg('ID从'.$fid.'至'.($userid-1).$MOD['name'].($update ? '更新' : '生成').'成功'.progress($sid, $fid, $tid), "?moduleid=$moduleid&file=$file&action=$action&sid=$sid&fid=$userid&tid=$tid&num=$num&update=$update&all=$all&one=$one");
	break;
	case 'passport':
		if(!isset($fid)) {
			$r = $db->get_one("SELECT min(userid) AS fid FROM {$table}");
			$fid = $r['fid'] ? $r['fid'] : 0;
		}
		isset($sid) or $sid = $fid;
		if(!isset($tid)) {
			$r = $db->get_one("SELECT max(userid) AS tid FROM {$table}");
			$tid = $r['tid'] ? $r['tid'] : 0;
		}
		isset($num) or $num = 50;
		if($fid <= $tid) {
			$result = $db->query("SELECT userid,username FROM {$table} WHERE userid>=$fid ORDER BY userid LIMIT 0,$num ");
			if($db->affected_rows($result)) {
				while($r = $db->fetch_array($result)) {
					$userid = $r['userid'];
					$username = $r['username'];
					$passport = get_user($userid, 'userid', 'passport');
					$passport = addslashes($passport);

					$db->query("UPDATE {$DT_PRE}comment SET passport='$passport' WHERE username='$username'");
					$db->query("UPDATE {$DT_PRE}know SET passport='$passport' WHERE username='$username'");
					$db->query("UPDATE {$DT_PRE}know_answer SET passport='$passport' WHERE username='$username'");
					$db->query("UPDATE {$DT_PRE}know_expert SET passport='$passport' WHERE username='$username'");
					$db->query("UPDATE {$DT_PRE}know_vote SET passport='$passport' WHERE username='$username'");
					$db->query("UPDATE {$DT_PRE}club SET passport='$passport' WHERE username='$username'");
					$db->query("UPDATE {$DT_PRE}club_fans SET passport='$passport' WHERE username='$username'");
					$db->query("UPDATE {$DT_PRE}club_group SET passport='$passport' WHERE username='$username'");
					$db->query("UPDATE {$DT_PRE}club_reply SET passport='$passport' WHERE username='$username'");
					$db->query("UPDATE {$DT_PRE}club SET replyer='$passport' WHERE replyuser='$username'");
				}
				$userid += 1;
			} else {
				$userid = $fid + $num;
			}
		} else {
			dmsg('更新成功', $this_forward);
		}
		msg('ID从'.$fid.'至'.($userid-1).'昵称更新成功'.progress($sid, $fid, $tid), "?moduleid=$moduleid&file=$file&action=$action&sid=$sid&fid=$userid&tid=$tid&num=$num&all=$all&one=$one");
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
	default:
		$r = $db->get_one("SELECT min(userid) AS fid,max(userid) AS tid FROM {$table}");
		$fid = $r['fid'] ? $r['fid'] : 0;
		$tid = $r['tid'] ? $r['tid'] : 0;
		include tpl('html', $module);
	break;
}
?>