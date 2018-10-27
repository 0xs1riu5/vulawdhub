<?php
defined('IN_DESTOON') or exit('Access Denied');
function nexttime($schedule, $time) {
	if(strpos($schedule, ',') !== false) {
		list($h, $m) = explode(',', $schedule);
		$t = strtotime(timetodate($time, 3).' '.($h < 10 ? '0'.$h : $h).':'.($m < 10 ? '0'.$m : $m).':00');
		return $t > $time ? $t : $t + 86400;
	} else {
		$m = intval($schedule);
		return $time + ($m ? $m : 1800)*60;
	}
}
$result = $db->query("SELECT * FROM {$DT_PRE}cron WHERE nexttime<$DT_TIME ORDER BY itemid");
while($cron = $db->fetch_array($result)) {
	if($cron['status']) continue;
	include DT_ROOT.'/api/cron/'.$cron['name'].'.inc.php';
	$nexttime = nexttime($cron['schedule'], $DT_TIME);
	$db->query("UPDATE {$DT_PRE}cron SET lasttime=$DT_TIME,nexttime=$nexttime WHERE itemid=$cron[itemid]");
}
if($DT['message_email'] && $DT['mail_type'] != 'close' && !$_userid) include DT_ROOT.'/api/cron/message.php';
?>