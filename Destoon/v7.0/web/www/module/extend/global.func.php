<?php
defined('IN_DESTOON') or exit('Access Denied');
function show_url($id, $page = 1) {
	global $MOD;
	if($MOD['show_url'] == 2) return 'show/'.$id.'/'.($page == 1 ? '' : $page.'/');
	if($MOD['show_url'] == 1) return 'show-'.$id.($page == 1 ? '' : '-'.$page).'.html';
	return 'show.php?itemid='.$id.($page == 1 ? '' : '&page='.$page);
}

function list_url($id, $page = 1) {
	global $MOD;
	if($MOD['list_url'] == 2) return 'list/'.$id.'/'.($page == 1 ? '' : $page.'/');
	if($MOD['list_url'] == 1) return 'list-'.$id.($page == 1 ? '' : '-'.$page).'.html';
	return 'list.php?catid='.$id.($page == 1 ? '' : '&page='.$page);
}

function rand_task() {
	$T = array('spread', 'ad', 'xml');
	return 'moduleid=3&html='.$T[array_rand($T)];
}

function ad_name($ad = array()) {
	if($ad['typeid'] > 5) {
		if($ad['key_word']) {
			return 'ad_t'.$ad['typeid'].'_m'.$ad['key_moduleid'].'_k'.urlencode($ad['key_word']).'_'.$ad['areaid'].'.htm';
		} else if($ad['key_catid']) {
			return 'ad_t'.$ad['typeid'].'_m'.$ad['key_moduleid'].'_c'.$ad['key_catid'].'_'.$ad['areaid'].'.htm';
		} else {
			return 'ad_t'.$ad['typeid'].'_m'.$ad['key_moduleid'].'_'.$ad['areaid'].'.htm';
		}
	} else {
		return 'ad_'.$ad['pid'].'_'.$ad['areaid'].'.htm';
	}
	return '';
}
?>