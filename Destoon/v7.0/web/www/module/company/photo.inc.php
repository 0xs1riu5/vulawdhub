<?php 
defined('IN_DESTOON') or exit('Access Denied');
$moduleid = 12;
$module = 'photo';
include DT_ROOT.'/lang/'.DT_LANG.'/'.$module.'.inc.php';
$MOD = cache_read('module-'.$moduleid.'.php');
$table = $DT_PRE.$module.'_'.$moduleid;
$table_data = $DT_PRE.$module.'_data_'.$moduleid;
$table_item = $DT_PRE.$module.'_item_'.$moduleid;
if($itemid) {
	$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
	if(!$item || $item['status'] < 3 || $item['username'] != $username || $item['items'] < 1) dheader($MENU[$menuid]['linkurl']);
	unset($item['template']);
	extract($item);
	$CAT = get_cat($catid);
	$CP = $MOD['cat_property'] && $CAT['property'];
	if($CP) {
		require DT_ROOT.'/include/property.func.php';
		$options = property_option($catid);
		$values = property_value($moduleid, $itemid);
	}
	$adddate = timetodate($addtime, 3);
	$editdate = timetodate($edittime, 3);
	$linkurl = userurl($username, "file=$file&itemid=$itemid", $domain);
	if($open < 3) {
		$_key = $open == 2 ? $password : $answer;
		$str = get_cookie('photo_'.$itemid);
		$pass = $str == md5(md5($DT_IP.$open.$_key.DT_KEY));	
		if($_username && $_username == $username) $pass = true;
	} else {
		$pass = true;
	}
	if($pass) {
		require DT_ROOT.'/module/'.$module.'/global.func.php';
		$view = isset($view) ? 1 : 0;
		if($view) {
			$pagesize = 30;
			$offset = ($page-1)*$pagesize;
			$demo_url = userurl($username, 'file='.$file.'&itemid='.$itemid.'&view=1&page={destoon_page}', $domain).'#p';
			$pages = $DT_PC ? home_pages($items, $page, $pagesize, $demo_url) : mobile_pages($items, $page, $pagesize, $demo_url);
			$T = array();
			$i = 1;
			$result = $db->query("SELECT itemid,thumb,introduce FROM {$table_item} WHERE item=$itemid ORDER BY listorder ASC,itemid ASC LIMIT $offset,$pagesize");
			while($r = $db->fetch_array($result)) {
				$r['number'] = $offset + $i++;
				$r['linkurl'] = userurl($username, 'file='.$file.'&itemid='.$itemid.'&page='.$r['number'], $domain).'#p';
				$r['thumb'] = str_replace('.thumb.', '.middle.', $r['thumb']);
				$r['title'] = $r['introduce'] ? dsubstr($r['introduce'], 46, '..') : '&nbsp;';
				$T[] = $r;
			}
		} else {
			if($page > $items) $page = 1;
			$T = array();
			$result = $db->query("SELECT itemid,thumb,introduce FROM {$table_item} WHERE item=$itemid ORDER BY listorder ASC,itemid ASC");
			while($r = $db->fetch_array($result)) {
				$r['big'] = str_replace('.thumb.'.file_ext($r['thumb']), '', $r['thumb']);
				$T[] = $r;
			}
			$demo_url = userurl($username, "file=$file&itemid=$itemid&page=".'{destoon_page}', $domain);
			$next_photo = $items > 1 ? next_photo($page, $items, $demo_url) : $linkurl;
			$prev_photo = $items > 1 ? prev_photo($page, $items, $demo_url) : $linkurl;
			if($T) {
				$S = side_photo($T, $page, $demo_url);
			} else {
				$S = array();
				$T[0]['thumb'] = DT_SKIN.'image/spacer.gif';
				$T[0]['introduce'] = $L['no_picture'];
			}
			$P = $T[$page-1];
			$P['src'] = $P['big'];
			$content_table = content_table($moduleid, $itemid, $MOD['split'], $table_data);
			$t = $db->get_one("SELECT content FROM {$content_table} WHERE itemid=$itemid");
			$content = $t['content'];
			$content = $DT_PC ? parse_video($content) : video5($content);
		}
	} else {
		$error = '';
		if($submit) {
			if(isset($key) && $key == $_key) {
				$pass = true;
				set_cookie('photo_'.$itemid, md5(md5($DT_IP.$open.$_key.DT_KEY)), $DT_TIME + 86400);
				dheader($linkurl);
			} else {
				$error = $open == 2 ? $L['error_password'] : $L['error_answer'];
			}
		}
	}
	$update = '';
	if(!$DT_BOT) include DT_ROOT.'/include/update.inc.php';
	$head_canonical = $MOD['linkurl'].($page == 1 ? $item['linkurl'] : itemurl($item, $page));
	$head_title = $title.$DT['seo_delimiter'].$head_title;
	$head_keywords = $keyword;
	$head_description = $introduce ? $introduce : $title;
	if($DT_PC) {
		//
	} else {
		$back_link = userurl($username, "file=$file", $domain);
	}
} else {
	$url = "file=$file";
	$condition = "username='$username' AND status=3 AND items>0";
	if($kw) {
		$condition .= " AND keyword LIKE '%$keyword%'";
		$url .= "&kw=$kw";
		$head_title = $kw.$DT['seo_delimiter'].$head_title;
	}
	$demo_url = userurl($username, $url.'&page={destoon_page}', $domain);
	$pagesize =intval($menu_num[$menuid]);
	if(!$pagesize || $pagesize > 100) $pagesize = 16;
	$offset = ($page-1)*$pagesize;
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $condition", 'CACHE');
	$items = $r['num'];
	$pages = $DT_PC ? home_pages($items, $page, $pagesize, $demo_url) : mobile_pages($items, $page, $pagesize, $demo_url);
	$lists = array();
	if($items) {
		$result = $db->query("SELECT ".$MOD['fields']." FROM {$table} WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
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
include template('photo', $template);
?>