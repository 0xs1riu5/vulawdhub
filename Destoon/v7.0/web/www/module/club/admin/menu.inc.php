<?php
defined('DT_ADMIN') or exit('Access Denied');
$menu = array(
	array("商圈管理", "?moduleid=$moduleid&file=group"),
	array("帖子管理", "?moduleid=$moduleid"),
	array("回复管理", "?moduleid=$moduleid&file=reply"),
	array("粉丝管理", "?moduleid=$moduleid&file=fans"),
	array("管理记录", "?moduleid=$moduleid&file=manage"),
	array("分类管理", "?file=category&mid=$moduleid"),
	array("更新数据", "?moduleid=$moduleid&file=html"),
	array("模块设置", "?moduleid=$moduleid&file=setting"),
);
?>