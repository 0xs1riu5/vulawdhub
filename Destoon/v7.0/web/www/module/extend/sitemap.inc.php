<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
($mid > 3 && isset($MODULE[$mid]) && !$MODULE[$mid]['islink']) or $mid = 0;
$LETTER = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
$letter = isset($letter) ? strtolower($letter) : '';
in_array($letter, $LETTER) or $letter = '';
$head_title = $L['sitemap_title'];
if($mid) {
	$moduleid = $mid;
	$M = $MODULE[$mid];
	$head_title = $M['name'].$DT['seo_delimiter'].$head_title;
	if($letter) {
		$action = 'letter';
		$CATALOG = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}category WHERE moduleid=$mid AND letter='$letter' ORDER BY listorder,catid ASC");
		while($r = $db->fetch_array($result)) {
			$CATALOG[] = $r;
		}
		$head_title = strtoupper($letter).$DT['seo_delimiter'].$head_title;
	} else {
		$action = 'module';
	}
} else {
	$action = 'sitemap';
}
$template = 'sitemap';
$head_keywords = $head_description = '';
if($DT_PC) {
	$CSS = array('catalog');
	$destoon_task = rand_task();
	if($EXT['mobile_enable']) $head_mobile = str_replace(DT_PATH, DT_MOB, $DT_URL);
} else {
	$foot = '';
	$head_name = $L['sitemap_title'];
	if($action == 'letter') {
		$back_link = DT_MOB.'sitemap/'.rewrite('index.php?mid='.$mid);
	} elseif($action == 'module') {
		$back_link = DT_MOB.'sitemap/';
	} else {
		$back_link = DT_MOB.'more.php';
	}
}
include template($template, $module);
?>