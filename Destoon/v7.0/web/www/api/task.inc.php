<?php
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) {
	//
} else {
	include template('line', 'chip');
	$db->close();
}
?>