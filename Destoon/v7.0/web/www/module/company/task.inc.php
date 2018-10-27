<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($html == 'list') {
	$catid or exit;
	if($MOD['list_html'] && $task_list && $CAT) {
		$num = 1;
		$totalpage = max(ceil($CAT['item']/$MOD['pagesize']), 1);
		$demo = DT_ROOT.'/'.$MOD['moduledir'].'/'.listurl($CAT, '{DEMO}');
		$fid = $page;
		if($fid >= 1 && $fid <= $totalpage && $DT_TIME - @filemtime(str_replace('{DEMO}', $fid, $demo)) > $task_list) tohtml('list', $module);
		$fid = $page + 1;
		if($fid >= 1 && $fid <= $totalpage && $DT_TIME - @filemtime(str_replace('{DEMO}', $fid, $demo)) > $task_list) tohtml('list', $module);
		$fid = $totalpage + 1 - $page;
		if($fid >= 1 && $fid <= $totalpage && $DT_TIME - @filemtime(str_replace('{DEMO}', $fid, $demo)) > $task_list) tohtml('list', $module);
	}
} else if($html == 'index') {
	if($DT['cache_hits'] && $MOD['hits']) {
		$file = DT_CACHE.'/hits-'.$moduleid;
		if($DT_TIME - @filemtime($file.'.dat') > $DT['cache_hits'] || @filesize($file.'.php') > 102400) update_hits($moduleid, $table);
	}
	if($MOD['index_html']) {
		$file = DT_CACHE.'/htm/company.htm';
		if($DT_TIME - @filemtime($file) > $task_index) tohtml('index', $module);
	}
}
?>