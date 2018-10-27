<?php
defined('IN_DESTOON') or exit('Access Denied');
$DT_MOB['os'] == 'ios' or exit;
if(get_cookie('mobile') != 'screen') set_cookie('mobile', 'screen', $DT_TIME + 86400*30);
?>