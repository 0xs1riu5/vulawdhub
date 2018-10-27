<?php
defined('IN_DESTOON') or exit('Access Denied');
if($_groupid != 1) exit;
if($moduleid < 5) exit;
if(strlen($path) < 5) exit;
$table = get_table($moduleid);
if($table) {
	$sql = "filepath='$path'";
	if($itemid) $sql .= " AND itemid!=$itemid";
	if($db->get_one("SELECT itemid FROM {$table} WHERE $sql")) {
		exit(lang('message->ajax_filepath_exists'));
	} else {
		exit(lang('message->ajax_filepath_not_exists'));
	}
}
?>