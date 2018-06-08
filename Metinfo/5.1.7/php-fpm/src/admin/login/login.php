<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$admin_index=FALSE;
require_once '../include/common.inc.php';
$admincp_ok = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$metinfo_admin_name' and admin_pass='$metinfo_admin_pass'");
if($metinfo_admin_name&&$metinfo_admin_pass&&$admincp_ok){
Header("Location: ../index.php");
}else{
if($met_admin_type_ok==0)$met_admin_type_display="none";
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
if($metinfo_mobile){
include template('mobile/login');
}else{
include template('login');
}
footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>