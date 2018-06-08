<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../login/login_check.php';

if($action=="add"){
	if($admin_if)metsave('-1',$lang_loginUserMudb1);
	$query="select * from $met_admin_array where user_webpower='$user_webpower' and lang='$lang'";
	if($db->get_one($query)){
	metsave('../member/addarray.php?lang='.$lang.'&anyid='.$anyid,$lang_memberwebpower);
	}
	$query = "INSERT INTO $met_admin_array SET
				  array_name         = '$array_name',
				  admin_type         = '',
				  admin_ok           = '0',
				  admin_op           = '',
				  admin_issueok      = '0',
				  admin_group        = '0',
				  user_webpower      = '$user_webpower',
				  array_type         = '1',
				  lang               = '$lang',
				  langok             = ''";
	$db->query($query);
	metsave('../member/array.php?lang='.$lang.'&anyid='.$anyid);
}

if($action=="editor"){
	$query="select * from $met_admin_array where user_webpower='$user_webpower' and id!='$id' and lang='$lang'";
	if($db->get_one($query)){
		metsave('../member/arrayeditor.php?lang='.$lang.'&anyid='.$anyid.'&id='.$id,$lang_memberwebpower);
	}
	$query = "update $met_admin_array SET
						  array_name         = '$array_name',
						  admin_type         = '',
						  admin_ok           = '0',
						  admin_op           = '',
						  admin_issueok      = '0',
						  admin_group        = '0',
						  user_webpower      = '$user_webpower',
						  array_type         = '1',
						  lang               = '$lang',
						  langok             = ''";
	$query .="  where id='$id'";
	$db->query($query);
	metsave('../member/array.php?lang='.$lang.'&anyid='.$anyid);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
