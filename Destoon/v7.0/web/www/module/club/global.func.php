<?php
defined('IN_DESTOON') or exit('Access Denied');
function get_group($gid) {
	global $table_group;
	return $gid > 0 ? DB::get_one("SELECT * FROM {$table_group} WHERE itemid=$gid") : array();
}

function is_fans($GRP) {
	global $table_fans, $_username;
	if($_username) {
		if($GRP['username'] == $_username) return true;
		if($GRP['manager'] && in_array($_username, explode('|', $GRP['manager']))) return true;
		$t = DB::get_one("SELECT * FROM {$table_fans} WHERE gid=$GRP[itemid] AND username='$_username' AND status=3");
		if($t) return true;
	}
	return false;
}

function is_admin($GRP) {
	global $_username, $_admin, $_passport;
	if($_username) {
		if($_admin == 1) return 'admin';
		if($GRP['username'] == $_username) return 'founder';
		if($GRP['manager'] && in_array($_passport, explode('|', $GRP['manager']))) return 'manager';
	}
	return '';
}
?>