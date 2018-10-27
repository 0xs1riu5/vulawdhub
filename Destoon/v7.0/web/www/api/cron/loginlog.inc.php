<?php
defined('IN_DESTOON') or exit('Access Denied');
$time = $today_endtime - 30*86400;
$db->query("DELETE FROM {$DT_PRE}login WHERE logintime<$time");
?>