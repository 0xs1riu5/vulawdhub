<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$backurl="../content/message/index.php?anyid={$anyid}&lang={$lang}&class1={$class1}&customerid={$customerid}";
if($action=="del"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
		$query = "delete from $met_message where id='$val'";
		$db->query($query);
	}
	$htmjs = classhtm('message',0,0);
	metsave($backurl,'',$depth,$htmjs);
}elseif($action=="delall"||$action=="delno"||$action=="delyes"||$action=="delnoreply"||$action=="delreply"){
	$mesql=$action=="delno"?"and readok=0":$mesql;
	$mesql=$action=="delyes"?"and readok=1":$mesql;
	$mesql=$action=="delnoreply"?"and useinfo is null":$mesql;
	$mesql=$action=="delreply"?"and useinfo is not null":$mesql;
	$query = "delete from $met_message where lang='$lang' $mesql";
	$db->query($query);
	$htmjs = classhtm('message',0,0);
	metsave($backurl,'',$depth,$htmjs);
}else{
	$admin_list = $db->get_one("SELECT * FROM $met_message WHERE id='$id'");
	if(!$admin_list)metsave('-1',$lang_dataerror,$depth);
	$query = "delete from $met_message where id='$id'";
	$db->query($query);
	$htmjs = classhtm('message',0,0);
	metsave($backurl,'',$depth,$htmjs);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>

