<?php
require '../../common.inc.php';
require DT_ROOT.'/include/mobile.inc.php';
require DT_ROOT.'/include/post.func.php';
set_cookie('mobile', 'pc', $DT_TIME + 30*86400);
$uri = isset($uri) && is_url($uri) ? $uri : DT_PATH;
$head_title = $L['device_title'].$DT['seo_delimiter'].$head_title;
$foot = '';
include template('device', 'mobile');
?>