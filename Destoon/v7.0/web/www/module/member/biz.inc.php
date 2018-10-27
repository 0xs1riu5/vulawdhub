<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
$MG['biz'] or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$year = isset($year) ? intval($year) : date('Y', $DT_TIME);
$year or $year = date('Y', $DT_TIME);
$month = isset($month) ? intval($month) : date('n', $DT_TIME);
$chart_data = '';
$T1 = $T2 = $T3 = 0;
if($month) {
	$L = date('t', strtotime($year.'-'.$month.'-01'));
	for($i = 1; $i <= $L; $i++) {
		if($i > 1) $chart_data .= '\n';
		$chart_data .= $i;
		$F = strtotime($year.'-'.$month.'-'.$i.' 00:00:00');
		$T = strtotime($year.'-'.$month.'-'.$i.' 23:59:59');
		$condition = "addtime>=$F AND addtime<=$T AND seller='$_username' AND pid=0";
		$t = $db->get_one("SELECT SUM(`amount`) AS num1,SUM(`fee`) AS num2 FROM {$DT_PRE}order WHERE {$condition} AND status=4");
		$num1 = $t['num1'] ? dround($t['num1']) : 0;
		$num2 = $t['num2'] ? dround($t['num2']) : 0;
		$num = $num1 + $num2;
		$chart_data .= ';'.$num;
		$T1 += $num;
		$t = $db->get_one("SELECT SUM(`amount`) AS num1,SUM(`fee`) AS num2 FROM {$DT_PRE}order WHERE {$condition} AND status=6");
		$num1 = $t['num1'] ? dround($t['num1']) : 0;
		$num2 = $t['num2'] ? dround($t['num2']) : 0;
		$num = $num1 + $num2;
		$chart_data .= ';'.$num;
		$T2 += $num;
	}
	$title = $year.'年'.$month.'月交易报表';
} else {
	for($i = 1; $i < 13; $i++) {
		if($i > 1) $chart_data .= '\n';
		$chart_data .= $i;
		$F = strtotime($year.'-'.$i.'-01 00:00:00');
		$T = strtotime($year.'-'.$i.'-'.date('t', $F).' 23:59:59');
		$condition = "addtime>=$F AND addtime<=$T AND seller='$_username' AND pid=0";
		$t = $db->get_one("SELECT SUM(`amount`) AS num1,SUM(`fee`) AS num2 FROM {$DT_PRE}order WHERE {$condition} AND status=4");
		$num1 = $t['num1'] ? dround($t['num1']) : 0;
		$num2 = $t['num2'] ? dround($t['num2']) : 0;
		$num = $num1 + $num2;
		$chart_data .= ';'.$num;
		$T1 += $num;
		$t = $db->get_one("SELECT SUM(`amount`) AS num1,SUM(`fee`) AS num2 FROM {$DT_PRE}order WHERE {$condition} AND status=6");
		$num1 = $t['num1'] ? dround($t['num1']) : 0;
		$num2 = $t['num2'] ? dround($t['num2']) : 0;
		$num = $num1 + $num2;
		$chart_data .= ';'.$num;
		$T2 += $num;
	}
	$title = $year.'年交易报表';
}
if($DT_PC) {
	$user = userinfo($_username);
	$deposit = $user['deposit'];
	$menu_id = 2;
} else {
	$foot = 'my';
}
$head_title = '商户后台';
include template('biz', $module);
?>