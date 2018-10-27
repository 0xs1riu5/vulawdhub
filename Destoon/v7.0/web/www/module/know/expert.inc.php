<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
isset($username) or $username = '';
if(check_name($username)) {
	$t = $db->get_one("SELECT * FROM {$table_expert} WHERE username='$username'");
	if($t) $itemid = $t['itemid'];
}
if($itemid) {
	$item = $db->get_one("SELECT * FROM {$table_expert} WHERE itemid=$itemid");
	if($item) {
		extract($item);
	} else {
		include load('404.inc');
	}
	$rate = ($answer && $best < $answer) ? dround($best*100/$answer, 2, true).'%' : '100%';
	if(!$DT_BOT && $page == 1) $db->query("UPDATE LOW_PRIORITY {$table_expert} SET hits=hits+1 WHERE itemid=$itemid", 'UNBUFFERED');
	include DT_ROOT.'/include/seo.inc.php';
	$seo_title = $title.$seo_delimiter.$L['expert_title'].$seo_delimiter.$seo_page.$seo_modulename.$seo_delimiter.$seo_sitename;
} else {
	$condition = "1";
	if($keyword) $condition .= " AND title LIKE '%$keyword%'";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table_expert} WHERE $condition");
	$items = $r['num'];
	$pages = $DT_PC ? pages($items, $page, $pagesize) : mobile_pages($items, $page, $pagesize);
	$lists = array();
	if($items) {
		$result = $db->query("SELECT * FROM {$table_expert} WHERE $condition ORDER BY addtime DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			$lists[] = $r;
		}
		$db->free_result($result);
	}
	include DT_ROOT.'/include/seo.inc.php';
	$seo_title = $L['expert_title'].$seo_delimiter.$seo_page.$seo_modulename.$seo_delimiter.$seo_sitename;
}
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	$back_link = ($kw || $page > 1 || $itemid) ? rewrite('expert.php?page=1') : $MOD['mobile'];
}
include template($MOD['template_expert'] ? $MOD['template_expert'] : 'expert', $module);
?>