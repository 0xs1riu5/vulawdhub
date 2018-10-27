<?php
defined('DT_ADMIN') or exit('Access Denied');
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_data_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_apply_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_talent_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_resume_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_resume_data_".$moduleid."`");
?>