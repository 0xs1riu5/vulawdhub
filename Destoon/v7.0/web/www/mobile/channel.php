<?php
require '../common.inc.php';
require DT_ROOT.'/include/mobile.inc.php';
$head_title = $head_name = $L['channel_title'];
$foot = 'channel';
include template('channel', 'mobile');
?>