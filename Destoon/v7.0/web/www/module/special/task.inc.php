<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($html == 'show') {
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	if(!$item || $item['status'] < 3) exit;
	extract($item);
	$update = '';
	if(!$DT_BOT) include DT_ROOT.'/include/update.inc.php';
	if($MOD['hits']) echo 'Inner("hits", \''.$item['hits'].'\');';
	$item['linkurl'] = $item['domain'] ? $item['filepath'] : $item['linkurl'];
	if($MOD['show_html'] && $task_item && $DT_TIME - @filemtime(DT_ROOT.'/'.$MOD['moduledir'].'/'.$item['linkurl']) > $task_item) tohtml('show', $module);
} else if($html == 'list') {
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
		$file = DT_ROOT.'/'.$MOD['moduledir'].'/'.$DT['index'].'.'.$DT['file_ext'];
		if($DT_TIME - @filemtime($file) > $task_index) tohtml('index', $module);
	}
}
?>