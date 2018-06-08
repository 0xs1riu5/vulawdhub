<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../include/common.inc.php';
if($metinfo_member_name<>""){
   $member_title=$lang_memberIndex2.$metinfo_member_name;
   $member_loginok="<script type='text/javascript'>document.getElementById('login_x1').style.display='none';document.getElementById('login_x2').style.display='';</script>";
 }else{
   $member_title=$lang_memberIndex8;
   $member_loginok="<script type='text/javascript'>document.getElementById('login_x2').style.display='none';document.getElementById('login_x1').style.display='';</script>";
 }
switch($memberaction){
 case "control":
 $met_js=$member_title;
 break;
 case "login":
 $met_js=$member_loginok;
 break;
 case "membername":
 $met_js=$metinfo_member_name;
 break;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
$met_js="<?php echo $met_js; ?>";
document.write($met_js) 