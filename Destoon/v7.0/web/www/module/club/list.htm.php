<?php
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['list_html'] || !$catid) return false;
$CAT or $CAT = get_cat($catid);
if(!$CAT) return false;
unset($CAT['moduleid']);
extract($CAT);
$CP = $MOD['cat_property'] && $CAT['property'];
if($MOD['cat_property'] && $CAT['property']) {
	require_once DT_ROOT.'/include/property.func.php';
	$PPT = property_condition($catid);
}
$maincat = get_maincat($child ? $catid : $parentid, $moduleid);
$condition = 'status=3';
$condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
if($page == 1) {
	$items = $db->count($table_group, $condition);
	if($items != $CAT['item']) {
		$CAT['item'] = $items;
		$db->query("UPDATE {$DT_PRE}category SET item=$items WHERE catid=$catid");
	}
} else {
	$items = $CAT['item'];
}
$pagesize = $MOD['pagesize'];
$showpage = 1;
$datetype = 5;
$template = $CAT['template'] ? $CAT['template'] : ($MOD['template_list'] ? $MOD['template_list'] : 'list');
$total = max(ceil($items/$MOD['pagesize']), 1);
if(isset($fid) && isset($num)) {
	$page = $fid;
	$topage = $fid + $num - 1;
	$total = $topage < $total ? $topage : $total;
}
if($EXT['mobile_enable']) {
	include DT_ROOT.'/include/mobile.htm.php';	
	if($CAT['parentid']) {
		$PCAT = get_cat($CAT['parentid']);
		$back_link = $MOD['mobile'].$PCAT['linkurl'];
	} else {
		$back_link = $MOD['mobile'];
	}
}
for(; $page <= $total; $page++) {
	$offset = ($page-1)*$pagesize;
	$pages = listpages($CAT, $items, $page, $pagesize);
	$tags = array();
	$result = $db->query("SELECT * FROM {$table_group} WHERE {$condition} ORDER BY addtime DESC LIMIT {$offset},{$pagesize}");
	while($r = $db->fetch_array($result)) {
		$r['adddate'] = timetodate($r['addtime'], 5);
		$r['editdate'] = timetodate($r['edittime'], 5);
		if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = DT_SKIN.'image/lazy.gif" original="'.$r['thumb'];
		$r['alt'] = $r['title'];
		$r['title'] = set_style($r['title'], $r['style']);
		$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
		$r['introduce'] = dsubstr(dtrim($r['content']), 60, '...');
		$r['managers'] = $r['manager'] ? explode('|', $r['manager']) : array();
		$tags[] = $r;
	}
	$seo_file = 'list';
	include DT_ROOT.'/include/seo.inc.php';
	$destoon_task = "moduleid=$moduleid&html=list&catid=$catid&page=$page";
	if($EXT['mobile_enable']) $head_mobile = $MOD['mobile'].listurl($CAT, $page);
	$filename = DT_ROOT.'/'.$MOD['moduledir'].'/'.listurl($CAT, $page);
	$DT_PC = $GLOBALS['DT_PC'] = 1;
	ob_start();
	include template($template, $module);
	$data = ob_get_contents();
	ob_clean();
	if($DT['pcharset']) $filename = convert($filename, DT_CHARSET, $DT['pcharset']);
	file_put($filename, $data);
	if($page == 1) {
		$indexname = DT_ROOT.'/'.$MOD['moduledir'].'/'.listurl($CAT, 0);
		if($DT['pcharset']) $indexname = convert($indexname, DT_CHARSET, $DT['pcharset']);
		file_copy($filename, $indexname);
	}
	if($EXT['mobile_enable']) {		
		$pages = mobile_pages($items, $page, $pagesize, $MOD['mobile'].listurl($CAT, '{destoon_page}'));
		$time = strpos($MOD['order'], 'add') !== false ? 'addtime' : 'edittime';
		$lists = array();
		foreach($tags as $r) {
			$r['title'] = str_replace('style="color:', 'style="font-size:16px;color:', $r['title']);
			$r['linkurl'] = str_replace($MOD['linkurl'], $MOD['mobile'], $r['linkurl']);
			$r['date'] = timetodate($r[$time], 3);
			$lists[] = $r;
		}		
		$head_title = $head_name = $CAT['catname'];
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