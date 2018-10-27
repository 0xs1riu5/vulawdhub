<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/include/module.func.php';
require DT_ROOT.'/module/'.$module.'/global.func.php';
$table = $DT_PRE.$module.'_'.$moduleid;
$table_data = $DT_PRE.$module.'_data_'.$moduleid;
$table_comment = $DT_PRE.$module.'_comment_'.$moduleid;
$table_express = $DT_PRE.$module.'_express_'.$moduleid;
$table_stat = $DT_PRE.$module.'_stat_'.$moduleid;
$table_view = $DT_PRE.$module.'_view_'.$moduleid;
$table_cart = $DT_PRE.'cart';
$table_order = $DT_PRE.'order';
?>