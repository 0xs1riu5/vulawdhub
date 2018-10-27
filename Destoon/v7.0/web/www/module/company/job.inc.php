<?php 
defined('IN_DESTOON') or exit('Access Denied');
$moduleid = 9;
$module = 'job';
$MOD = cache_read('module-'.$moduleid.'.php');
$table = $DT_PRE.'job_'.$moduleid;
$table_data = $DT_PRE.'job_data_'.$moduleid;
if($itemid) {
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	if(!$item || $item['status'] < 3 || $item['username'] != $username) dheader($MENU[$menuid]['linkurl']);
	unset($item['template']);
	extract($item);
	$CAT = get_cat($catid);
	$content_table = content_table($moduleid, $itemid, $MOD['split'], $table_data);
	$t = $db->get_one("SELECT content FROM {$content_table} WHERE itemid=$itemid");
	$content = $t['content'];
	$content = $DT_PC ? parse_video($content) : video5($content);
	$CP = $MOD['cat_property'] && $CAT['property'];
	if($CP) {
		require DT_ROOT.'/include/property.func.php';
		$options = property_option($catid);
		$values = property_value($moduleid, $itemid);
	}
	//do not require common.inc.php
	$CATEGORY = cache_read('category-'.$moduleid.'.php');
	$AREA or $AREA = cache_read('area.php');
	$TYPE = explode('|', trim($MOD['type']));
	$GENDER = explode('|', trim($MOD['gender']));
	$MARRIAGE = explode('|', trim($MOD['marriage']));
	$EDUCATION = explode('|', trim($MOD['education']));
	$SITUATION = explode('|', trim($MOD['situation']));
	$parentid = $CATEGORY[$catid]['parentid'] ? $CATEGORY[$catid]['parentid'] : $catid;
	$expired = $totime && $totime < $DT_TIME ? true : false;
	$linkurl = $MOD['linkurl'].$linkurl;
	if(!$DT_BOT && $MOD['hits']) $db->query("UPDATE LOW_PRIORITY {$table} SET hits=hits+1 WHERE itemid=$itemid", 'UNBUFFERED');
	$head_canonical = $linkurl;
	$head_title = $title.$DT['seo_delimiter'].$head_title;
	$head_keywords = $title.','.$COM['company'];
	$head_description = dsubstr(strip_tags($content), 200, '...');
	if($DT_PC) {
		//
	} else {
		$back_link = userurl($username, "file=$file", $domain);
	}
} else {
	$url = "file=$file";
	$condition = "username='$username' AND status=3";
	if($kw) {
		$condition .= " AND keyword LIKE '%$keyword%'";
		$url .= "&kw=$kw";
		$head_title = $kw.$DT['seo_delimiter'].$head_title;
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
			$r['linkurl'] = $homeurl ? ($DT_PC ? $MOD['linkurl'] : $MOD['mobile']).$r['linkurl'] : userurl($username, "file=$file&itemid=$r[itemid]", $domain);
			if($kw) {
				$r['title'] = str_replace($kw, '<span class="highlight">'.$kw.'</span>', $r['title']);
				$r['introduce'] = str_replace($kw, '<span class="highlight">'.$kw.'</span>', $r['introduce']);
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
include template('job', $template);
?>