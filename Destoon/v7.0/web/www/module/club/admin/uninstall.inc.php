<?php
defined('DT_ADMIN') or exit('Access Denied');
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_data_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_fans_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_group_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_manage_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_reply_".$moduleid."`");
?>