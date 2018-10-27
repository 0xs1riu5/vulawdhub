<?php
require '../common.inc.php';
#header("Content-type:text/javascript");
$DT['pushtime'] > 0 or exit;
include template('push', 'chip');
?>