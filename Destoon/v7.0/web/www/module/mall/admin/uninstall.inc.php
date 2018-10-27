<?php
defined('DT_ADMIN') or exit('Access Denied');
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_data_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_comment_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_express_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_stat_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_view_".$moduleid."`");
?>