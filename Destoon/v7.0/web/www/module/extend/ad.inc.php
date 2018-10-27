<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MOD['ad_enable'] or dheader(DT_PATH);
$ext = 'ad';
$url = $EXT[$ext.'_url'];
$mob = $EXT[$ext.'_mob'];
$TYPE = $L['ad_type'];
require DT_ROOT.'/module/'.$module.'/'.$ext.'.class.php';
$do = new $ext();
$currency = $MOD['ad_currency'];
$unit = $currency == 'money' ? $DT['money_unit'] : $DT['credit_unit'];
if($itemid) $pid = $itemid;
$typeid = isset($typeid) ? intval($typeid) : 0;
$pid = isset($pid) ? intval($pid) : 0;
$aid = isset($aid) ? intval($aid) : 0;
if($pid || $aid) {
	$MOD['ad_view'] or message($L['preview_close']);		
	$filename = '';
	$ad_moduleid = 0;
	if($pid) {
		$do->pid = $pid;
		$p = $do->get_one_place();
		$p or message($L['not_ad_place']);
		$head_title = lang($L['view_ad_place'], array($p['name']));
		$title = $p['name'];
		$typeid = $p['typeid'];
	} else if($aid) {
		$do->aid = $aid;
		$a = $do->get_one();
		$a or message($L['not_ad']);
		$head_title = lang($L['view_ad'], array($a['title']));
		$title = $a['title'];
		$pid = $a['pid'];
		$typeid = $a['typeid'];
		if($typeid > 5) {
			$ad_moduleid = $a['key_moduleid'];
			$ad_catid = $a['key_catid'];
			$ad_kw = $a['key_word'];
		}
	}
	$action = 'view';
} else {
	$destoon_task = "moduleid=$moduleid&html=ad";
	$head_title = $L['ad_title'];
	if($catid) $typeid = $catid;
	$condition = 'open=1';
	if($keyword) $condition .= " AND name LIKE '%$keyword%'";
	if($typeid) {
		isset($TYPE[$typeid]) or dheader($EXT['ad_url']);
		$condition .= " AND typeid=$typeid";
		$head_title = $TYPE[$typeid].$DT['seo_delimiter'].$head_title;
	}
	$lists = $do->get_list_place($condition, 'listorder DESC,pid DESC');	
}
$template = $ext;
if($DT_PC) {
	$destoon_task = "moduleid=$moduleid&html=$ext";
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