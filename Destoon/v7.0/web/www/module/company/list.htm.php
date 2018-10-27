<?php
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['list_html'] || !$catid) return false;
$CAT or $CAT = get_cat($catid);
if(!$CAT) return false;
unset($CAT['moduleid']);
extract($CAT);
$maincat = get_maincat($child ? $catid : $parentid, $moduleid);
$condition = "groupid>5 and catids like '%,".$catid.",%'";
if($page == 1) {
	$items = $db->count($table, $condition);
	if($items != $CAT['item']) {
		$CAT['item'] = $items;
		$db->query("UPDATE {$DT_PRE}category SET item=$items WHERE catid=$catid");
	}
} else {
	$items = $CAT['item'];
}
$pagesize = $MOD['pagesize'];
$showpage = 1;
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
	$tags = $_tags = $ids = array();
	if($items) {
		$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE {$condition} ORDER BY ".$MOD['order']." LIMIT {$offset},{$pagesize}");
		while($r = $db->fetch_array($result)) {
			if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = DT_SKIN.'image/lazy.gif" original="'.$r['thumb'];
			$tags[] = $r;
		}
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