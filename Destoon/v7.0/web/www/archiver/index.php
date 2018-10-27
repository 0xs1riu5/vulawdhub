<?php
define('DT_REWRITE', true);
require '../common.inc.php';
$EXT['archiver_enable'] or dheader(DT_PATH);
$DT_BOT or dheader(DT_PATH);
$N = $M = $T = array();
$mid or $mid = 5;
$vmid = $list = 0;
foreach($MODULE as $k=>$v) {
	if(!$v['islink'] && $v['ismenu'] && $v['moduleid'] > 4) {
		if($k == $mid) $vmid = 1;
		$v['url'] = $DT['rewrite'] ? rewrite('index.php?mid='.$k) : '?mid-'.$k.'.html';
		$N[] = $v;
	}
}
$vmid or $mid = $N[0]['moduleid'];
$table = get_table($mid);
$t = $db->get_one("SELECT MIN(addtime) AS mintime,MAX(addtime) AS maxtime FROM {$table} WHERE addtime>0 AND status>2", 'CACHE');
$fromtime = $t['mintime'];
$fromyear = timetodate($fromtime, 'Y');
if($fromyear < 1990) $fromyear = 1990;
$frommonth = timetodate($fromtime, 'n');
$totime = $t['maxtime'] > $DT_TIME ? $DT_TIME : $t['maxtime'];
$toyear = timetodate($totime, 'Y');
$tomonth = timetodate($totime, 'n');
for($i = $toyear; $i >= $fromyear; $i--) {
	for($j = ($i == $toyear ? $tomonth : 12); $j >= ($i == $fromyear ? $frommonth : 1); $j--) {
		$r = array();
		$r['title'] = $MODULE[$mid]['name'].$i.'年'.($j < 10 ? '0' : '').$j.'月归档';
		$r['month'] = $i.($j < 10 ? '0' : '').$j;
		$r['url'] = $DT['rewrite'] ? rewrite('index.php?mid='.$mid.'&month='.$r['month']) : '?mid-'.$mid.'-month-'.$r['month'].'.html';
		$M[$r['month']] = $r;
	}
}
$head_title = $MODULE[$mid]['name'].'归档';
if(isset($month) && isset($M[$month])) {
	$list = 1;
	$y = substr($month, 0, 4);
	$m = substr($month, 4, 2);
	$ym = $y.'-'.$m;
	$t = timetodate(strtotime($ym.'-01'), 't');
	$ftime = strtotime($ym.'-01 00:00:00');
	$ttime = strtotime($ym.'-'.$t.' 23:59:59');
	$condition = "status>2 AND addtime>$ftime AND addtime<$ttime";	
	$num = $db->count($table, $condition, $CFG['db_expires']);
	$demo_url = $DT['rewrite'] ? rewrite('index.php?mid='.$mid.'&month='.$month.'&page={destoon_page}') : '?mid-'.$mid.'-month-'.$month.'-page-{destoon_page}.html';
	$pages = pages($num, $page, $pagesize, $demo_url);
	$tmp = explode('<input type="text"', $pages);
	$pages = $tmp[0];
	if($num) {
		$result = $db->query("SELECT title,linkurl,addtime FROM {$table} WHERE $condition ORDER BY addtime DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			if(strpos($r['linkurl'], '://') === false) $r['linkurl'] = $MODULE[$mid]['linkurl'].$r['linkurl'];
			$T[] = $r;
		}
	}
	$head_title = $MODULE[$mid]['name'].$y.'年'.$m.'月归档'.($page > 1 ? '第'.$page.'页' : '');
}
include template('archiver', 'extend');
?>