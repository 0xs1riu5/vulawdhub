<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['show_html'] || !$itemid) return false;
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
if(!$item || $item['status'] < 3) return false;
$could_comment = in_array($moduleid, explode(',', $EXT['comment_module'])) ? 1 : 0;
extract($item);
$CAT = get_cat($catid);
$content_table = content_table($moduleid, $itemid, $MOD['split'], $table_data);
$t = $db->get_one("SELECT content FROM {$content_table} WHERE itemid=$itemid");
$content = $_content =  $t['content'];
$content = parse_video($content);
if($MOD['keylink']) $content = keylink($content, $moduleid);
if($lazy) $content = img_lazy($content);
$CP = $MOD['cat_property'] && $CAT['property'];
if($CP) {
	require_once DT_ROOT.'/include/property.func.php';
	$options = property_option($catid);
	$values = property_value($moduleid, $itemid);
}
$adddate = timetodate($addtime, 3);
$editdate = timetodate($edittime, 3);
$fileurl = $linkurl;
$linkurl = $MOD['linkurl'].$linkurl;
$fee = get_fee($item['fee'], $MOD['fee_view']);
if($fee) {
	$description = '';
	$user_status = 4;
} else {
	$user_status = 3;
}
$pass = $open == 3 ? true : false;
$T = array();
$result = $db->query("SELECT itemid,thumb,introduce FROM {$table_item} WHERE item=$itemid ORDER BY listorder ASC,itemid ASC");
while($r = $db->fetch_array($result)) {
	$r['middle'] = str_replace('.thumb.', '.middle.', $r['thumb']);
	$r['big'] = str_replace('.thumb.'.file_ext($r['thumb']), '', $r['thumb']);
	$T[] = $r;
}
$demo_url = $MOD['linkurl'].itemurl($item, '{destoon_page}');
$total = $items = count($T);
$user_status = 3;
$seo_file = 'show';
include DT_ROOT.'/include/seo.inc.php';
$template = $item['template'] ? $item['template'] : ($CAT['show_template'] ? $CAT['show_template'] : ($MOD['template_show'] ? $MOD['template_show'] : 'show'));
if($template == 'show-ebook' || $template == 'show-ebookfull') include DT_ROOT.'/api/flashpageflip/make.inc.php';
if($EXT['mobile_enable']) {
	include DT_ROOT.'/include/mobile.htm.php';	
	$back_link = $MOD['mobile'].$CAT['linkurl'];
	$head_name = $CAT['catname'];
	$foot = '';
}
for(; $page <= $total; $page++) {
	$next_photo = $items > 1 ? next_photo($page, $items, $demo_url) : $linkurl;
	$prev_photo = $items > 1 ? prev_photo($page, $items, $demo_url) : $linkurl;
	if($T) {
		$S = side_photo($T, $page, $demo_url);
	} else {
		$S = array();
		$T[0]['thumb'] = DT_SKIN.'image/spacer.gif';
		$T[0]['introduce'] = $L['no_picture'];
	}
	$P = $T[$page-1];
	$P['src'] = str_replace('.thumb.'.file_ext($P['thumb']), '', $P['thumb']);
	$destoon_task = "moduleid=$moduleid&html=show&itemid=$itemid&page=$page";
	if($EXT['mobile_enable']) $head_mobile = $MOD['mobile'].itemurl($item, $page > 1 ? $page : '');
	$filename = DT_ROOT.'/'.$MOD['moduledir'].'/'.itemurl($item, $page);
	$DT_PC = $GLOBALS['DT_PC'] = 1;
	if($pass) {
		ob_start();
		include template($template, $module);
		$data = ob_get_contents();
		ob_clean();
	} else {
		$data = '<meta http-equiv="refresh" content="0;url='.$MOD['linkurl'].'private.php?itemid='.$itemid.'&page='.$page.'"/>';
	}
	if($DT['pcharset']) $filename = convert($filename, DT_CHARSET, $DT['pcharset']);
	file_put($filename, $data);
	if($page == 1) {
		$indexname = DT_ROOT.'/'.$MOD['moduledir'].'/'.itemurl($item, 0);
		if($DT['pcharset']) $indexname = convert($indexname, DT_CHARSET, $DT['pcharset']);
		file_copy($filename, $indexname);
	}
	if($EXT['mobile_enable']) {
		if($total > 1) $pages = mobile_pages($total, $page, 1, $MOD['mobile'].itemurl($item, '{destoon_page}'));
		$content = video5($_content);
		if($MOD['keylink']) $content = keylink($content, $moduleid, 1);
		$filename = str_replace(DT_ROOT, DT_ROOT.'/mobile', $filename);
		$DT_PC = $GLOBALS['DT_PC'] = 0;
		if($pass) {
			ob_start();
			include template('show', $module);
			$data = ob_get_contents();
			ob_clean();
		} else {
			$data = '<meta http-equiv="refresh" content="0;url='.$MOD['mobile'].'private.php?itemid='.$itemid.'&page='.$page.'"/>';
		}
		file_put($filename, $data);
		if($page == 1 && $total > 1) file_copy($filename, str_replace(DT_ROOT, DT_ROOT.'/mobile', $indexname));
	}
}
return true;
?>