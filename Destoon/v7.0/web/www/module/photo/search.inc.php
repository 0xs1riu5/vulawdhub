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
	$CP = $MOD['cat_property'] && $catid && $CAT['property'];
	if(!$areaid && $cityid && strpos($DT_URL, 'areaid') === false) {
		$areaid = $cityid;
		$ARE = $AREA[$cityid];
	}
	$sfields = array($L['by_auto'], $L['by_title'], $L['by_content'], $L['by_introduce']);
	$dfields = array('keyword', 'title', 'content', 'introduce');
	$sorder  = array($L['order'], $L['order_auto'], $L['order_addtime'], $L['order_hits']);
	$dorder  = array($MOD['order'], '', 'addtime DESC', 'hits DESC');
	if(!$MOD['fulltext']) unset($sfields[2], $dfields[2]);
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;
	$order_select  = dselect($sorder, 'order', '', $order);
	$category_select = category_select('catid', $L['all_category'], $catid, $moduleid);
	$area_select = $DT['city'] ? ajax_area_select('areaid', $L['all_area'], $areaid) : '';
	$tags = array();
	if($DT_QST) {
		if($kw) {
			if(strlen($kw) < $DT['min_kw'] || strlen($kw) > $DT['max_kw']) message(lang($L['word_limit'], array($DT['min_kw'], $DT['max_kw'])), $MOD['linkurl'].'search.php');
			if($DT['search_limit'] && $page == 1) {
				if(($DT_TIME - $DT['search_limit']) < get_cookie('last_search')) message(lang($L['time_limit'], array($DT['search_limit'])), $MOD['linkurl'].'search.php');
				set_cookie('last_search', $DT_TIME);
			}
		}

		$pptsql = '';
		if($CP) {
			require DT_ROOT.'/include/property.func.php';
			$PPT = property_condition($catid);
			foreach($PPT as $k=>$v) {
				$PPT[$k]['select'] = '';
				$oid = $v['oid'];
				$tmp = 'ppt_'.$oid;
				if(isset($$tmp)) {
					$PPT[$k]['select'] = $tmp = strip_kw($$tmp);
					if($tmp && in_array($tmp, $v['options'])) {
						$tmp = 'O'.$oid.':'.$tmp.';';
						$pptsql .= " AND pptword LIKE '%$tmp%'";
					}
				}
			}
		}
		$fds = $MOD['fields'];
		$condition = '';
		$condition .= ' AND open=3';
		if($catid) $condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
		if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
		if($dfields[$fields] == 'content') {
			if($keyword && $MOD['fulltext'] == 1) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
			$condition = str_replace('AND ', 'AND i.', $condition);
			$condition = str_replace('i.content', 'd.content', $condition);
			$condition = "i.status=3 AND i.itemid=d.itemid".$condition;
			if($keyword && $MOD['fulltext'] == 2) $condition .= " AND MATCH(`content`) AGAINST('$kw'".(preg_match("/[+-<>()~*]/", $kw) ? ' IN BOOLEAN MODE' : '').")";
			$table = $table.' i,'.$table_data.' d';
			$fds = 'i.'.str_replace(',', ',i.', $fds);
		} else {
			if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
			if($pptsql) $condition .= $pptsql;//PPT
			$condition = "status=3".$condition;
		}
		$pagesize = $MOD['pagesize'];
		$offset = ($page-1)*$pagesize;
		$items = $db->count($table, $condition, $DT['cache_search']);
		$pages = pages($items, $page, $pagesize);
		if($items) {
			$order = $dorder[$order] ? " ORDER BY $dorder[$order]" : '';
			$result = $db->query("SELECT {$fds} FROM {$table} WHERE {$condition}{$order} LIMIT {$offset},{$pagesize}", $DT['cache_search'] && $page == 1 ? 'CACHE' : '', $DT['cache_search']);
			if($kw) {
				$replacef = explode(' ', $kw);
				$replacet = array_map('highlight', $replacef);
			}
			while($r = $db->fetch_array($result)) {
				$r['adddate'] = timetodate($r['addtime'], 5);
				$r['editdate'] = timetodate($r['edittime'], 5);
				if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = DT_SKIN.'image/lazy.gif" original="'.$r['thumb'];
				$r['alt'] = $r['title'];
				$r['title'] = dsubstr($r['title'], 20);
				$r['title'] = set_style($r['title'], $r['style']);
				if($kw) $r['title'] = str_replace($replacef, $replacet, $r['title']);
				$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
				$tags[] = $r;
			}
			$db->free_result($result);
			if($page == 1 && $kw) keyword($kw, $items, $moduleid);
		}
	}
	$showpage = 1;
	$datetype = 3;
	$target = '_blank';
	$width = 168;
	$height = 124;
	$seo_file = 'search';
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
	$condition = "status=3";
	if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
	if($catid) $condition .= $CAT['child'] ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
	if($areaid) $condition .= $ARE['child'] ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition", 'CACHE');
	$items = $r['num'];
	$pages = mobile_pages($items, $page, $pagesize);
	$lists = array();
	if($items) {
		$order = $MOD['order'];
		$time = strpos($MOD['order'], 'add') !== false ? 'addtime' : 'edittime';
		$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			if($kw) $r['title'] = str_replace($kw, '<b class="f_red">'.$kw.'</b>', $r['title']);
			$r['linkurl'] = $MOD['mobile'].$r['linkurl'];
			$r['date'] = timetodate($r[$time], 3);
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