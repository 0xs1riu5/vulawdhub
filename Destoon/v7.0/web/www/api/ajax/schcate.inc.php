<?php
defined('IN_DESTOON') or exit('Access Denied');
isset($name) or $name = '';
if(!$name || strlen($name) < 2 || strlen($name) > 30) exit;
$limit = $DT['schcate_limit'] ? intval($DT['schcate_limit']) : 10;
$html = '';
$result = $db->query("SELECT catid,arrparentid FROM {$DT_PRE}category WHERE moduleid=$moduleid AND catname LIKE '%$name%' ORDER BY item DESC,catid DESC LIMIT $limit");
while($r = $db->fetch_array($result)) {
	$html .= '<input type="radio" name="dtcate" value="'.$r['catid'].'" onclick="load_category('.$r['catid'].', 1);" id="dtcate_'.$r['catid'].'"/> <label for="dtcate_'.$r['catid'].'">'.strip_tags(cat_pos($r)).'</label><br/>';
}
echo $html;
?>