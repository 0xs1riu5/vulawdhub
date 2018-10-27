<?php
defined('IN_DESTOON') or exit('Access Denied');
if(strlen($captcha) < 4) exit('1');
$session = new dsession();
if(!isset($_SESSION['captchastr'])) exit('2');
if(decrypt($_SESSION['captchastr'], DT_KEY.'CPC') != strtoupper($captcha)) exit('3');
exit('0');
?>