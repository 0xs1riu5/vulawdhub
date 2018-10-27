<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$condition = "1";
if($keyword) $condition .= " AND title LIKE '%$keyword%'";
if($catid) $condition .= $CAT['child'] ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
$items = $db->count($table_product, $condition, $DT['cache_search']);
$pages = $DT_PC ? pages($items, $page, $pagesize) : mobile_pages($items, $page, $pagesize);
$lists = array();
$result = $db->query("SELECT * FROM {$table_product} WHERE $condition ORDER BY addtime DESC LIMIT $offset,$pagesize", $DT['cache_search'] && $page == 1 ? 'CACHE' : '', $DT['cache_search']);
while($r = $db->fetch_array($result)) {
	$r['mobile'] = $MOD['mobile'].rewrite('price.php?itemid='.$r['itemid']);
	$r['linkurl'] = $MOD['linkurl'].rewrite('price.php?itemid='.$r['itemid']);
	$lists[] = $r;
}
$head_title = $L['product_title'].$DT['seo_delimiter'].$MOD['name'];
if($catid) $head_title = $CAT['catname'].$DT['seo_delimiter'].$head_title;
if($kw) $head_title = $kw.$DT['seo_delimiter'].$head_title;
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	$back_link = ($kw || $page > 1) ? rewrite('product.php?page=1') : $MOD['mobile'];
}
include template($MOD['template_product'] ? $MOD['template_product'] : 'product', $module);
?>