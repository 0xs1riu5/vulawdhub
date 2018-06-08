<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$backurl='../seo/link/index.php?anyid='.$anyid.'&lang='.$lang;
if($action=="del"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
	$query = "delete from $met_link where id='$val'";
	$db->query($query);
	}
	$htmjs = onepagehtm('link','index').'$|$';
	$htmjs.= indexhtm();
	metsave($backurl,'',$depth,$htmjs);
}elseif($action=="delno"){
	$query = "delete from $met_link where show_ok=0 and lang='$lang'";
	$db->query($query);
	$htmjs = onepagehtm('link','index').'$|$';
	$htmjs.= indexhtm();
	metsave($backurl,'',$depth,$htmjs);
}elseif($action=="delall"){
	$query = "delete from $met_link where lang='$lang'";
	$db->query($query);
	$htmjs = onepagehtm('link','index').'$|$';
	$htmjs.= indexhtm();
	metsave($backurl,'',$depth,$htmjs);
}else{
	$admin_list = $db->get_one("SELECT * FROM $met_link WHERE id='$id'");
	if(!$admin_list)metsave('-1',$lang_dataerror,$depth);
	$query = "delete from $met_link where id='$id'";
	$db->query($query);
	$htmjs = onepagehtm('link','index').'$|$';
	$htmjs.= indexhtm();
	metsave($backurl,'',$depth,$htmjs);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
