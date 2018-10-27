<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($action == 'clear') {
	require DT_ROOT.'/include/post.func.php';
	isset($date) or $date = '';
	if($itemid) {
		$db->query("DELETE FROM {$table_view} WHERE uid='".$_username.'|'.$itemid."'");
	} else if(is_date($date)) {
		$db->query("DELETE FROM {$table_view} WHERE username='$_username' AND lasttime>=".strtotime($date.' 00:00:00')." AND lasttime<=".strtotime($date.' 23:59:59'));
	} else {
		$db->query("DELETE FROM {$table_view} WHERE username='$_username'");
	}
	dheader('?rand='.$DT_TIME);
} else {
	$lists = $tags = $views = $ids = array();
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table_view} WHERE username='$_username'");
	$items = $r['num'];
	$pages = pages($items, $page, $pagesize);
	if($items) {
		$result = $db->query("SELECT * FROM {$table_view} WHERE username='$_username' ORDER BY lasttime DESC");
		while($r = $db->fetch_array($result)) {
			$ids[] = $r['itemid'];
			$views[] = $r;
		}
		$result = $db->query("SELECT * FROM {$table} WHERE itemid IN (".implode(',', $ids).") ORDER BY addtime DESC");
		while($r = $db->fetch_array($result)) {
			if($r['status'] != 3) continue;
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
			$r['mobile'] = $MOD['mobile'].$r['linkurl'];
			$tags[$r['itemid']] = $r;
		}
		foreach($views as $v) {
			$date = timetodate($v['lasttime'], 3);
			$tags[$v['itemid']]['lasttime'] = $v['lasttime'];
			$lists[$date][] = $tags[$v['itemid']];
		}
	}
}
$head_title = $L['view_title'].$DT['seo_delimiter'].$MOD['name'];
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	$back_link = $MOD['mobile'];
	$head_name = $L['view_title'];
	$foot = '';
}
include template($MOD['template_view'] ? $MOD['template_view'] : 'view', $module);
?>