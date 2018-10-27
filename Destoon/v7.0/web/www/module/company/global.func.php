<?php
defined('IN_DESTOON') or exit('Access Denied');
require_once DT_ROOT.'/module/member/global.func.php';
function home_pages($total, $page, $pagesize, $demo_url) {
	global $MOD, $L;
	$pages = '';
	$items = $total;
	$total = ceil($total/$pagesize);
	$page = intval($page);
	$home_url = str_replace('{destoon_page}', '1', str_replace(array('%7B', '%7D'), array('{', '}'), $demo_url));
	include DT_ROOT.'/api/pages.sample.php';
	return $pages;
}
?>