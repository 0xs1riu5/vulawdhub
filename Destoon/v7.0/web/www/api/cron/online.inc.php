<?php
defined('IN_DESTOON') or exit('Access Denied');
$lastime = $DT_TIME - $DT['online'];
$db->query("DELETE FROM {$DT_PRE}online WHERE lasttime<$lastime");
?>