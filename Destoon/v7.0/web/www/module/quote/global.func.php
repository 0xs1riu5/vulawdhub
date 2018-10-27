<?php
defined('IN_DESTOON') or exit('Access Denied');
function get_avg($lists) {
	$n = $s = 0;
	foreach($lists as $v) {
		if($v['price'] > 0) {
			$n++;
			$s += $v['price'];
		}
	}
	return $n ? dround($s/$n) : 0;
}

function get_all($date, $lists) {
	$n = $s = 0;
	foreach($lists as $v) {
		if($v['date'] == $date) {
			$n++;
			$s += $v['price'];
		}
	}
	return $n ? dround($s/$n) : get_avg($lists);
}

function get_mkt($date, $market, $lists) {
	$n = $s = 0;
	foreach($lists as $v) {
		if($v['date'] == $date && $v['market'] == $market) {
			$n++;
			$s += $v['price'];
		}
	}
	return $n ? dround($s/$n) : get_avg($lists);
}
?>