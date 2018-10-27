<?php
defined('IN_DESTOON') or exit('Access Denied');
$OAUTH = cache_read('oauth.php');
$site = 'taobao';
$OAUTH[$site]['enable'] or dheader($MODULE[2]['linkurl'].$DT['file_login']);
$session = new dsession();

define('TB_ID', $OAUTH[$site]['id']);
define('TB_SECRET', $OAUTH[$site]['key']);
define('TB_CALLBACK', DT_PATH.'api/oauth/'.$site.'/callback.php');
define('TB_CONNECT_URL', 'https://oauth.taobao.com/authorize');
define('TB_TOKEN_URL', 'https://oauth.taobao.com/token');
?>