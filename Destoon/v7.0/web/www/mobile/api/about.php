<?php
require '../../common.inc.php';
require DT_ROOT.'/include/mobile.inc.php';
$table = $DT_PRE.'webpage';
(isset($item) && check_name($item)) or $item = '1';
$lists = array();
$result = $db->query("SELECT * FROM {$table} WHERE item='$item' ORDER BY listorder DESC,itemid DESC LIMIT 50", 'CACHE');
while($r = $db->fetch_array($result)) {
	if(!$r['islink']) {
		$linkurl = $r['domain'] ? $r['domain'] : linkurl($r['linkurl'], 1);
		$r['linkurl'] = str_replace(DT_PATH, DT_MOB, $linkurl);
	}
	$lists[] = $r;
}
$db->free_result($result);
$head_title = $head_name = $L['about_title'];
$back_link = DT_MOB.'more.php';
$foot = 'more';
include template('about', 'mobile');
?>