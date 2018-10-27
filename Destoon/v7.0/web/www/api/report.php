<?php
$moduleid = 3;
require '../common.inc.php';
$action = 'add';
$report = 1;
$content = isset($content) ? stripslashes($content) : '';
if($content) $content = strip_tags($content);
require DT_ROOT.'/module/'.$module.'/guestbook.inc.php';
?>