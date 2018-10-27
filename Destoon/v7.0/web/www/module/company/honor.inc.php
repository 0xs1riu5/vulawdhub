<?php 
defined('IN_DESTOON') or exit('Access Denied');
$table = $DT_PRE.'honor';
if($itemid) {
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	if(!$item || $item['status'] < 3 || $item['username'] != $username) dheader($MENU[$menuid]['linkurl']);
	extract($item);
	$image = str_replace('.thumb.'.file_ext($thumb), '', $thumb);
	$content = $DT_PC ? parse_video($content) : video5($content);
	if(!$DT_BOT) $db->query("UPDATE LOW_PRIORITY {$table} SET hits=hits+1 WHERE itemid=$itemid", 'UNBUFFERED');
	$head_title = $title.$DT['seo_delimiter'].$head_title;
	$head_keywords = $title.','.$COM['company'];
	$head_description = dsubstr(strip_tags($content), 200);
	if($DT_PC) {
		//
	} else {
		$back_link = userurl($username, "file=$file", $domain);
	}
} else {
	$url = "file=$file";
	$condition = "username='$username' AND status=3";
	if($kw) {
		$condition .= " AND title LIKE '%$keyword%'";
		$url .= "&kw=$kw";
		$head_title = $kw.$DT['seo_delimiter'].$head_title;
	}
	$demo_url = userurl($username, $url.'&page={destoon_page}', $domain);
	$pagesize =intval($menu_num[$menuid]);
	if(!$pagesize || $pagesize > 100) $pagesize = 10;
	$offset = ($page-1)*$pagesize;
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition", 'CACHE');
	$items = $r['num'];
	$pages = $DT_PC ? home_pages($items, $page, $pagesize, $demo_url) : mobile_pages($items, $page, $pagesize, $demo_url);
	$lists = array();
	if($items) {
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY addtime DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			$r['linkurl'] = userurl($username, "file=$file&itemid=$r[itemid]", $domain);
			$r['image'] = str_replace('.thumb.'.file_ext($r['thumb']), '', $r['thumb']);
			if($kw) {
				$r['title'] = str_replace($kw, '<span class="highlight">'.$kw.'</span>', $r['title']);
			}
			$lists[] = $r;
		}
		$db->free_result($result);
	}
	if($DT_PC) {
		//
	} else {
		if($kw) $back_link = userurl($username, "file=$file", $domain);
	}
}
include template('honor', $template);
?>