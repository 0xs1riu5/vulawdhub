<?php 
defined('IN_DESTOON') or exit('Access Denied');
require_once DT_ROOT.'/include/module.func.php';
require DT_ROOT.'/module/'.$module.'/global.func.php';
$table = $DT_PRE.$module.'_'.$moduleid;
$table_data = $DT_PRE.$module.'_data_'.$moduleid;
$table_fans = $DT_PRE.$module.'_fans_'.$moduleid;
$table_group = $DT_PRE.$module.'_group_'.$moduleid;
$table_manage = $DT_PRE.$module.'_manage_'.$moduleid;
$table_reply = $DT_PRE.$module.'_reply_'.$moduleid;
?>