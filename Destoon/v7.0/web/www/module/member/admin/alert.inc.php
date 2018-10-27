<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/alert.class.php';
$do = new alert();
$menus = array (
    array('添加提醒', '?moduleid='.$moduleid.'&file='.$file.'&action=add'),
    array('贸易提醒', '?moduleid='.$moduleid.'&file='.$file),
    array('审核提醒', '?moduleid='.$moduleid.'&file='.$file.'&action=check'),
    array('发送商机', '?moduleid='.$moduleid.'&file='.$file.'&action=send'),
);
$mids = array();
$tmp = explode('|', $MOD['alertid']);
foreach($tmp as $v) {
	if($v > 4 && isset($MODULE[$v])) $mids[] = $v;
}
if(in_array($action, array('', 'check'))) {
	$sfields = array('按条件', '关键词', '会员名', 'Email');
	$dfields = array('word', 'word', 'username', 'email');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '发送时间降序', '发送时间升序', '发送频率降序', '发送频率升序');
	$dorder  = array('addtime DESC', 'addtime DESC', 'addtime ASC', 'sendtime DESC', 'sendtime ASC', 'rate DESC', 'rate ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;
	$areaid = isset($areaid) ? intval($areaid) : 0;

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	if($mid) $condition .= " AND mid=$mid";
}
switch($action) {
	case 'send':
		if(isset($send)) {
			if(isset($first)) {
				$item = array();
				$item['title'] = $title;
				$item['total'] = $total;
				$item['num'] = $num;
				$item['sql'] = $sql;
				$item['ord'] = $ord;
				$item['template'] = $template;
				cache_write('alert-'.$_userid.'.php', $item);
			} else {
				$item = cache_read('alert-'.$_userid.'.php');
				extract($item);
			}
			if(!isset($num)) {
				$num = 5;
			}
			if(!isset($fid)) {
				$r = $db->get_one("SELECT min(itemid) AS fid FROM {$DT_PRE}alert");
				$fid = $r['fid'] ? $r['fid'] : 0;
			}
			isset($sid) or $sid = $fid;
			if(!isset($tid)) {
				$r = $db->get_one("SELECT max(itemid) AS tid FROM {$DT_PRE}alert");
				$tid = $r['tid'] ? $r['tid'] : 0;
			}
			if($fid <= $tid) {
				$result = $db->query("SELECT * FROM {$DT_PRE}alert WHERE itemid>=$fid AND status=3 ORDER BY itemid LIMIT 0,$num");
				$_MOD = $MOD;
				if($db->affected_rows($result)) {
					while($r = $db->fetch_array($result)) {
						$itemid = $r['itemid'];
						$rate = $r['rate'];
						if($rate && $r['sendtime'] && $DT_TIME - $rate*86400 < $r['sendtime']) continue;
						$kw = $r['word'];
						$mid = $r['mid'];
						$catid = $r['catid'];
						$areaid = $r['areaid'];
						$MOD = cache_read('module-'.$mid.'.php');
						$CAT = get_cat($catid);
						$condition = "status=3 AND addtime>$r[sendtime]";
						if($kw) $condition .= " AND keyword LIKE '%$kw%'";
						if($areaid) $condition .= $AREA[$areaid]['child'] ? " AND areaid IN (".$AREA[$areaid]['arrchildid'].")" : " AND areaid=$areaid";
						if($catid) $condition .= $CAT['child'] ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
						if($sql) $condition .= ' '.$sql;
						if($ord) $condition .= ' ORDER BY '.$ord;
						$lists = array();
						$results = $db->query("SELECT * FROM ".get_table($mid)." WHERE $condition LIMIT 0,$total");
						while($rs = $db->fetch_array($results)) {
							if(strpos($rs['linkurl'], '://') === false) $rs['linkurl'] =  $MOD['linkurl'].$rs['linkurl'];
							$lists[] = $rs;
						}
						$content = ob_template($template ? $template : 'alert', 'mail');
						send_mail($r['email'], $title, $content);
						$db->query("UPDATE {$DT_PRE}alert SET sendtime=$DT_TIME WHERE itemid=$itemid");
					}
					$itemid += 1;
				} else {
					$itemid = $fid + $num;
				}
				$MOD = $_MOD;
			} else {
				dmsg('发送成功', "?moduleid=$moduleid&file=$file");
			}
			msg('ID从'.$fid.'至'.($itemid-1).'发送成功'.progress($sid, $fid, $tid), "?moduleid=$moduleid&file=$file&action=$action&sid=$sid&fid=$itemid&tid=$tid&num=$num&send=1");
		} else {
			$item = cache_read('alert-'.$_userid.'.php');
			if($item) {
				extract($item);
			} else {
				$title = $DT['sitename'].'[贸易提醒]';
				$total = 30;
				$num = 5;
				$template = '';
				$sql = 'AND vip>0';
				$ord = 'addtime DESC';
			}
			include tpl('alert_send', $module);
		}
	break;
	case 'add':
		if($submit) {			
			$usernames = explode("\n", trim($post['username']));
			foreach($usernames as $username) {
				$username = trim($username);
				if(!$username) continue;
				$user = userinfo($username);
				if(!$user) continue;
				$post['username'] = $username;
				$post['email'] = $user['email'];
				$post['addtime'] = $DT_TIME;
				if($do->pass($post)) {
					$do->add($post);
				} else {
					msg($do->errmsg);
				}
			}
			dmsg('添加成功', '?moduleid='.$moduleid.'&file='.$file);
		} else {
			$mid or $mid = $mids[0];
			$mid or msg();
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
			$word = '';
			$catid = $areaid = $rate = 0;
			$status = 3;
			$menuid = 1;
			include tpl('alert_add', $module);
		}
	break;
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		$r = $do->get_one();
		if(!$r) msg();
		if($submit) {
			if($do->pass($post)) {
				$user = userinfo($post['username']);
				if($user) {
					$email = $post['email'] = $user['email'];
					$do->edit($post);
					$db->query("UPDATE {$DT_PRE}alert SET email='$email' WHERE username='$post[username]'");
					dmsg('修改成功', $forward);
				} else {
					msg('会员不存在');
				}
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($r);
			$menuid = 1;
			include tpl('alert_edit', $module);
		}
	break;
	case 'reject':		
		$itemid or msg('请选择贸易提醒');
		$do->check($itemid, 2);
		dmsg('撤销成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择贸易提醒');
		$do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'check':
		if($itemid && !$psize) {
			$do->check($itemid);
			dmsg('审核成功', $forward);
		} else {
			$lists = $do->get_list('status=2'.$condition, $dorder[$order]);
			if($lists) {
				$tmp = $MOD['linkurl'];
				foreach($lists as $k=>$v) {
					if($v['catid']) {
						$lists[$k]['cate'] = cat_pos(get_cat($v['catid']), '-', 1);
					}
				}
				$MOD['linkurl'] = $tmp;
			}
			$menuid = 2;
			include tpl('alert', $module);
		}
	break;
	default:
		$lists = $do->get_list('status=3'.$condition, $dorder[$order]);
		if($lists) {
			$tmp = $MOD['linkurl'];
			foreach($lists as $k=>$v) {
				if($v['catid']) {
					$lists[$k]['cate'] = cat_pos(get_cat($v['catid']), '-', 1);
				}
			}
			$MOD['linkurl'] = $tmp;
		}
		$menuid = 1;
		include tpl('alert', $module);
	break;
}
?>