<?php 
defined('IN_DESTOON') or exit('Access Denied');
$tags = $ids = $_tags = $tbs = $PPT = array();//PPT
if($DT_QST) {
	if($kw) {
		if(strlen($kw) < $DT['min_kw'] || strlen($kw) > $DT['max_kw']) message(lang($L['word_limit'], array($DT['min_kw'], $DT['max_kw'])), $MOD['linkurl'].'search.php');
		if($DT['search_limit'] && $page == 1) {
			if(($DT_TIME - $DT['search_limit']) < get_cookie('last_search')) message(lang($L['time_limit'], array($DT['search_limit'])), $MOD['linkurl'].'search.php');
			set_cookie('last_search', $DT_TIME);
		}
		$replacef = explode(' ', $kw);
		$replacet = array_map('highlight', $replacef);
	}
	require DT_ROOT.'/include/sphinx.class.php';
	$sx = new SphinxClient();
	if($MOD['sphinx_host'] && $MOD['sphinx_port']) $sx->SetServer($MOD['sphinx_host'], $MOD['sphinx_port']);
	$sx->SetArrayResult(true);
	$sx->SetMatchMode(SPH_MATCH_PHRASE);
	$sx->SetRankingMode(SPH_RANK_NONE);
	$sx->SetSortMode(SPH_SORT_EXTENDED, 'sorttime desc');
	$sx->SetFilter('status', array(3));
	if($catid) $sx->SetFilter('catid', explode(',', $CAT['arrchildid']));
	if($areaid) $sx->SetFilter('areaid', explode(',', $ARE['arrchildid']));
	$pagesize = $MOD['pagesize'];
	$offset = ($page-1)*$pagesize;
	$sx->SetLimits($offset, $pagesize);
	$_kw = $kw;
	$r = $sx->Query($_kw, $MOD['sphinx_name']);
	$time = $r['time'];
	$items = $r['total_found'];
	$total = $r['total'];
	$pages = pages($items > $total ? $total : $items, $page, $pagesize);
	foreach($r['matches'] as $k=>$v) {
		$ids[$v['id']] = $v['id'];
	}		
	if($ids) {
		$condition = "itemid IN (".implode(',', $ids).")";
		$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE {$condition}");
		while($r = $db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = DT_SKIN.'image/lazy.gif" original="'.$r['thumb'];
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			if($kw) $r['title'] = str_replace($replacef, $replacet, $r['title']);
			if($kw) $r['introduce'] = str_replace($replacef, $replacet, $r['introduce']);
			$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
			$_tags[$r['itemid']] = $r;
		}
		$db->free_result($result);
		foreach($ids as $id) {
			$tags[] = $_tags[$id];
		}
		if($page == 1 && $kw) keyword($kw, $items, $moduleid);
	}
}
$showpage = 1;
$datetype = 5;
$seo_file = 'search';
include DT_ROOT.'/include/seo.inc.php';
if($EXT['mobile_enable']) $head_mobile = $EXT['mobile_url'].($kw ? 'index.php?moduleid='.$moduleid.'&kw='.encrypt($kw, DT_KEY.'KW') : 'search.php?action=mod'.$moduleid);
include template($MOD['template_search'] ? $MOD['template_search'] : 'search', $module);
?>