<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
//CATE
if($_catids) {
	$_catid = explode(',', $_catids);
	$result = $db->query("SELECT arrchildid FROM {$DT_PRE}category WHERE catid IN ($_catids)");
	while($r = $db->fetch_array($result)) {
		if($r['arrchildid']) $_childs .= ','.$r['arrchildid'];
	}
	if($_childs) {
		$_childs = substr($_childs, 1);
		$_child = explode(',', $_childs);
		if($catid && !in_array($catid, $_child)) msg('您无权进行此操作 Error(10)');
		if(isset($post['catid']) && $post['catid'] && !in_array($post['catid'], $_child)) msg('您无权进行此操作 Error(11)');
		if($itemid) {
			if(is_array($itemid)) {
				foreach($itemid as $_itemid) {
					item_check($_itemid) or msg('您无权进行此操作 Error(12)');
				}
			} else {
				item_check($itemid) or msg('您无权进行此操作 Error(13)');
			}
		}
	}
}
//CITY
if($_areaids) {
	if($areaid == 0) {
		$areaid = $_aid;
		$ARE = $AREA[$areaid];
	} else {
		if(!in_array($areaid, $_areaid)) msg('您无权进行此操作 Error(20)');
	}
	if(isset($post['areaid']) && $post['areaid'] && !in_array($post['areaid'], $_areaid)) msg('您无权进行此操作 Error(21)');
	if($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $_itemid) {
				city_check($_itemid) or msg('您无权进行此操作 Error(22)');
			}
		} else {
			city_check($itemid) or msg('您无权进行此操作 Error(23)');
		}
	}
}
?>