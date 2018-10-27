<?php
defined('IN_DESTOON') or exit('Access Denied');
$OAUTH = cache_read('oauth.php');
$site = 'qq';
$OAUTH[$site]['enable'] or dheader($MODULE[1]['linkurl']);
$session = new dsession();
define('QQ_ID', $OAUTH[$site]['id']);
define('QQ_SECRET', $OAUTH[$site]['key']);
define('QQ_CALLBACK', DT_PATH.'api/oauth/'.$site.'/callback.php');
define('QQ_CONNECT_URL', 'https://graph.qq.com/oauth2.0/authorize');
define('QQ_TOKEN_URL', 'https://graph.qq.com/oauth2.0/token');
define('QQ_ME_URL', 'https://graph.qq.com/oauth2.0/me');
define('QQ_USERINFO_URL', 'https://graph.qq.com/user/get_user_info');
?>