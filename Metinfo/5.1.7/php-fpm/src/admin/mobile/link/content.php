<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$navoks[1]='ui-btn-active';
if($action=='editor'){
	$link_list=$db->get_one("select * from $met_link where id='$id'");
	if(!$link_list){
		metsave('-1',$lang_dataerror,$depth);
	}
	$link_type[$link_list[link_type]]="checked";
	$link_lang[$link_list[link_lang]]="checked";
	$show_ok1=$link_list[show_ok]?"checked":"";
	$com_ok1=$link_list[com_ok]?"checked":"";
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('mobile/link/link_content');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>