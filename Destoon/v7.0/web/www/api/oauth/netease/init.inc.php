<?php
defined('IN_DESTOON') or exit('Access Denied');
$OAUTH = cache_read('oauth.php');
$site = 'netease';
$OAUTH[$site]['enable'] or dheader($MODULE[2]['linkurl'].$DT['file_login']);
$session = new dsession();

define('NE_ID', $OAUTH[$site]['id']);
define('NE_SECRET', $OAUTH[$site]['key']);
define('NE_CALLBACK', DT_PATH.'api/oauth/'.$site.'/callback.php');
define('NE_CONNECT_URL', 'http://reg.163.com/open/oauth2/authorize.do');
define('NE_TOKEN_URL', 'http://reg.163.com/open/oauth2/token.do');
define('NE_USERINFO_URL', 'https://reg.163.com/open/oauth2/getUserInfo.do');
?>