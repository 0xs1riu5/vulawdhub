<?php
require '../../../common.inc.php';
require 'init.inc.php';
$OAUTH[$site]['sync'] or exit;
$_token = get_cookie('sina_token');
if($_token) {
	require '../post.inc.php';
	$o = new SaeTClientV2(WB_AKEY, WB_SKEY, $_token);
	$rec = $thumb ? $o->upload($content, $thumb) : $o->update($content);
	#log_write($rec, 'wb', 1);
	if(isset($rec['error_code']) && $rec['error_code'] > 0) {
		//fail
	} else {
		//success
	}
}
?>