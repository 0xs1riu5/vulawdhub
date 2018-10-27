<?php
defined('IN_DESTOON') or exit('Access Denied');
$partner = trim($PAY[$bank]['partnerid']);
$key = trim($PAY[$bank]['keycode']);
$return_url = $receive_url;
$notify_url = DT_PATH.'api/pay/'.$bank.'/'.($PAY[$bank]['notify'] ? $PAY[$bank]['notify'] : 'notify.php');
?>