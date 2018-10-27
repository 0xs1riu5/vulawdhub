<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MOD['announce_enable'] or dheader(DT_PATH);
require DT_ROOT.'/include/post.func.php';
$ext = 'announce';
$url = $EXT[$ext.'_url'];
$mob = $EXT[$ext.'_mob'];
$TYPE = get_type($ext, 1);
$_TP = sort_type($TYPE);
require DT_ROOT.'/module/'.$module.'/'.$ext.'.class.php';
$do = new $ext();
$typeid = isset($typeid) ? intval($typeid) : 0;
if($itemid) {
	$do->itemid = $itemid;
	$item = $do->get_one();
	$item or dheader($DT_PC ? $url : $mob);
	extract($item);
	$adddate = timetodate($addtime, 3);
	$fromdate = $fromtime ? timetodate($fromtime, 3) : $L['timeless'];
	$todate = $totime ? timetodate($totime, 3) : $L['timeless'];
	$content = $DT_PC ? parse_video($content) : video5($content);
	if(!$DT_BOT) $db->query("UPDATE LOW_PRIORITY {$DT_PRE}{$ext} SET hits=hits+1 WHERE itemid=$itemid", 'UNBUFFERED');
	$head_title = $title.$DT['seo_delimiter'].$L['announce_title'];
	$template = $item['template'] ? $item['template'] : $ext;
} else {
	$head_title = $L['announce_title'];
	if($catid) $typeid = $catid;
	$condition = '1';
	if($keyword) $condition .= " AND title LIKE '%$keyword%'";
	if($typeid) {
		isset($TYPE[$typeid]) or dheader($DT_PC ? $url : $mob);
		$condition .= " AND typeid IN (".type_child($typeid, $TYPE).")";
		$head_title = $TYPE[$typeid]['typename'].$DT['seo_delimiter'].$head_title;
	}
	if($cityid) $condition .= ($AREA[$cityid]['child']) ? " AND areaid IN (".$AREA[$cityid]['arrchildid'].")" : " AND areaid=$cityid";
	$lists = $do->get_list($condition, 'listorder DESC,itemid DESC');
	$template = $ext;
}
if($DT_PC) {
	$destoon_task = rand_task();
	if($EXT['mobile_enable']) $head_mobile = str_replace($url, $mob, $DT_URL);
} else {
	$foot = '';
	if($itemid) {
		$back_link = $mob;
	} else {
		$pages = mobile_pages($items, $page, $pagesize);
		$back_link = ($kw || $page > 1 || $typeid) ? $mob : DT_MOB.'more.php';
	}
}
include template($template, $module);
?>