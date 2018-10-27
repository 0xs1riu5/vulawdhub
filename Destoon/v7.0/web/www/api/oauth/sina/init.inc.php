<?php
defined('IN_DESTOON') or exit('Access Denied');
$OAUTH = cache_read('oauth.php');
$site = 'sina';
$OAUTH[$site]['enable'] or dheader($MODULE[2]['linkurl'].$DT['file_login']);
$session = new dsession();
define("WB_AKEY", $OAUTH[$site]['id']);
define("WB_SKEY", $OAUTH[$site]['key']);
define("WB_CALLBACK_URL", DT_PATH.'api/oauth/'.$site.'/callback.php');
require DT_ROOT.'/api/oauth/'.$site.'/saetv2.ex.class.php';
?>