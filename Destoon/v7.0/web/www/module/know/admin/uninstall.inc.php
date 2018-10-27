<?php
defined('DT_ADMIN') or exit('Access Denied');
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_data_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_answer_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_expert_".$moduleid."`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE.$module."_vote_".$moduleid."`");
?>