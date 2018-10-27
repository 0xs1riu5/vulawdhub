<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
check_group($_groupid, $MOD['group_compare']) or dalert(lang('message->without_permission'), 'goback');
$itemid && is_array($itemid) or dalert($L['compare_choose'], 'goback');
if(is_array($itemid)) {
	$DT_URL = $MOD['linkurl'].'compare.php?';
	foreach($itemid as $id) {
		$DT_URL .= '&itemid[]='.$id;
	}
	$DT_URL = str_replace('?&', '?', $DT_URL);
}
$itemid = array_unique($itemid);
$item_nums = count($itemid);
$item_nums < 7 or dalert($L['compare_max'], 'goback');
$item_nums > 1 or dalert($L['compare_min'], 'goback');
$itemid = implode(',', $itemid);
$tags = array();
$result = $db->query("SELECT * FROM {$table} WHERE itemid IN ($itemid) ORDER BY addtime DESC");
while($r = $db->fetch_array($result)) {
	if($r['status'] != 3) continue;
	$r['editdate'] = timetodate($r['edittime'], 3);
	$r['adddate'] = timetodate($r['addtime'], 3);
	$r['alt'] = $r['title'];
	$r['title'] = set_style($r['title'], $r['style']);
	$r['userurl'] = userurl($r['username']);
	$r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
	$r['mobile'] = $MOD['mobile'].$r['linkurl'];
	$tags[] = $r;
}
$head_title = $L['compare_title'].$DT['seo_delimiter'].$MOD['name'];
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	$back_link = $forward = $MOD['mobile'];
	$head_name = $L['compare_title'];
	$foot = '';
}
include template($MOD['template_compare'] ? $MOD['template_compare'] : 'compare', $module);
?>