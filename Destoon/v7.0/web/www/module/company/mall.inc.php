<?php 
defined('IN_DESTOON') or exit('Access Denied');
$moduleid = 16;
$module = 'mall';
$MOD = cache_read('module-'.$moduleid.'.php');
$table = $DT_PRE.'mall_'.$moduleid;
$table_data = $DT_PRE.'mall_data_'.$moduleid;
$TYPE = get_type('mall-'.$userid);
$_TP = sort_type($TYPE);
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
	require DT_ROOT.'/module/'.$module.'/global.func.php';
	$RL = $relate_id ? get_relate($item) : array();
	$P1 = get_nv($n1, $v1);
	$P2 = get_nv($n2, $v2);
	$P3 = get_nv($n3, $v3);
	if($step) {
		extract(unserialize($step));
	} else {
		$a1 = 1;
		$p1 = $item['price'];
		$a2 = $a3 = $p2 = $p3 = '';
	}
	$unit or $unit = $L['unit'];
	$adddate = timetodate($addtime, 3);
	$editdate = timetodate($edittime, 3);
	$linkurl = $MOD['linkurl'].$linkurl;
	$thumbs = get_albums($item);
	$albums =  get_albums($item, 1);
	$album_js = 1;
	$typeid = $mycatid;
	$update = '';
	if(!$DT_BOT) include DT_ROOT.'/include/update.inc.php';
	$head_canonical = $linkurl;
	$head_title = $title.$DT['seo_delimiter'].$head_title;
	$head_keywords = $keyword;
	$head_description = $introduce ? $introduce : $title;
	if($DT_PC) {
		//
	} else {
		$member = array();
		$fee = get_fee($item['fee'], $MOD['fee_view']);
		include DT_ROOT.'/mobile/api/contact.inc.php';
		$back_link = userurl($username, "file=$file", $domain);
	}
} else {
	$typeid = isset($typeid) ? intval($typeid) : 0;
	$view = isset($view) ? 1 : 0;
	$url = "file=$file";
	$condition = "username='$username' AND status=3";
	if($typeid) {
		if($TYPE[$typeid]['parentid']) {
			$condition .= " AND mycatid='$typeid'";
		} else {
			$cids = $typeid.',';
			foreach($TYPE as $k=>$v) {
				if($v['parentid'] == $typeid) $cids .= $k.',';
			}
			$cids = substr($cids, 0, -1);
			$condition .= " AND mycatid IN ($cids)";
		}
		$url .= "&typeid=$typeid";
		$head_title = $TYPE[$typeid]['typename'].$DT['seo_delimiter'].$head_title;
	}
	if($kw) {
		$condition .= " AND keyword LIKE '%$keyword%'";
		$url .= "&kw=$kw";
		$head_title = $kw.$DT['seo_delimiter'].$head_title;
	}
	if($view) {
		$url .= "&view=$view";
	}
	$demo_url = userurl($username, $url.'&page={destoon_page}', $domain);
	$pagesize = intval($menu_num[$menuid]);
	if(!$pagesize || $pagesize > 100) $pagesize = 16;
	if($view) $pagesize = ceil($pagesize/2);
	$offset = ($page-1)*$pagesize;
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition", 'CACHE');
	$items = $r['num'];
	$pages = $DT_PC ? home_pages($items, $page, $pagesize, $demo_url) : mobile_pages($items, $page, $pagesize, $demo_url);
	$lists = array();
	if($items) {
		$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE $condition ORDER BY edittime DESC LIMIT $offset,$pagesize");
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
		if($kw || $typeid) $back_link = userurl($username, "file=$file", $domain);
		$head_name = $typeid ? $TYPE[$typeid]['typename'] : $head_title;
	}
}
include template('mall', $template);
?>