<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($action=="del"){
$allidlist=explode(',',$allid);
$total_count = $db->counter($met_admin_array, "where lang='$lang' and array_type='1'", "*");
if($total_count==count($allidlist)-1)metsave('-1','必须保留一个会员分组');
foreach($allidlist as $key=>$val){
	$admin_list = $db->get_one("SELECT * FROM $met_admin_array WHERE id='$val' and lang='$lang' and array_type='1'");
	$query = "delete from $met_admin_array where id='$val'";
	$db->query($query);
}
metsave('../member/array.php?lang='.$lang.'&anyid='.$anyid);
}
else{
	$total_count = $db->counter($met_admin_array, "where lang='$lang' and array_type='1' and lang='$lang' and array_type='1'", "*");
	if($total_count==1)metsave('-1','必须保留一个会员分组');
	$admin_list = $db->get_one("SELECT * FROM $met_admin_array WHERE id='$id'");
	if(!$admin_list)metsave('-1',$lang_dataerror);
	$query = "delete from $met_admin_array where id='$id'";
	$db->query($query);
	metsave('../member/array.php?lang='.$lang.'&anyid='.$anyid);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
