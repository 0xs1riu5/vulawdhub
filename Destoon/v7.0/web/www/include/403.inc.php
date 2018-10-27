<?php
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403, $DT_BOT);
$head_title = lang('message->without_permission');
exit(include template('403', 'message'));
?>