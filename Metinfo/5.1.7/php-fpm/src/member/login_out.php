<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../include/common.inc.php';
$login_out=1;
if($met_webhtm==0){
$member_index_url="login.php?lang=".$lang;
}else{
$member_index_url="login".$met_htmtype;
}
require_once 'login_check.php';
session_start();
$_SESSION['metinfo_admin_name'] ='';
$_SESSION['metinfo_admin_pass'] ='';
$_SESSION['metinfo_admin_time'] ='';
$_SESSION['metinfo_admin_type'] ='';
$_SESSION['metinfo_admin_pop']  ='';
$_SESSION['metinfo_member_name'] ='';
$_SESSION['metinfo_member_pass'] ='';		  
$_SESSION['metinfo_member_type'] ='';
if(isset($_COOKIE['ps'])) setcookie("ps", "", mktime()-86400*7, "/");
Header("Location: $member_index_url");
exit;
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>