<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/include/module.func.php';
require DT_ROOT.'/module/'.$module.'/global.func.php';
$table = $DT_PRE.$module.'_'.$moduleid;
$table_data = $DT_PRE.$module.'_data_'.$moduleid;
$table_order = $DT_PRE.$module.'_order_'.$moduleid;
?>