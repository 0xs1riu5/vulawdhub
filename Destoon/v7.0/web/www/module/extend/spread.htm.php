<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$itemid) return false;
$item = $db->get_one("SELECT * FROM {$DT_PRE}spread WHERE itemid=$itemid");
if(!$item) return false;

$filename = DT_CACHE.'/htm/m'.urlencode($item['mid']).'_k'.urlencode($item['word']).'.htm';
if($DT_TIME - @filemtime($filename) < 60) return false;

$result = $db->query("SELECT * FROM {$DT_PRE}spread WHERE mid=$item[mid] AND word='$item[word]' AND fromtime<=$DT_TIME AND totime>=$DT_TIME ORDER BY price DESC,itemid ASC");
$totime = 0;
$itemids = array();
while($r = $db->fetch_array($result)) {
	if($r['totime'] > $totime) $totime = $r['totime'];
	$itemids[] = $r['tid'];
}
if(!$itemids) {
	file_del($filename);
	return false;
}
$spread_itemids = implode(',', $itemids);
$spread_moduleid = $item['mid']; 
$spread_module = $MODULE[$spread_moduleid]['module'];
$id = $spread_moduleid == 4 ? 'userid' : 'itemid';
$bmid = $moduleid;
$moduleid = $spread_moduleid;
$pages = '';
$datetype = 5;
$showpage = 0;
$tags = $tag = array();
$result = $db->query("SELECT * FROM ".get_table($moduleid)." WHERE `{$id}` IN ($spread_itemids)");
while($r = $db->fetch_array($result)) {
	if(strpos($r['linkurl'], '://') === false) $r['linkurl'] = $MODULE[$spread_moduleid]['linkurl'].$r['linkurl'];
	$tag[$r[$id]] = $r;
}
if(!$tag) {
	file_del($filename);
	return false;
}
$spread_url = $EXT['spread_url'].rewrite('index.php?kw='.urlencode($item['word']));
foreach($itemids as $v) {//Order
	if($tag[$v]) $tags[] = $tag[$v];
}
ob_start();
echo '<!--'.$totime.'-->';
include template('spread', 'chip');
$data = ob_get_contents();
ob_clean();
file_put($filename, $data);
$moduleid = $bmid;
return true;
?>