<?php
defined('IN_DESTOON') or exit('Access Denied');
function get_relate($M) {
	global $table, $MOD, $DT_PC;
	$lists = $tags = array();
	if($M['relate_id'] && $M['relate_name']) {
		$ids = $M['relate_id'];
		$result = DB::query("SELECT itemid,title,linkurl,thumb,username,status,relate_id,relate_name,relate_title FROM {$table} WHERE itemid IN ($ids)");
		while($r = DB::fetch_array($result)) {
			if($r['username'] != $M['username']) continue;
			if($r['relate_id'] != $M['relate_id']) continue;
			if($r['relate_name'] != $M['relate_name']) continue;
			if($r['status'] != 3) continue;
			if(!$r['relate_title']) $r['relate_title'] = $r['title'];
			$tags[$r['itemid']] = $r;
		}
		foreach(explode(',', $ids) as $v) {
			if(isset($tags[$v])) $lists[] = $tags[$v];
		}
		return count($lists) > 1 ? $lists : array();
	}
}

function get_nv($n, $v) {
	$p = array();
	if($n && $v) $p = explode('|', $v);
	return count($p) > 1 ? $p : array();
}

function get_price($amount, $price, $step) {
	if($step) {
		$s = unserialize($step);
		if($s['a3'] && $amount > $s['a3']) return $s['p3'];
		if($s['a2'] && $amount > $s['a2']) return $s['p2'];
	}
	return $price;
}

function get_promos($username) {
	$lists = array();
	$result = DB::query("SELECT * FROM ".DT_PRE."finance_promo WHERE username='$username' AND fromtime<".DT_TIME." AND totime>".DT_TIME." AND number<amount ORDER BY price ASC LIMIT 10", 'CACHE');
	while($r = DB::fetch_array($result)) {
		$lists[] = $r;
	}
	return $lists;
}

function get_coupons($username, $seller) {
	$lists = array();
	$result = DB::query("SELECT * FROM ".DT_PRE."finance_coupon WHERE username='$username' AND (seller='$seller' OR seller='') AND fromtime<".DT_TIME." AND totime>".DT_TIME." AND oid=0 ORDER BY price ASC LIMIT 10", 'CACHE');
	while($r = DB::fetch_array($result)) {
		$lists[] = $r;
	}
	return $lists;
}

function view_log($item) {
	global $table_view, $_username;
	if($item && $_username) {
		$uid = $_username.'|'.$item['itemid'];
		$itemid = $item['itemid'];
		$seller = $item['username'];
		if($itemid > 0 && check_name($seller)) DB::query("REPLACE INTO {$table_view} (uid,itemid,username,seller,lasttime) VALUES ('$uid','$itemid','$_username','$seller','".DT_TIME."')");
	}
}

function view_txt($date) {
	global $L;
	if($date == timetodate(DT_TIME, 3)) return $L['view_txt_0'];
	if($date == timetodate(strtotime('-1 day'), 3)) return $L['view_txt_1'];
	if($date == timetodate(strtotime('-2 day'), 3)) return $L['view_txt_2'];
	return $date;
}
?>