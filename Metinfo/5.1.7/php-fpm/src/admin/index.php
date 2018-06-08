<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$admin_index=TRUE;
require_once 'login/login_check.php';
if($action=="renameadmin"){
	$adminfile=$url_array[count($url_array)-2];
	if($met_adminfile!=""&&$met_adminfile!=$adminfile){
		$oldname='../'.$adminfile;
		$newname='../'.$met_adminfile;
		if(rename($oldname,$newname)){
			echo "<script type='text/javascript'> alert('{$lang_authTip11}'); document.write('{$lang_authTip12}'); top.location.href='{$newname}'; </script>";
			die();
		}else{
			echo "<script type='text/javascript'> alert('{$lang_adminwenjian}'); top.location.reload(); </script>";
			die();
		}
	}
}
$authinfo = $db->get_one("SELECT * FROM $met_otherinfo where id=1");
$appaddok=$db->get_one("SELECT * FROM $met_app where name!=''");
$appaddok=$appaddok?1:0;
$css_url="templates/".$met_skin."/css";
$img_url="templates/".$met_skin."/images";
if($metinfo_mobile){
Header("Location: mobile/index.php");
exit;
}else{
include template('index');
}
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>