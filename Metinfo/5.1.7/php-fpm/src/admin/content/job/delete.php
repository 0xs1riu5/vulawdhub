<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$backurl="../content/job/index.php?anyid={$anyid}&lang=$lang&class1=$class1&customerid=$customerid";
if($met_htmpagename==2)$folder=$db->get_one("select * from $met_column where id='$class1'");
if($action=="del"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
		if($met_webhtm!=0 or $metadmin[pagename]){
			$job_list=$db->get_one("select * from $met_news where id='$val'");
			if($met_htmpagename==1)$updatetime=date('Ymd',strtotime($job_list[updatetime]));
			deletepage($folder[foldername],$val,'job',$updatetime,$job_list[filename]);
		}
		$query = "delete from $met_job where id='$val'";
		$db->query($query);
	}
	$htmjs =indexhtm().'$|$';
	$htmjs.=classhtm($class1,0,0);
	metsave($backurl,'',$depth,$htmjs);
}elseif($action=="editor"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
		$no_order = "no_order_$val";
		$no_order = $$no_order;
		$query = "update $met_job SET
			no_order   = '$no_order',
			lang       = '$lang'
			where id='$val'";
		$db->query($query);
	}
	$htmjs =indexhtm().'$|$';
	$htmjs.=classhtm($class1,0,0);
	metsave($backurl,'',$depth,$htmjs);
}else{
	$job_list = $db->get_one("SELECT * FROM $met_job WHERE id='$id'");
	if(!$job_list)metsave('-1',$lang_dataerror,$depth);
	if($met_webhtm!=0 or $metadmin[pagename]){
		if($met_htmpagename==1)$updatetime=date('Ymd',strtotime($job_list[updatetime]));
		deletepage($folder[foldername],$id,'shownews',$updatetime,$job_list[filename]);
	}
	$query = "delete from $met_job where id='$id'";
	$db->query($query);
	$htmjs =indexhtm().'$|$';
	$htmjs.=classhtm($class1,0,0);
	metsave($backurl,'',$depth,$htmjs);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
