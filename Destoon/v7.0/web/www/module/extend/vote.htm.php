<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$itemid) return false;
$item = $db->get_one("SELECT * FROM {$DT_PRE}vote WHERE itemid=$itemid");
if(!$item) return false;
extract($item);
$votes = array();
for($i = 1; $i < 11; $i++) {
	$s = 's'.$i;
	if($$s) $votes[$i] = $$s;
}
$type = $choose ? 'checkbox' : 'radio';
$template = $item['template_vote'] ? $item['template_vote'] : 'vote';
ob_start();
include template($template, 'chip');
$data = ob_get_contents();
ob_clean();
file_put(DT_CACHE.'/htm/vote_'.$itemid.'.htm', $data);
return true;
?>