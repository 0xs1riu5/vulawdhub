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
	$description = $best ? get_description($best['content'], $MOD['pre_view']) : '';
	$user_status = 4;
} else {
	$user_status = 3;
}
$answers = $best = $E = array();
if($page == 1) {
	if($aid) $best = $db->get_one("SELECT * FROM {$table_answer} WHERE itemid=$aid");
	if($best && $best['expert']) $E = $db->get_one("SELECT * FROM {$table_expert} WHERE username='$best[username]'");
}
$pages = '';
$pagesize = $MOD['answer_pagesize'];
$items = $answer;
if($aid) $items--;
$total = max(ceil($items/$pagesize), 1);
if(isset($fid) && isset($num)) {
	$page = $fid;
	$topage = $fid + $num - 1;
	$total = $topage < $total ? $topage : $total;
}
$template = $item['template'] ? $item['template'] : ($CAT['show_template'] ? $CAT['show_template'] : ($MOD['template_show'] ? $MOD['template_show'] : 'show'));
if($EXT['mobile_enable']) {
	include DT_ROOT.'/include/mobile.htm.php';	
	$back_link = $MOD['mobile'].$CAT['linkurl'];
	$head_name = $CAT['catname'];
	$foot = '';
}
for(; $page <= $total; $page++) {
	$destoon_task = "moduleid=$moduleid&html=show&itemid=$itemid&page=$page";
	if($EXT['mobile_enable']) $head_mobile = $MOD['mobile'].itemurl($item, $page > 1 ? $page : '');
	$filename = $total == 1 ? DT_ROOT.'/'.$MOD['moduledir'].'/'.$fileurl : DT_ROOT.'/'.$MOD['moduledir'].'/'.itemurl($item, $page);
	$answers = array();
	if(($process == 0 || $process == 3) && $items) {
		$offset = ($page-1)*$pagesize;
		$pages = pages($items, $page, $pagesize, $MOD['linkurl'].itemurl($item, '{destoon_page}'));
		$result = $db->query("SELECT * FROM {$table_answer} WHERE qid=$itemid AND status=3 ORDER BY itemid ASC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			if($r['itemid'] == $aid) continue;
			$answers[] = $r;
		}
	}
	$seo_file = 'show';
	include DT_ROOT.'/include/seo.inc.php';
	$DT_PC = $GLOBALS['DT_PC'] = 1;
	ob_start();
	include template($template, $module);
	$data = ob_get_contents();
	ob_clean();
	if($DT['pcharset']) $filename = convert($filename, DT_CHARSET, $DT['pcharset']);
	file_put($filename, $data);
	if($page == 1 && $total > 1) {
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
		ob_start();
		include template($template, $module);
		$data = ob_get_contents();
		ob_clean();
		file_put($filename, $data);
		if($page == 1 && $total > 1) file_copy($filename, str_replace(DT_ROOT, DT_ROOT.'/mobile', $indexname));
	}
}
return true;
?>