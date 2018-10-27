<?php
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['list_html'] || !$itemid) return false;
$gid = $catid = $itemid;
$GRP = get_group($gid);
if(!$GRP || $GRP['status'] != 3) return false;
$CAT = get_cat($GRP['catid']);
$GRP['managers'] = $GRP['manager'] ? explode('|', $GRP['manager']) : array();
$admin = '';
$typeid = 0;
$condition = 'status=3 AND gid='.$catid;
if($page == 1) {
	$items = $db->count($table, $condition);
	if($items != $GRP['post']) {
		$GRP['post'] = $items;
		$db->query("UPDATE {$table_group} SET post=$items WHERE itemid=$catid");
	}
} else {
	$items = $GRP['post'];
}
$pagesize = $MOD['pagesize'];
$showpage = 1;
$datetype = 5;
$template = $GRP['template'] ? $GRP['template'] : 'group';
$total = max(ceil($items/$MOD['pagesize']), 1);
if(isset($fid) && isset($num)) {
	$page = $fid;
	$topage = $fid + $num - 1;
	$total = $topage < $total ? $topage : $total;
}
for(; $page <= $total; $page++) {
	$offset = ($page-1)*$pagesize;
	$_CAT = array('catid' => $GRP['itemid'], 'catdir' => $GRP['filepath'], 'catname' => $GRP['title'], 'linkurl' => $GRP['linkurl']);
	$pages = listpages($_CAT, $items, $page, $pagesize);
	$tags = array();
	if($page == 1) {
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
		$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE {$condition} ORDER BY ".$MOD['order']." LIMIT {$offset},{$pagesize}");
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
	include DT_ROOT.'/include/seo.inc.php';
	$seo_title = $GRP['title'].$MOD['seo_name'].$seo_delimiter.$seo_page.$seo_modulename.$seo_delimiter.$seo_sitename;
	$head_keywords = $GRP['title'].$MOD['seo_name'].','.$MOD['name'];
	$head_description = dsubstr(dtrim($GRP['content']), 200);
	$destoon_task = "moduleid=$moduleid&html=list&catid=$catid&page=$page";
	if($EXT['mobile_enable']) $head_mobile = $MOD['mobile'].listurl($_CAT, $page);
	$filename = DT_ROOT.'/'.$MOD['moduledir'].'/'.listurl($_CAT, $page);
	$DT_PC = $GLOBALS['DT_PC'] = 1;
	ob_start();
	include template($template, $module);
	$data = ob_get_contents();
	ob_clean();
	if($DT['pcharset']) $filename = convert($filename, DT_CHARSET, $DT['pcharset']);
	file_put($filename, $data);
	if($page == 1) {
		$indexname = DT_ROOT.'/'.$MOD['moduledir'].'/'.listurl($_CAT, 0);
		if($DT['pcharset']) $indexname = convert($indexname, DT_CHARSET, $DT['pcharset']);
		file_copy($filename, $indexname);
	}
	if($EXT['mobile_enable']) {		
		$pages = mobile_pages($items, $page, $pagesize, $MOD['mobile'].listurl($_CAT, '{destoon_page}'));
		$time = strpos($MOD['order'], 'add') !== false ? 'addtime' : 'edittime';
		$lists = array();
		foreach($tags as $r) {
			$r['title'] = str_replace('style="color:', 'style="font-size:16px;color:', $r['title']);
			$r['linkurl'] = str_replace($MOD['linkurl'], $MOD['mobile'], $r['linkurl']);
			$r['date'] = timetodate($r[$time], 5);
			$lists[] = $r;
		}
		$back_link = $MOD['mobile'].$CAT['linkurl'];
		$head_name = $GRP['title'].$MOD['seo_name'];
		$filename = str_replace(DT_ROOT, DT_ROOT.'/mobile', $filename);
		$DT_PC = $GLOBALS['DT_PC'] = 0;
		ob_start();
		include template($template, $module);
		$data = ob_get_contents();
		ob_clean();
		file_put($filename, $data);
		if($page == 1) file_copy($filename, str_replace(DT_ROOT, DT_ROOT.'/mobile', $indexname));
	}
}
return true;
?>