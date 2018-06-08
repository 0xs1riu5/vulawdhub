<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=="del"){
	if($met_deleteimg){
		$query = "select * from $met_parameter where lang='$lang' and (module='2' or module='3' or module='4' or module='5') and type='5'";
		$para_list=$db->get_all($query);
	}

	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
	$rid=explode('|',$val);
	switch($rid[1]){
		case 2:
			$recycle=$met_news;
		break;
		case 3:
			$recycle=$met_product;
		break;
		case 4:
			$recycle=$met_download;
		break;
		case 5:
			$recycle=$met_img;
		break;
	}
	if($met_deleteimg){
	$admin_list = $db->get_one("SELECT * FROM $recycle WHERE id='$rid[0]'");
	delimg($admin_list,1,0,$para_list);
	}

	if($rid[1]!=2){
		$query = "delete from $met_plist where listid='$rid[0]' and module='$rid[1]'";
		$db->query($query);
	}
	$query = "delete from $recycle where id='$rid[0]'";
	$db->query($query);

	}
	metsave('../content/recycle/index.php?lang='.$lang.'&anyid='.$anyid,'',$depth);
	}
else if($action=="delall"){
	$query = "select id,imgurl,imgurls,recycle from $met_news where recycle=2";	
	$result = $db->query($query);
	while($list = $db->fetch_array($result)) {$del_list[]=$list;}
	$query = "select id,imgurl,imgurls,displayimg,recycle from $met_product where recycle=3";	
	$result = $db->query($query);
	while($list = $db->fetch_array($result)) {$del_list[]=$list;}
	$query = "select id,downloadurl,recycle from $met_download where recycle=4";	
	$result = $db->query($query);
	while($list = $db->fetch_array($result)) {$del_list[]=$list;}
	$query = "select id,imgurl,imgurls,displayimg,recycle from $met_img where recycle=5";	
	$result = $db->query($query);
	while($list = $db->fetch_array($result)) {$del_list[]=$list;}
	if($met_deleteimg){
		$query = "select * from $met_parameter where lang='$lang' and (module='2' or module='3' or module='4' or module='5') and type='5'";
		$para_list=$db->get_all($query);
	}
	foreach($del_list as $key=>$val){
		if($val['recycle']!=2){
			$query = "delete from $met_plist where listid='$val[id]' and module='$val[recycle]'";
			$db->query($query);
		}
		delimg($val,1,0,$para_list);
	}

	$query = "delete from $met_news where lang='$lang'and recycle=2";
	$db->query($query);
	$query = "delete from $met_product where lang='$lang' and recycle=3";
	$db->query($query);
	$query = "delete from $met_download where lang='$lang' and recycle=4";
	$db->query($query);
	$query = "delete from $met_img where lang='$lang' and recycle=5";
	$db->query($query);

	metsave('../content/recycle/index.php?lang='.$lang.'&anyid='.$anyid,'',$depth);
}
else{
	$rid=explode('|',$id);
	switch($rid[1]){
		case 2:
			$recycle=$met_news;
		break;
		case 3:
			$recycle=$met_product;
		break;
		case 4:
			$recycle=$met_download;
		break;
		case 5:
			$recycle=$met_img;
		break;
	}
	$admin_list = $db->get_one("SELECT * FROM $recycle WHERE id='$rid[0]'");
	if(!$admin_list)metsave('-1',$lang_dataerror,$depth);
	delimg($admin_list,1,$rid[1]);
	if($rid[1]!=2){
		$query = "delete from $met_plist where listid='$rid[0]' and module='$rid[1]'";
		$db->query($query);
	}
	$query = "delete from $recycle where id='$rid[0]'";
	$db->query($query);
	metsave('../content/recycle/index.php?lang='.$lang.'&anyid='.$anyid,'',$depth);
	}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>