<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($DT['rewrite'] && $DT['search_rewrite'] && $_SERVER["REQUEST_URI"] && $_SERVER['QUERY_STRING']) {
	$_URL = rewrite($_SERVER["REQUEST_URI"]);
	if($_URL != $_SERVER["REQUEST_URI"]) dheader($_URL);
}
if($DT_PC) {
	if(!check_group($_groupid, $MOD['group_search'])) include load('403.inc');
	require DT_ROOT.'/include/post.func.php';
	include load('search.lang');
	$MS = cache_read('module-2.php');
	$modes = explode('|', $L['choose'].'|'.$MS['com_mode']);
	$types = explode('|', $L['choose'].'|'.$MS['com_type']);
	$sizes = explode('|', $L['choose'].'|'.$MS['com_size']);
	$vips = array($L['vip_level'], VIP, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
	$thumb = isset($thumb) ? intval($thumb) : 0;
	//$vip = isset($vip) ? intval($vip) : 0;
	$mincapital = isset($mincapital) ? dround($mincapital) : '';
	$mincapital or $mincapital = '';
	$maxcapital = isset($maxcapital) ? dround($maxcapital) : '';
	$maxcapital or $maxcapital = '';
	if(!$areaid && $cityid && strpos($DT_URL, 'areaid') === false) {
		$areaid = $cityid;
		$ARE = $AREA[$cityid];
	}
	isset($mode) && isset($modes[$mode]) or $mode = 0;
	isset($type) && isset($types[$type]) or $type = 0;
	isset($size) && isset($sizes[$size]) or $size = 0;
	isset($vip) && isset($vips[$vip]) or $vip = 0;
	$category_select = ajax_category_select('catid', $L['all_category'], $catid, $moduleid);
	$area_select = ajax_area_select('areaid', $L['all_area'], $areaid);
	$mode_select = dselect($modes, 'mode', '', $mode);
	$type_select = dselect($types, 'type', '', $type);
	$size_select = dselect($sizes, 'size', '', $size);
	$vip_select = dselect($vips, 'vip', '', $vip);
	$tags = array();
	if($DT_QST) {
		if($kw) {
			if(strlen($kw) < $DT['min_kw'] || strlen($kw) > $DT['max_kw']) message(lang($L['word_limit'], array($DT['min_kw'], $DT['max_kw'])), $MOD['linkurl'].'search.php');
			if($DT['search_limit'] && $page == 1) {
				if(($DT_TIME - $DT['search_limit']) < get_cookie('last_search')) message(lang($L['time_limit'], array($DT['search_limit'])), $MOD['linkurl'].'search.php');
				set_cookie('last_search', $DT_TIME);
			}
		}
		$fds = $MOD['fields'];
		$condition = "groupid>5 AND catids<>''";
		if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
		if($mode) $condition .= " AND mode LIKE '%$modes[$mode]%'";
		if($type) $condition .= " AND type='$types[$type]'";
		if($size) $condition .= " AND size='$sizes[$size]'";
		if($catid) $condition .= " AND catids LIKE '%,".$catid.",%'";
		if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
		if($thumb) $condition .= " AND thumb<>''";
		if($vip) $condition .= $vip == 1 ? " AND vip>0" : " AND vip=$vip-1";
		if($mincapital)  $condition .= " AND capital>$mincapital";
		if($maxcapital)  $condition .= " AND capital<$maxcapital";
		$pagesize = $MOD['pagesize'];
		$offset = ($page-1)*$pagesize;
		$items = $db->count($table, $condition, $DT['cache_search']);
		$pages = pages($items, $page, $pagesize);
		if($items) {
			$order = $MOD['order'] ? " ORDER BY ".$MOD['order'] : '';
			$result = $db->query("SELECT {$fds} FROM {$table} WHERE {$condition}{$order} LIMIT {$offset},{$pagesize}", $DT['cache_search'] && $page == 1 ? 'CACHE' : '', $DT['cache_search']);
			if($kw) {
				$replacef = explode(' ', $kw);
				$replacet = array_map('highlight', $replacef);
			}
			while($r = $db->fetch_array($result)) {
				if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = DT_SKIN.'image/lazy.gif" original="'.$r['thumb'];
				if($kw) $r['company'] = str_replace($replacef, $replacet, $r['company']);
				$tags[] = $r;
			}
			$db->free_result($result);
			if($page == 1 && $kw) keyword($kw, $items, $moduleid);
		}
	}
	$showpage = 1;
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	if($kw) {
		check_group($_groupid, $MOD['group_search']) or message($L['msg_no_search']);
	} else if($catid) {
		$CAT or message($L['msg_not_cate']);
		if(!check_group($_groupid, $MOD['group_list']) || !check_group($_groupid, $CAT['group_list'])) message($L['msg_no_right']);
	} else {
		check_group($_groupid, $MOD['group_index']) or message($L['msg_no_right']);
	}
	$head_title = $MOD['name'].$DT['seo_delimiter'].$head_title;
	if($kw) $head_title = $kw.$DT['seo_delimiter'].$head_title;
	if(!$areaid && $cityid && strpos($DT_URL, 'areaid') === false) {
		$areaid = $cityid;
		$ARE = $AREA[$cityid];
	}
	$condition = "groupid>5";
	if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
	if($catid) $condition .= " AND catids like '%,".$catid.",%'";
	if($areaid) $condition .= $ARE['child'] ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition", 'CACHE');
	$items = $r['num'];
	$pages = mobile_pages($items, $page, $pagesize);
	$lists = array();
	if($items) {
		$order = $MOD['order'];
		$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			if($kw) $r['company'] = str_replace($kw, '<b class="f_red">'.$kw.'</b>', $r['company']);
			$lists[] = $r;
		}
		$db->free_result($result);
	}
	$back_link = $MOD['mobile'];
	$head_name = $MOD['name'].$L['search'];
}
$seo_file = 'search';
include DT_ROOT.'/include/seo.inc.php';
include template($MOD['template_search'] ? $MOD['template_search'] : 'search', $module);
?>