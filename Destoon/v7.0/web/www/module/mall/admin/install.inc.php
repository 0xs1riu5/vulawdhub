<?php
defined('DT_ADMIN') or exit('Access Denied');
$setting = include(DT_ROOT.'/file/setting/module-16.php');
update_setting($moduleid, $setting);
$sql = file_get(DT_ROOT.'/file/setting/'.$module.'.sql');
$sql = str_replace('_16', '_'.$moduleid, $sql);
$sql = str_replace('商城', $modulename, $sql);
sql_execute($sql);
include DT_ROOT.'/module/'.$module.'/admin/remkdir.inc.php';
?>