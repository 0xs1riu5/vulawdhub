<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($action=="modify"){
if($met_member_force==""){
metsave('-1');
}
require_once $depth.'../include/config.php';
if(!$met_member_use){
$query = "update $met_column SET nav='0' where module=10 and lang='$lang'";
$db->query($query);
}else{
$membernow=$db->get_one("SELECT * FROM $met_column where module=10 and lang='$lang'");
if(!$membernow[nav]){
$query = "update $met_column SET nav='2' where module=10 and lang='$lang'";
$db->query($query);
}
}
metsave('../member/member.php?lang='.$lang.'&anyid='.$anyid);
}
else{
$met_member_use1[$met_member_use]="checked='checked'";
$met_member_login1[$met_member_login]="checked='checked'";
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('member/set_member');
footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>