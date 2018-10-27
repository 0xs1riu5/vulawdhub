<?php 
defined('IN_DESTOON') or exit('Access Denied');
$comment = 0;
if(isset($MODULE[16])) {
	$table = $DT_PRE.'mall_comment_16';
	$comment = 1;
	$STARS = $L['star_type'];
	$view = isset($view) ? 1 : 0;
	$url = "file=$file";
	if($view) {
		$url .= "&view=$view";
		$condition = "buyer='$username' AND buyer_star>0";
	} else {
		$condition = "seller='$username' AND seller_star>0";
	}
	$demo_url = userurl($username, $url.'&page={destoon_page}', $domain);
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition", 'CACHE');
	$items = $r['num'];
	$pages = $DT_PC ? home_pages($items, $page, $pagesize, $demo_url) : mobile_pages($items, $page, $pagesize, $demo_url);
	$lists = array();
	if($items) {
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$lists[] = $r;
		}
		$db->free_result($result);
	}
}
if($DT_PC) {
	//
} else {
	$head_name = $head_title;
}
include template('credit', $template);
?>