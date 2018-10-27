<?php
defined('IN_DESTOON') or exit('Access Denied');
$WX = cache_read('weixin.php');
($WX['appid'] && $WX['appsecret'] && $WX['apptoken']) or exit('Missing configuration...');
define('WX_APPID', $WX['appid']);
define('WX_APPSECRET', $WX['appsecret']);
define('WX_APPTOKEN', $WX['apptoken']);
?>