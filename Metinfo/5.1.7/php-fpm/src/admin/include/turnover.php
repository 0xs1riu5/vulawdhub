<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../login/login_check.php';
if($hml){
	$hlist = $db->get_one("SELECT * FROM $met_config WHERE id='{$hml}' and name='metsave_html_list' and lang='{$lang}'");
	$query = "delete from $met_config where id='{$hml}' and name='metsave_html_list' and lang='$lang'";
	$db->query($query);
	$hml=$hlist['value'];
}
$text = $text==''?$lang_jsok:$text;
$gettime=2000;
$text1=urlencode($text);
$geturl=$geturl!='-1'?$geturl.'&turnovertext='.$text1:$geturl;
$getjs = "location.href='{$geturl}'";
$rf=$hml!=''&&$met_webhtm?'1':'';
$rfhm=$hml!=''&&$met_webhtm?0:1;
if($prent){
	if($prent==2)$getjs = "parent.window.location.reload(); ";
}
if($geturl=='-1'){
	$rf='1';
	$geturl='javascript:methistory(1);';
	$getjs ='history.go(-1)';
}
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('turnover');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>