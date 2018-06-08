<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
     require_once '../include/common.inc.php';
if($met_webhtm==0){
$member_login_url="login.php?lang=".$lang;
$member_register_url="register.php?lang=".$lang;
}else{
$member_login_url="login".$met_htmtype;
$member_register_url="register".$met_htmtype;
}
$message_list=$db->get_one("SELECT * FROM $met_message where id='$id' and lang='$lang'");
switch($listinfo){
case 'info':
   if(intval($metinfo_member_type)<intval($metaccess)){
       $met_js_ac=$lang_access;
    }else{
       $met_js_ac=$message_list[info];
    }
break;
case 'useinfo':
   if(intval($metinfo_member_type)<intval($metaccess)){
     $met_js_ac="【<a href='../member/$member_login_url'>$lang_login</a>】【<a href='../member/$member_register_url'>$lang_register</a>】";
	 }else{
       $met_js_ac=$message_list[useinfo];
    }
break;
}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
$met_js="<?php echo $met_js_ac; ?>";
document.write($met_js)