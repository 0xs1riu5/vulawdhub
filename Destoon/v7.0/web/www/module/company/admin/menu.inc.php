<?php
defined('DT_ADMIN') or exit('Access Denied');
$menu = array(
	array('公司列表', '?moduleid=4'),
	array(VIP.'管理', '?moduleid=4&file=vip'),
	array('行业分类', '?file=category&mid=4'),
	array('荣誉资质', '?moduleid=2&file=honor'),
	array('公司新闻', '?moduleid=2&file=news'),
	array('公司单页', '?moduleid=2&file=page'),
	array('友情链接', '?moduleid=2&file=link'),
	array('公司模板', '?moduleid=2&file=style'),
	array('更新数据', '?moduleid=4&file=html'),
	array('模块设置', '?moduleid=4&file=setting'),
);
?>