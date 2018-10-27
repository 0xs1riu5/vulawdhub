<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($DT_PC) {
	if(!$CAT || $CAT['moduleid'] != $moduleid) include load('404.inc');
	if($MOD['list_html']) {
		$html_file = listurl($CAT, $page);
		if(is_file(DT_ROOT.'/'.$MOD['moduledir'].'/'.$html_file)) d301($MOD['linkurl'].$html_file);
	}
	if(!check_group($_groupid, $MOD['group_list']) || !check_group($_groupid, $CAT['group_list'])) include load('403.inc');
	$CP = $MOD['cat_property'] && $CAT['property'];
	if($MOD['cat_property'] && $CAT['property']) {
		require DT_ROOT.'/include/property.func.php';
		$PPT = property_condition($catid);
	}
	unset($CAT['moduleid']);
	extract($CAT);
	$maincat = get_maincat($child ? $catid : $parentid, $moduleid);
	$condition = 'status=3 AND open=3 AND items>0';
	$condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
	if($cityid) {
		$areaid = $cityid;
		$ARE = $AREA[$cityid];
		$condition .= $ARE['child'] ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
		$items = $db->count($table, $condition, $CFG['db_expires']);
	} else {
		if($page == 1) {
			$items = $db->count($table, $condition, $CFG['db_expires']);
			if($items != $CAT['item']) {
				$CAT['item'] = $items;
				$db->query("UPDATE {$DT_PRE}category SET item=$items WHERE catid=$catid");
			}
		} else {
			$items = $CAT['item'];
		}
	}
	$pagesize = $MOD['pagesize'];
	$offset = ($page-1)*$pagesize;
	$pages = listpages($CAT, $items, $page, $pagesize);
	$tags = array();
	if($items) {
		$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE {$condition} ORDER BY ".$MOD['order']." LIMIT {$offset},{$pagesize}", ($CFG['db_expires'] && $page == 1) ? 'CACHE' : '', $CFG['db_expires']);
		while($r = $db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			if($lazy && isset($r['thumb']) && $r['thumb']) $r['thumb'] = DT_SKIN.'image/lazy.gif" original="'.$r['thumb'];
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
			$tags[] = $r;
		}
		$db->free_result($result);
	}
	$showpage = 1;
	$datetype = 3;
	$target = '_blank';
	$width = 168;
	$height = 124;
	if($EXT['mobile_enable']) $head_mobile = $MOD['mobile'].listurl($CAT, $page);
} else {
	if(!$CAT || $CAT['moduleid'] != $moduleid) message($L['msg_not_cate']);
	if(!check_group($_groupid, $MOD['group_list']) || !check_group($_groupid, $CAT['group_list'])) message($L['msg_no_right']);
	$condition = "status=3";
	$condition .= $CAT['child'] ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
	if($cityid) {
		$areaid = $cityid;
		$ARE = $AREA[$cityid];
		$condition .= $ARE['child'] ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	}
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition", 'CACHE');
	$items = $r['num'];
	$pages = mobile_pages($items, $page, $pagesize, $MOD['mobile'].listurl($CAT, '{destoon_page}'));
	$lists = array();
	if($items) {
		$order = $MOD['order'];
		$time = strpos($MOD['order'], 'add') !== false ? 'addtime' : 'edittime';
		$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['title'] = str_replace('style="color:', 'style="font-size:16px;color:', set_style($r['title'], $r['style']));
			$r['linkurl'] = $MOD['mobile'].$r['linkurl'];
			$r['date'] = timetodate($r[$time], 3);
			$lists[] = $r;
		}
		$db->free_result($result);
	}
	if($CAT['parentid']) {
		$PCAT = get_cat($CAT['parentid']);
		$back_link = $MOD['mobile'].$PCAT['linkurl'];
	} else {
		$back_link = $MOD['mobile'];
	}
	$head_title = $head_name = $CAT['catname'];
}
$seo_file = 'list';
include DT_ROOT.'/include/seo.inc.php';
$template = $CAT['template'] ? $CAT['template'] : ($MOD['template_list'] ? $MOD['template_list'] : 'list');
include template($template, $module);
?>