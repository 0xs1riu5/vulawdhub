<?php
defined('IN_DESTOON') or exit('Access Denied');
@ignore_user_abort(true);
$session = new dsession();
if($_SESSION['uploads']) {
	foreach($_SESSION['uploads'] as $file) {
		delete_upload($file, $_userid);
	}
	$_SESSION['uploads'] = array();
}
?>