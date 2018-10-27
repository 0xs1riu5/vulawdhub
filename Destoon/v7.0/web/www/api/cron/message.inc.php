<?php
defined('IN_DESTOON') or exit('Access Denied');
$time = $today_endtime - 90*86400;
$db->query("DELETE FROM {$DT_PRE}message WHERE isread=1 AND addtime<$time");
$db->query("DELETE FROM {$DT_PRE}message WHERE status IN (2,4) AND addtime<$time");
?>