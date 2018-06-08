<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
$admin_index=FALSE;
require_once '../include/common.inc.php';
if($referer)$referer=str_replace('$metinfo$','&',$referer);
if($met_member_use){
$member_title="<script language='javascript' src='member.php?memberaction=control&lang=".$lang."'></script>";
$admincp_ok = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$metinfo_member_name' and admin_pass='$metinfo_member_pass' and usertype<3");
if($metinfo_member_name&&$metinfo_member_pass&&$admincp_ok){
Header("Location:$member_index_url");
}else{
require_once ROOTPATH.'member/index_member.php';
if($metinfo_member_name<>""){
   $member_title=$metinfo_member_name.$lang_memberIndex2;
 }else{
   $member_title=$lang_memberIndex8;
 }
 require_once '../public/php/methtml.inc.php';
 require_once 'list.php';

$mfname='login';
include template('member');
footer();
}
}else{
okinfo('../',$lang_memberclose);
exit;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>