<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$mid or $mid = 4;
$areaid = isset($areaid) ? intval($areaid) : 0;
if($mid > 4 && isset($MODULE[$mid]) && !$MODULE[$mid]['islink']) {
	$moduleid = $mid;
	$module = $MODULE[$mid]['module'];
	$modurl = $MODULE[$mid]['linkurl'];
	$table = get_table($mid);
	$rss_title = $MODULE[$mid]['name'];
	if($MOD['feed_enable']) {
		$pagesize = $MOD['feed_pagesize'] ? intval($MOD['feed_pagesize']) : 50;
		$condition = "status=3";
		if($MOD['feed_enable'] == 2) {
			if($kw) $rss_title = $rss_title.$DT['seo_delimiter'].$kw;
			if($keyword) $condition .= " and keyword LIKE '%$keyword%'";
			if($catid) {
				$condition .= $CAT['child'] ? " and catid IN (".$CAT['arrchildid'].")" : " and catid=$catid";
				$rss_title = $rss_title.$DT['seo_delimiter'].strip_tags(cat_pos($catid, $DT['seo_delimiter']));
			}
			if($areaid) {
				$condition .= $ARE['child'] ? " and areaid IN (".$ARE['arrchildid'].")" : " and areaid=$areaid";
				$rss_title = $rss_title.$DT['seo_delimiter'].strip_tags(area_pos($areaid, $DT['seo_delimiter']));
			}
		}
	}
	$rss_title = $rss_title.$DT['seo_delimiter'].$DT['sitename'];
	header("content-type:application/xml");
	echo '<?xml version="1.0" encoding="'.DT_CHARSET.'"?>';
	echo '<rss version="2.0">';
	echo '<channel>';
	echo '<title>'.$rss_title.'</title>';
	echo '<link>'.$modurl.'</link>';
	echo '<pubDate>'.timetodate($DT_TIME).'</pubDate>';	
	if($MOD['feed_enable']) {
		$result = $db->query("SELECT itemid,title,introduce,linkurl,addtime FROM {$table} WHERE {$condition} ORDER BY addtime DESC LIMIT 0,$pagesize", 'CACHE');
		while($r = $db->fetch_array($result)) {
			echo '<item id="'.$r['itemid'].'">';
			echo '<title><![CDATA['.$r['title'].']]></title>';
			echo '<link>'.$modurl.str_replace('&', '&amp;', $r['linkurl']).'</link>';
			echo '<description><![CDATA['.$r['introduce'].']]></description>';
			echo '<pubDate>'.timetodate($r['addtime'], 6).'</pubDate>';
			echo '</item>';
		}
	} else {
		echo '<item id="0">';
		echo '<title><![CDATA['.$L['rss_close'].']]></title>';
		echo '<link>'.DT_PATH.'</link>';
		echo '<description><![CDATA['.$L['rss_close'].']]></description>';
		echo '<pubDate>'.timetodate($DT_TIME, 6).'</pubDate>';
		echo '</item>';
	}
	echo '</channel>';
	echo '</rss>';
} else {
	dheader('./');
}
?>