<?php
defined('IN_DESTOON') or exit('Access Denied');
$OAUTH = cache_read('oauth.php');
$site = 'baidu';
$OAUTH[$site]['enable'] or dheader($MODULE[2]['linkurl'].$DT['file_login']);
$session = new dsession();

define('BD_ID', $OAUTH[$site]['id']);
define('BD_SECRET', $OAUTH[$site]['key']);
define('BD_CALLBACK', DT_PATH.'api/oauth/'.$site.'/callback.php');
define('BD_CONNECT_URL', 'https://openapi.baidu.com/oauth/2.0/authorize');
define('BD_TOKEN_URL', 'https://openapi.baidu.com/oauth/2.0/token');
define('BD_USERINFO_URL', 'https://openapi.baidu.com/rest/2.0/passport/users/getLoggedInUser');
?>