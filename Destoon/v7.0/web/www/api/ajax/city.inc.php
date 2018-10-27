<?php
defined('IN_DESTOON') or exit('Access Denied');
$lists = array();
$result = $db->query("SELECT areaid,name,style,domain,letter FROM {$DT_PRE}city ORDER BY letter,listorder");
while($r = $db->fetch_array($result)) {
	$r['linkurl'] = $r['domain'] ? $r['domain'] : DT_PATH.'api/'.rewrite('city.php?areaid='.$r['areaid']);
	$lists[strtoupper($r['letter'])][] = $r;
}
include template('city', 'chip');
?>