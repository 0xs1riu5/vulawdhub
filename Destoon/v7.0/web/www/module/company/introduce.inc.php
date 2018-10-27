<?php 
defined('IN_DESTOON') or exit('Access Denied');
$table = $DT_PRE.'page';
$table_data = $DT_PRE.'page_data';
if($itemid) {
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	if(!$item || $item['status'] < 3 || $item['username'] != $username) dheader($MENU[$menuid]['linkurl']);
	extract($item);
	$t = $db->get_one("SELECT content FROM {$table_data} WHERE itemid=$itemid");
	$content = $t['content'];
	$content = $DT_PC ? parse_video($content) : video5($content);
	if(!$DT_BOT) $db->query("UPDATE LOW_PRIORITY {$table} SET hits=hits+1 WHERE itemid=$itemid", 'UNBUFFERED');
	$head_title = $title.$DT['seo_delimiter'].$head_title;
	$head_keywords = $title.','.$COM['company'];
	$head_description = get_intro($content, 200);
	if($DT_PC) {
		//
	} else {
		$back_link = userurl($username, "file=$file", $domain);
		$head_name = $title;
	}
} else {
	$content_table = content_table(4, $userid, is_file(DT_CACHE.'/4.part'), $DT_PRE.'company_data');
	$t = $db->get_one("SELECT content FROM {$content_table} WHERE userid=$userid");
	$content = $t['content'];
	$thumb = $COM['thumb'];
	$video = isset($HOME['video']) ? $HOME['video'] : '';
	if($DT_PC) {
		//
	} else {
		$head_name = $head_title;
	}
}
$TYPE = array();
$result = $db->query("SELECT itemid,title,style FROM {$table} WHERE status=3 AND username='$username' ORDER BY listorder DESC,addtime DESC", 'CACHE');
while($r = $db->fetch_array($result)) {
	$r['alt'] = $r['title'];
	$r['title'] = set_style($r['title'], $r['style']);
	$r['linkurl'] = userurl($username, "file=$file&itemid=$r[itemid]", $domain);
	$TYPE[] = $r;
}
include template($file, $template);
?>