<?php
defined('IN_DESTOON') or exit('Access Denied');
if($job == 'isell') {
	tag("moduleid=5&length=14&condition=status=3 and level>0 and thumb<>''&areaid=$cityid&pagesize=".$DT['page_sell']."&order=addtime desc&width=100&height=100&cols=5&target=_blank&template=thumb-table&page=$page");
} else if($job == 'imall') {
	tag("moduleid=16&length=14&condition=status=3&areaid=$cityid&pagesize=".$DT['page_mall']."&order=orders desc&width=100&height=100&cols=5&target=_blank&template=thumb-mall&page=$page");
} else {
	//
}
?>