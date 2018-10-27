<?php
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(404, $DT_BOT);
$head_title = lang($itemid ? 'message->item_not_exists' : 'message->cate_not_exists');
exit(include template($itemid ? 'show-notfound' : 'list-notfound', 'message'));
?>