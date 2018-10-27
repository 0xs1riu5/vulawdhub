<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['sitemaps']) {
	file_del(DT_ROOT.'/sitemaps.xml');
	return false;
}
$today = timetodate($DT_TIME, 3);
$mods = explode(',', $MOD['sitemaps_module']);
$nums = intval($MOD['sitemaps_items']/count($mods));
$data = '<?xml version="1.0" encoding="UTF-8"?>';
$data .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$data .= '<url>';
$data .= '<loc>'.DT_PATH.'</loc>';
$data .= '<lastmod>'.$today.'</lastmod>';
$data .= '<changefreq>always</changefreq>';
$data .= '<priority>1.0</priority>';
$data .= '<data><display></display></data>';
$data .= '</url>';
$item = '';
foreach($mods as $mid) {
	if(isset($MODULE[$mid]) && !$MODULE[$mid]['islink'] && !$MODULE[$mid]['domain']) {
		if($mid == 4 && $CFG['com_domain']) continue;
		$url = $MODULE[$mid]['linkurl'];
		$data .= '<url>';
		$data .= '<loc>'.$url.'</loc>';
		$data .= '<lastmod>'.$today.'</lastmod>';
		$data .= '<changefreq>hourly</changefreq>';
		$data .= '<priority>0.9</priority>';
		$data .= '<data><display></display></data>';
		$data .= '</url>';
		if($nums) {
			$fields = $mid == 4 ? 'linkurl' : 'linkurl,edittime';
			$order = $mid == 4 ? 'userid' : 'addtime';
			$condition = $mid == 4 ? "catids<>''" : "status>2";
			$result = $db->query("SELECT $fields FROM ".get_table($mid)." WHERE $condition ORDER BY $order DESC LIMIT $nums");
			while($r = $db->fetch_array($result)) {
				$item .= '<url>';
				$item .= '<loc>'.xml_linkurl($r['linkurl'], $url).'</loc>';
				$item .= '<lastmod>'.($mid == 4 ? $today : timetodate($r['edittime'], 3)).'</lastmod>';
				$item .= '<changefreq>'.$MOD['sitemaps_changefreq'].'</changefreq>';
				$item .= '<priority>'.$MOD['sitemaps_priority'].'</priority>';
				$item .= '<data><display></display></data>';
				$item .= '</url>';
			}
		}
	}
}
$data .= $item;
$data .= '</urlset>';
$data = str_replace('><', ">\n<", $data);
file_put(DT_ROOT.'/sitemaps.xml', $data);
foreach($mods as $mid) {
	if(isset($MODULE[$mid]) && !$MODULE[$mid]['islink'] && $MODULE[$mid]['domain']) {
		if($mid == 4 && $CFG['com_domain']) continue;
		$url = $MODULE[$mid]['linkurl'];
		$data = '<?xml version="1.0" encoding="UTF-8"?>';
		$data .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$data .= '<url>';
		$data .= '<loc>'.$url.'</loc>';
		$data .= '<lastmod>'.$today.'</lastmod>';
		$data .= '<changefreq>always</changefreq>';
		$data .= '<priority>1.0</priority>';
		$data .= '<data><display></display></data>';
		$data .= '</url>';
		foreach(cache_read('category-'.$mid.'.php') as $c) {
			$data .= '<url>';
			$data .= '<loc>'.$url.$c['linkurl'].'</loc>';
			$data .= '<lastmod>'.$today.'</lastmod>';
			$data .= '<changefreq>hourly</changefreq>';
			$data .= '<priority>0.9</priority>';
			$data .= '<data><display></display></data>';
			$data .= '</url>';
		}
		$item = '';
		$nums = intval($MOD['sitemaps_items']);
		if($nums) {
			$fields = $mid == 4 ? 'linkurl' : 'linkurl,edittime';
			$order = $mid == 4 ? 'userid' : 'addtime';
			$condition = $mid == 4 ? "catids<>''" : "status>2";
			$result = $db->query("SELECT $fields FROM ".get_table($mid)." WHERE $condition ORDER BY $order DESC LIMIT $nums");
			while($r = $db->fetch_array($result)) {
				$item .= '<url>';
				$item .= '<loc>'.xml_linkurl($r['linkurl'], $url).'</loc>';
				$item .= '<lastmod>'.($mid == 4 ? $today : timetodate($r['edittime'], 3)).'</lastmod>';
				$item .= '<changefreq>'.$MOD['sitemaps_changefreq'].'</changefreq>';
				$item .= '<priority>'.$MOD['sitemaps_priority'].'</priority>';
				$item .= '<data><display></display></data>';
				$item .= '</url>';
			}
		}
		$data .= $item;
		$data .= '</urlset>';
		$data = str_replace('><', ">\n<", $data);
		file_put(DT_ROOT.'/'.$MODULE[$mid]['moduledir'].'/sitemaps.xml', $data);
	}
}
return true;
?>