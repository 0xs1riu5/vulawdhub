<?php
defined('IN_DESTOON') or exit('Access Denied');
if(!$DT['search_tips']) exit;
isset($MODULE[$mid]) or exit;
if(!$word || strlen($word) < 2 || strlen($word) > 30) exit;
foreach(array('&', '=', '(', ',') as $v) {
	strpos($word, $v) === false or exit;
}
$word = str_replace(array(' ','*', "\'"), array('%', '%', ''), $word);
if(preg_match("/^[a-z0-9A-Z]+$/", $word)) {			
	tag("moduleid=$mid&table=keyword&condition=moduleid=$mid and letter like '%$word%'&pagesize=10&order=total_search desc&template=list-search_tip", -2);
} else {
	tag("moduleid=$mid&table=keyword&condition=moduleid=$mid and keyword like '%$word%'&pagesize=10&order=total_search desc&template=list-search_tip", -2);
}
?>