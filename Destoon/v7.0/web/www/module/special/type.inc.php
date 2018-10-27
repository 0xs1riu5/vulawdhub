<?php 
defined('IN_DESTOON') or exit('Access Denied');
$modurl = $DT_PC ? $MOD['linkurl'] : $MOD['mobile'];
$typeid = isset($tid) ? intval($tid) : 0;
$typeid or dheader($modurl);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$type = $db->get_one("SELECT * FROM {$DT_PRE}type WHERE typeid=$typeid");
$type or dheader($modurl);
$item = explode('-', $type['item']);
$item[0] == 'special' or dheader($modurl);
$itemid = $item[1];
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
($item && $item['status'] == 3) or dheader($modurl);
if($item['islink']) dheader($item['linkurl']);
$could_comment = in_array($moduleid, explode(',', $EXT['comment_module'])) ? 1 : 0;
extract($item);
$adddate = timetodate($addtime, 3);
$CAT = get_cat($catid);
$linkurl = $modurl.$linkurl;
$action = 'type';
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
	$CSS = array('article');
} else {
	$condition = "specialid=$itemid and typeid=$typeid";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table_item} WHERE $condition", 'CACHE');
	$items = $r['num'];
	$pages = mobile_pages($items, $page, $pagesize, $MOD['mobile'].rewrite('type.php?tid='.$typeid.'&page={destoon_page}'));
	$lists = array();
	if($items) {
		$result = $db->query("SELECT * FROM {$table_item} WHERE $condition ORDER BY addtime DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['title'] = str_replace('style="color:', 'style="font-size:16px;color:', set_style($r['title'], $r['style']));
			$r['linkurl'] = str_replace(DT_PATH, DT_MOB, $r['linkurl']);
			$r['date'] = timetodate($r['addtime'], 3);
			$lists[] = $r;
		}
		$db->free_result($result);
	}
	$back_link = $MOD['mobile'].$item['linkurl'];
	$head_name = $type['typename'];
	$foot = '';
}
include DT_ROOT.'/include/seo.inc.php';
if($seo_title) {
	$seo_title = $seo_title.$seo_delimiter.$seo_sitename;
} else {
	$seo_title = $seo_showtitle.$seo_delimiter.$seo_catname.$seo_modulename.$seo_delimiter.$seo_sitename;
}
$seo_title = $type['typename'].$seo_delimiter.$seo_page.$seo_title;
$template = $item['template'] ? $item['template'] : ($CAT['show_template'] ? $CAT['show_template'] : ($MOD['template_show'] ? $MOD['template_show'] : 'show'));
include template($template, $module);
?>