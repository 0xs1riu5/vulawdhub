<?php 
defined('IN_DESTOON') or exit('Access Denied');
$filename =  DT_ROOT.'/'.$MOD['moduledir'].'/'.$DT['index'].'.'.$DT['file_ext'];
if(!$MOD['index_html']) {
	if(is_file($filename)) unlink($filename);
	$mobfile = str_replace(DT_ROOT, DT_ROOT.'/mobile', $filename);
	if(is_file($mobfile)) unlink($mobfile);
	return false;
}
$maincat = $childcat = get_maincat(0, $moduleid, 1);
$seo_file = 'index';
include DT_ROOT.'/include/seo.inc.php';
$destoon_task = "moduleid=$moduleid&html=index";
$template = $MOD['template_index'] ? $MOD['template_index'] : 'index';
if($EXT['mobile_enable']) $head_mobile = $MOD['mobile'];
$DT_PC = $GLOBALS['DT_PC'] = 1;
ob_start();
include template($template, $module);
$data = ob_get_contents();
ob_clean();
file_put($filename, $data);
if($EXT['mobile_enable']) {
	include DT_ROOT.'/include/mobile.htm.php';
	$condition = "status=3";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition", 'CACHE');
	$items = $r['num'];
	$pages = mobile_pages($items, $page, $pagesize, $MOD['mobile'].'index.php?page={destoon_page}');
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
	ob_start();
	include template($template, $module);
	$data = ob_get_contents();
	ob_clean();
	file_put(str_replace(DT_ROOT, DT_ROOT.'/mobile', $filename), $data);
}
return true;
?>