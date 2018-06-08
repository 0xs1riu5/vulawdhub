<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($action=="del"){
$allidlist=explode(',',$allid);
foreach($allidlist as $key=>$val){
$query = "delete from $met_admin_table where id='$val'";
$db->query($query);
}
metsave('../member/index.php?lang='.$lang.'&anyid='.$anyid);
}
else if($action=='delactive'){
	$query = "delete from $met_admin_table where admin_ok=0 and checkid=0";
	$db->query($query);
	metsave('../member/index.php?lang='.$lang.'&anyid='.$anyid);
}
else{
$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE id='$id'");
if(!$admin_list)metsave('-1',$lang_dataerror);
$query = "delete from $met_admin_table where id='$id'";
$db->query($query);
metsave('../member/index.php?lang='.$lang.'&anyid='.$anyid);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>