<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/include/module.func.php';
require DT_ROOT.'/module/'.$module.'/global.func.php';
$CREDITS = explode('|', trim($MOD['credits']));
$table = $DT_PRE.$module.'_'.$moduleid;
$table_data = $DT_PRE.$module.'_data_'.$moduleid;
$table_answer = $DT_PRE.$module.'_answer_'.$moduleid;
$table_vote = $DT_PRE.$module.'_vote_'.$moduleid;
$table_expert = $DT_PRE.$module.'_expert_'.$moduleid;
?>