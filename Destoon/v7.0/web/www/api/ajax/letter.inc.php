<?php
defined('IN_DESTOON') or exit('Access Denied');
preg_match("/[a-z]{1}/", $letter) or exit;
$cols = isset($cols) ? intval($cols) : 5;
$precent = ceil(100/$cols);
$CATALOG = array();
$result = $db->query("SELECT * FROM {$DT_PRE}category WHERE moduleid=$moduleid AND letter='$letter' ORDER BY listorder,catid ASC");
while($r = $db->fetch_array($result)) {
	$CATALOG[] = $r;
}
include template('letter', 'chip');
?>