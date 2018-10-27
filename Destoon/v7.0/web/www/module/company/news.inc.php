<?php 
defined('IN_DESTOON') or exit('Access Denied');
$table = $DT_PRE.'news';
$table_data = $DT_PRE.'news_data';
if($action == 'company') {
	$condition = "status=3";
	if($keyword) $condition .= " AND title LIKE '%$keyword%'";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition");
	$items = $r['num'];
	$pages = $DT_PC ? pages($items, $page, $pagesize) : mobile_pages($items, $page, $pagesize);
	$lists = array();
	if($items) {
		$result = $db->query("SELECT * FROM {$table} WHERE $condition ORDER BY addtime DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			$r['date'] = timetodate($r['addtime'], 3);
			$lists[] = $r;
		}
		$db->free_result($result);
	}
	if($DT_PC) {
		if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
		$CSS = array('article');
	} else {
		$back_link = ($kw || $page > 1) ? rewrite('news.php?page=1') : $MOD['mobile'];
	}
	$head_title = $L['news_title'].$DT['seo_delimiter'].$MOD['name'];
	if($kw) $head_title = $kw.$DT['seo_delimiter'].$head_title;
	include template('news', $module);
	exit;
}
$TYPE = get_type('news-'.$userid);
$_TP = sort_type($TYPE);
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
		$head_name = isset($TYPE[$typeid]) ? $TYPE[$typeid]['typename'] : $head_title;
	}
} else {
	$typeid = isset($typeid) ? intval($typeid) : 0;
	$url = "file=$file";
	$condition = "username='$username' AND status=3";
	if($kw) {
		$condition .= " AND title LIKE '%$keyword%'";
		$url .= "&kw=$kw";
		$head_title = $kw.$DT['seo_delimiter'].$head_title;
	}
	if($typeid) {
		$condition .= " AND typeid='$typeid'";
		$url .= "&typeid=$typeid";
		$head_title = $TYPE[$typeid]['typename'].$DT['seo_delimiter'].$head_title;
	}
	$demo_url = userurl($username, $url.'&page={destoon_page}', $domain);

	$pagesize =intval($menu_num[$menuid]);
	if(!$pagesize || $pagesize > 100) $pagesize = 30;

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
			if($kw) $r['title'] = str_replace($kw, '<span class="highlight">'.$kw.'</span>', $r['title']);
			$lists[] = $r;
		}
		$db->free_result($result);
	}
	if($DT_PC) {
		//
	} else {
		if($kw || $typeid) $back_link = userurl($username, "file=$file", $domain);
		$head_name = $typeid ? $TYPE[$typeid]['typename'] : $head_title;
	}
}
include template('news', $template);
?>