<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$navoks[1]='ui-btn-active';
$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE id='$id'");
if(!$admin_list){
metsave('-1',$lang_dataerror);
}
switch($admin_list['usertype']){
	case '1':$access1="selected='selected'";break;
	case '2':$access2="selected='selected'";break;
	default:$access1="selected='selected'";break;
}
$checkid=($admin_list['checkid'])?"checked=checked":"";
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('mobile/member/member_editor');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>