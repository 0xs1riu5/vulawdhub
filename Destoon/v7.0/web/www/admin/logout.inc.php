<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
if($CFG['authadmin'] == 'cookie') {
	set_cookie($secretkey, '');
} else {
	session_destroy();
}
$db->query("DELETE FROM {$db->pre}online WHERE userid=$_userid");
set_cookie('auth', '');
set_cookie('userid', '');
msg('已经安全退出网站后台管理', '?');
?>