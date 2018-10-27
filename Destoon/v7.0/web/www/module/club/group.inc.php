<?php 
defined('IN_DESTOON') or exit('Access Denied');
$gid = $catid;
$GRP = get_group($gid);
if(!$GRP || $GRP['status'] != 3) include load('404.inc');
if($GRP['list_type'] && !is_fans($GRP)) {
	$action = 'list';
	$head_title = lang('message->without_permission');
	exit(include template('nofans', $module));
}
$CAT = get_cat($GRP['catid']);
$GRP['managers'] = $GRP['manager'] ? explode('|', $GRP['manager']) : array();
$admin = is_admin($GRP);
$typeid = isset($typeid) ? intval($typeid) : 0;
isset($TYPE[$typeid]) or $typeid = 0;
if($DT_PC) {
	//
} else {
	if($typeid < 1) $typeid = 1;
}
$condition = 'status=3 AND gid='.$catid;
if($typeid) {
	switch($typeid) {
		case 1:
			$MOD['order'] = 'addtime DESC';
		break;
		case 2:
			$MOD['order'] = 'replytime DESC';
		break;
		case 3:
			$condition .= " AND level>0";
		break;
		case 4:
			$condition .= " AND addtime>".($DT_TIME - 86400*30);
			$MOD['order'] = 'hits DESC';
		break;
	}
}
if($typeid) {
	$items = $db->count($table, $condition, $CFG['db_expires']);
} else {
	if($page == 1) {
		$items = $db->count($table, $condition, $CFG['db_expires']);
		if($items != $GRP['post']) {
			$GRP['post'] = $items;
			$db->query("UPDATE {$table_group} SET post=$items WHERE itemid=$catid");
		}
	} else {
		$items = $GRP['post'];
	}
}
$pagesize = $MOD['pagesize'];
$offset = ($page-1)*$pagesize;
if($typeid) {
	$pages = pages($items, $page, $pagesize);
} else {
	$pages = listpages(array('catid' => $GRP['itemid'], 'catdir' => $GRP['filepath'], 'catname' => $GRP['title'], 'linkurl' => $GRP['linkurl']), $items, $page, $pagesize);
}
$tags = array();
if($typeid == 0 && $page == 1) {
	$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE status=3 AND ontop=2 ORDER BY addtime DESC LIMIT ".$MOD['maxontop'], 'CACHE');
	while($r = $db->fetch_array($result)) {
		$r['adddate'] = timetodate($r['addtime'], 5);
		$r['editdate'] = timetodate($r['edittime'], 5);
		if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = DT_SKIN.'image/lazy.gif" original="'.$r['thumb'];
		$r['alt'] = $r['title'];
		$r['title'] = set_style($r['title'], $r['style']);
		$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
		if(!$r['username']) $r['username'] = 'Guest';
		$tags[] = $r;
	}
	$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE status=3 AND gid=$gid AND ontop=1 ORDER BY addtime DESC LIMIT ".$MOD['maxontop'], 'CACHE');
	while($r = $db->fetch_array($result)) {
		$r['adddate'] = timetodate($r['addtime'], 5);
		$r['editdate'] = timetodate($r['edittime'], 5);
		if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = DT_SKIN.'image/lazy.gif" original="'.$r['thumb'];
		$r['alt'] = $r['title'];
		$r['title'] = set_style($r['title'], $r['style']);
		$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
		if(!$r['username']) $r['username'] = 'Guest';
		$tags[] = $r;
	}
}
if($items) {
	$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE {$condition} ORDER BY ".$MOD['order']." LIMIT {$offset},{$pagesize}", ($CFG['db_expires'] && $page == 1) ? 'CACHE' : '', $CFG['db_expires']);
	while($r = $db->fetch_array($result)) {
		if($r['ontop']) continue;
		$r['adddate'] = timetodate($r['addtime'], 5);
		$r['editdate'] = timetodate($r['edittime'], 5);
		if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = DT_SKIN.'image/lazy.gif" original="'.$r['thumb'];
		$r['alt'] = $r['title'];
		$r['title'] = set_style($r['title'], $r['style']);
		$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
		if(!$r['username']) $r['username'] = 'Guest';
		$tags[] = $r;
	}
	$db->free_result($result);
}
$_CAT = array('catid' => $GRP['itemid'], 'catdir' => $GRP['filepath'], 'catname' => $GRP['title']);
if($DT_PC) {	
	if($EXT['mobile_enable']) $head_mobile = $MOD['mobile'].listurl($_CAT, $page);
} else {
	$time = strpos($MOD['order'], 'add') !== false ? 'addtime' : 'replytime';
	if(strpos($DT_URL, 'typeid') === false) {
		$pages = mobile_pages($items, $page, $pagesize, $MOD['mobile'].listurl($_CAT, '{destoon_page}'));
	} else {
		$pages = mobile_pages($items, $page, $pagesize);
	}
	$lists = array();
	foreach($tags as $r) {
		$r['title'] = str_replace('style="color:', 'style="font-size:16px;color:', $r['title']);
		$r['linkurl'] = str_replace($MOD['linkurl'], $MOD['mobile'], $r['linkurl']);
		$r['date'] = timetodate($r[$time], 5);
		$lists[] = $r;
	}
	$back_link = $MOD['mobile'].$CAT['linkurl'];
	$head_name = $GRP['title'].$MOD['seo_name'];
}
$showpage = 1;
$datetype = 5;
include DT_ROOT.'/include/seo.inc.php';
$seo_title = ($typeid ? $TYPE[$typeid].$seo_delimiter : '').$GRP['title'].$MOD['seo_name'].$seo_delimiter.$seo_page.$seo_modulename.$seo_delimiter.$seo_sitename;
$head_keywords = $GRP['title'].$MOD['seo_name'].','.$MOD['name'];
$head_description = dsubstr(dtrim($GRP['content']), 200);
$template = $GRP['template'] ? $GRP['template'] : ($MOD['template_group'] ? $MOD['template_group'] : 'group');
include template($template, $module);
?>