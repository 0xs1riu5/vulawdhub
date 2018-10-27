<?php
defined('IN_DESTOON') or exit('Access Denied');
foreach($MODULE as $m) {
	if($m['module'] == 'article') $db->query("UPDATE ".get_table($m['moduleid'])." SET status=3 WHERE status=4 AND addtime<$DT_TIME");
}
if($CFG['cache'] == 'file') $dc->expire();
?>