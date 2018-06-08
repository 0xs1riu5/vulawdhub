<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../include/common.inc.php';
$returnurl=$met_webhtm?login.$met_htmtype:"login.php?lang=".$lang;
if($met_member_use){
if($met_member_login==0)
{
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
okinfo($returnurl,"$lang_js3");
exit();
}

$member_column=$db->get_one("select * from $met_column where module='10' and lang='$lang'");
$metaccess=$member_column[access];
$classnow=$member_column[id];
require_once '../include/head.php';
$class1_info=$class_list[$classnow];
$class_info=$class1_info;
     $show[description]=$class_info[description]?$class_info[description]:$met_keywords;
     $show[keywords]=$class_info[keywords]?$class_info[keywords]:$met_keywords;
	 $met_title=$class_info[name]."--".$met_title;
$member_title="<script language='javascript' src='member.php?memberaction=control&lang=".$lang."'></script>";
require_once '../public/php/methtml.inc.php';
 require_once 'list.php';
if($met_webhtm==0){
$member_index_url="index.php?lang=".$lang;
}else{
$member_index_url="index".$met_htmtype;
}
$mfname='register';
include template('member');
/*
if(file_exists("../templates/".$met_skin_user."/login.".$dataoptimize_html)){
    include template('register');
}else{
include templatemember('register_metinfo');
 }
 */
footer();
}else{
okinfo('../',$lang_memberclose);
exit;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>