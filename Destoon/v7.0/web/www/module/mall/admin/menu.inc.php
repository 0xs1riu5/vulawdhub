<?php
defined('DT_ADMIN') or exit('Access Denied');
$menu = array(
	array("添加商品", "?moduleid=$moduleid&action=add"),
	array("商品列表", "?moduleid=$moduleid"),
	array("订单列表", "?moduleid=$moduleid&file=order"),
	array("浏览历史", "?moduleid=$moduleid&file=view"),
	array("审核商品", "?moduleid=$moduleid&action=check"),
	array("商品分类", "?file=category&mid=$moduleid"),
	array("更新数据", "?moduleid=$moduleid&file=html"),
	array("模块设置", "?moduleid=$moduleid&file=setting"),
);
?>