<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once 'login_check.php';
require_once ROOTPATH.'member/index_member.php';
$query="select * from $met_admin_array where array_type='1' and lang='$lang'";
$menber_array_temp=$db->get_all($query);
foreach($menber_array_temp as $key=>$val){
$menber_array[$val['id']]=$val['array_name'];
}
$menber_array[3]='管理员';
$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$metinfo_member_name' ");
if(!$admin_list){
	session_unset();
	$returnurl="login.php?lang=".$lang;
	header("Location: $returnurl");
	exit();
}
$access=$menber_array[$admin_list['usertype']];
$feedback_totalcount = $db->counter($met_feedback, " where customerid='$metinfo_member_name' and lang='$lang' ", "*");
$feedback_totalcount_readyes = $db->counter($met_feedback, " where customerid='$metinfo_member_name' and readok='1' and lang='$lang' ", "*");
$feedback_totalcount_readno = $db->counter($met_feedback, " where customerid='$metinfo_member_name' and readok='0' and lang='$lang' ", "*");

$message_totalcount = $db->counter($met_message, " where customerid='$metinfo_member_name' and lang='$lang' ", "*");
$message_totalcount_readyes = $db->counter($met_message, " where customerid='$metinfo_member_name' and readok='1' and lang='$lang' ", "*");
$message_totalcount_readno = $db->counter($met_message, " where customerid='$metinfo_member_name' and readok='0' and lang='$lang' ", "*");

$cv_totalcount = $db->counter($met_cv, " where customerid='$metinfo_member_name' and lang='$lang' ", "*");
$cv_totalcount_readyes = $db->counter($met_cv, " where customerid='$metinfo_member_name' and readok='1' and lang='$lang' ", "*");
$cv_totalcount_readno = $db->counter($met_cv, " where customerid='$metinfo_member_name' and readok='0' and lang='$lang' ", "*");

$mfname='basic';
include template('member');
footermember();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>