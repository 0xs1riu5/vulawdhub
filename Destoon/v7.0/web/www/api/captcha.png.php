<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
require '../common.inc.php';
check_referer() or exit;
if($DT_BOT) dhttp(403);
$session = new dsession();
require DT_ROOT.'/include/captcha.class.php';
$do = new captcha;
$do->font = DT_ROOT.'/file/font/'.$DT['water_font'];
if($DT['captcha_cn']) $do->cn = is_file($do->font);
if($action == 'question') {
	$id = isset($id) ? trim($id) : 'questionstr';
	$do->question($id);
} else {
	if($DT['captcha_chars']) $do->chars = trim($DT['captcha_chars']);
	$do->image();
}
?>