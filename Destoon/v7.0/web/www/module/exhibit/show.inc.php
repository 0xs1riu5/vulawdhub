<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$could_comment = in_array($moduleid, explode(',', $EXT['comment_module'])) ? 1 : 0;
if($DT_PC) {
	$itemid or dheader($MOD['linkurl']);
	if(!check_group($_groupid, $MOD['group_show'])) include load('403.inc');
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	if($item && $item['status'] > 2) {
		if($MOD['show_html'] && is_file(DT_ROOT.'/'.$MOD['moduledir'].'/'.$item['linkurl'])) d301($MOD['linkurl'].$item['linkurl']);
		extract($item);
	} else {
		include load('404.inc');
	}
	$CAT = get_cat($catid);
	if(!check_group($_groupid, $CAT['group_show'])) include load('403.inc');
	$content_table = content_table($moduleid, $itemid, $MOD['split'], $table_data);
	$t = $db->get_one("SELECT content FROM {$content_table} WHERE itemid=$itemid");
	$content = $t['content'];
	$content = parse_video($content);
	if($MOD['keylink']) $content = keylink($content, $moduleid);
	if($lazy) $content = img_lazy($content);
	$CP = $MOD['cat_property'] && $CAT['property'];
	if($CP) {
		require DT_ROOT.'/include/property.func.php';
		$options = property_option($catid);
		$values = property_value($moduleid, $itemid);
	}
	$process = get_process($fromtime, $totime);
	$fromtime = timetodate($fromtime, 3);
	$totime = timetodate($totime, 3);
	$adddate = timetodate($addtime, 3);
	$editdate = timetodate($edittime, 3);
	$linkurl = $MOD['linkurl'].$linkurl;
	$maincat = get_maincat(0, $moduleid);
	$update = '';
	$fee = get_fee($item['fee'], $MOD['fee_view']);
	if(check_group($_groupid, $MOD['group_contact'])) {
		if($fee) {
			$user_status = 4;
			$destoon_task = "moduleid=$moduleid&html=show&itemid=$itemid";
			$description = get_description($content, $MOD['pre_view']);
		} else {
			$user_status = 3;
		}
	} else {
		$user_status = $_userid ? 1 : 0;
		if($_username && $item['username'] == $_username) $user_status = 3;
	}
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $linkurl);
} else {
	$itemid or dheader($MOD['mobile']);
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	($item && $item['status'] > 2) or message($L['msg_not_exist']);
	extract($item);
	$CAT = get_cat($catid);
	if(!check_group($_groupid, $MOD['group_show']) || !check_group($_groupid, $CAT['group_show'])) mobile_msg($L['msg_no_right']);
	$member = array();
	$fee = get_fee($item['fee'], $MOD['fee_view']);
	include DT_ROOT.'/mobile/api/content.inc.php';
	$content_table = content_table($moduleid, $itemid, $MOD['split'], $table_data);
	$t = $db->get_one("SELECT content FROM {$content_table} WHERE itemid=$itemid");
	$content = video5($t['content']);
	if($MOD['keylink']) $content = keylink($content, $moduleid, 1);
	if($share_icon) $share_icon = share_icon($thumb, $content);
	$editdate = timetodate($edittime, 5);
	$fromdate = timetodate($fromtime, 3);
	$todate = timetodate($totime, 3);
	$process = get_process($fromtime, $totime);
	$could_sign = ($sign && $process == 1 && $user_status == 3 && $username && $username != $_username) ? 1 : 0;
	$update = '';
	$back_link = $MOD['mobile'].$CAT['linkurl'];
	$head_name = $CAT['catname'];
	$foot = '';
}
if(!$DT_BOT) include DT_ROOT.'/include/update.inc.php';
$seo_file = 'show';
include DT_ROOT.'/include/seo.inc.php';
$template = $item['template'] ? $item['template'] : ($CAT['show_template'] ? $CAT['show_template'] : ($MOD['template_show'] ? $MOD['template_show'] : 'show'));
include template($template, $module);
?>