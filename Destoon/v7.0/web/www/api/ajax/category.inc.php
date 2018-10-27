<?php
defined('IN_DESTOON') or exit('Access Denied');
$category_title = isset($category_title) ? strip_tags($category_title) : '';
$category_extend = isset($category_extend) ? decrypt($category_extend, DT_KEY.'CAT') : '';
$category_moduleid = isset($category_moduleid) ? intval($category_moduleid) : 1;
if(!$category_moduleid) exit;
$category_deep = isset($category_deep) ? intval($category_deep) : 0;
$cat_id = isset($cat_id) ? intval($cat_id) : 1;
$_child = array();
if($_groupid == 1 && $_admin == 2) {
	$R = cache_read('right-'.$_userid.'.php');
	if(isset($R[$category_moduleid]['index']['catid'])) {
		$_catids = $R[$category_moduleid]['index']['catid'];
		if($_catids && is_array($_catids)) {
			$_childs = '';
			$result = $db->query("SELECT arrchildid FROM {$DT_PRE}category WHERE catid IN (".implode(',', $_catids).")");
			while($r = $db->fetch_array($result)) {
				$_childs .= ','.$r['arrchildid'];
			}
			if($_childs) {
				$_childs = substr($_childs, 1);
				$_child = explode(',', $_childs);
			}
		}
	}
}
echo get_category_select($category_title, $catid, $category_moduleid, $category_extend, $category_deep, $cat_id);
?>