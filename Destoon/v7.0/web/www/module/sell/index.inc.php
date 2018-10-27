<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($DT_PC) {
	if(!check_group($_groupid, $MOD['group_index'])) include load('403.inc');
	$typeid = isset($typeid) ? intval($typeid) : 99;
	isset($TYPE[$typeid]) or $typeid = 99;
	$dtype = $typeid != 99 ? " AND typeid=$typeid" : '';
	$maincat = get_maincat($catid ? $CAT['parentid'] : 0, $moduleid);
	if($catid) $seo_title = $seo_catname.$seo_title;
	if($typeid != 99) $seo_title = $TYPE[$typeid].$seo_delimiter.$seo_title;
	if($page == 1) $head_canonical = $MOD['linkurl'];
	$destoon_task = "moduleid=$moduleid&html=index";
	if($EXT['mobile_enable']) $head_mobile = $MOD['mobile'].($page > 1 ? 'index.php?page='.$page : '');
} else {
	$condition = "status=3";
	if($cityid) {
		$areaid = $cityid;
		$ARE = $AREA[$cityid];
		$condition .= $ARE['child'] ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	}
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition", 'CACHE');
	$items = $r['num'];
	$pages = mobile_pages($items, $page, $pagesize);
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
	$back_link = DT_MOB.'channel.php';
	$head_name = $MOD['name'];
}
$seo_file = 'index';
include DT_ROOT.'/include/seo.inc.php';
include template($MOD['template_index'] ? $MOD['template_index'] : 'index', $module);
?>