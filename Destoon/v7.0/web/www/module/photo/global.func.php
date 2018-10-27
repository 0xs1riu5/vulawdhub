<?php
defined('IN_DESTOON') or exit('Access Denied');
function side_photo($T, $page, $demo_url) {
	$demo_url = str_replace(array('%7B', '%7D'), array('{', '}'), $demo_url);
	$S = array();
	$i = $j = 0;
	foreach($T as $k=>$v) {
		$T[$k]['linkurl'] = str_replace('{destoon_page}', $k + 1, $demo_url);
		$T[$k]['page'] = $k + 1;
		if($page == $k + 1) $j = $i;
		$i++;
	}
	if($i < 5) return $T;
	$N = $T;
	$N = array_merge($N, $T);
	$N = array_merge($N, $T);
	if(isset($N[$j + $i - 2])) $S[] = $N[$j + $i - 2];
	if(isset($N[$j + $i - 1])) $S[] = $N[$j + $i - 1];
	if(isset($N[$j + $i])) $S[] = $N[$j + $i];
	if(isset($N[$j + $i + 1])) $S[] = $N[$j + $i + 1];
	if(isset($N[$j + $i + 2])) $S[] = $N[$j + $i + 2];
	return $S;
}

function next_photo($page, $items, $demo_url) {
	if($page == $items) return 'javascript:PhotoLast();';
	$demo_url = str_replace(array('%7B', '%7D'), array('{', '}'), $demo_url);
	$p = $page == $items ? 1 : $page + 1;
	return str_replace('{destoon_page}', $p, $demo_url).'#p';
}

function prev_photo($page, $items, $demo_url) {
	$demo_url = str_replace(array('%7B', '%7D'), array('{', '}'), $demo_url);
	$p = $page == 1 ? $items : $page - 1;
	return str_replace('{destoon_page}', $p, $demo_url).'#p';
}
?>