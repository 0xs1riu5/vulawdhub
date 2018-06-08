<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
$memberindex="metinfo";
require_once 'login_check.php';
if(!$metid)$metid='index';
if(!preg_match("/^[0-9a-zA-Z\_\-]*$/",$metid))die();
if($metid!='index'){
require_once $metid.'.php';
}else{
require_once ROOTPATH.'member/index_member.php';
//
$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$metinfo_member_name' ");
if(!$admin_list){
    session_unset();
    $returnurl="login.php?lang=".$lang.'&referer='.$referer;
	header("Location: $returnurl");
	exit();
}
$query="select * from $met_admin_array where id='$admin_list[usertype]'";
$uesr_array=$db->get_one($query);
$access=$uesr_array[array_name];

$feedback_totalcount = $db->counter($met_feedback, " where customerid='$metinfo_member_name' and lang='$lang' ", "*");
$feedback_totalcount_readyes = $db->counter($met_feedback, " where customerid='$metinfo_member_name' and readok='1' and lang='$lang' ", "*");
$feedback_totalcount_readno = $db->counter($met_feedback, " where customerid='$metinfo_member_name' and readok='0' and lang='$lang' ", "*");

$message_totalcount = $db->counter($met_message, " where customerid='$metinfo_member_name' and lang='$lang' ", "*");
$message_totalcount_readyes = $db->counter($met_message, " where customerid='$metinfo_member_name' and readok='1' and lang='$lang' ", "*");
$message_totalcount_readno = $db->counter($met_message, " where customerid='$metinfo_member_name' and readok='0' and lang='$lang' ", "*");

$cv_totalcount = $db->counter($met_cv, " where customerid='$metinfo_member_name' and lang='$lang' ", "*");
$cv_totalcount_readyes = $db->counter($met_cv, " where customerid='$metinfo_member_name' and readok='1' and lang='$lang' ", "*");
$cv_totalcount_readno = $db->counter($met_cv, " where customerid='$metinfo_member_name' and readok='0' and lang='$lang' ", "*");
//
$mfname='basic';
include template('member');
footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>