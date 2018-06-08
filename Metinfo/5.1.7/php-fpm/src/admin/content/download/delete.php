<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$backurl ="../content/download/index.php?anyid={$anyid}&lang=$lang&class1=$class1";
$backurl.=$cengci==1?'':($cengci==2?'&class2='.$class2:'&class2='.$class2.'&class3='.$class3);
if($met_htmpagename==2)$folder=$db->get_one("select * from $met_column where id='$class1'");
if($action=="del"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
		if($met_webhtm!=0 or $metadmin[pagename]){
			$download_list=$db->get_one("select * from $met_download where id='$val'");
			if($met_htmpagename==1)$updatetime=date('Ymd',strtotime($download_list[updatetime]));	
			deletepage($folder[foldername],$val,'showdownload',$updatetime,$download_list[filename]);
			$declass[$download_list['class2']][$download_list['class3']]=1;
		}
		if(!$met_recycle){
			$query = "delete from $met_plist where listid='$val' and module='4'";
			$db->query($query);
		}
		$query = $met_recycle?"update $met_download set recycle='4',updatetime='".date('Y-m-d H:i:s')."' where id='$val'":"delete from $met_download where id='$val'";
		$db->query($query);
		if(!$met_recycle){if($download_list){delimg($download_list,1,2);}else{delimg($val,3,2);}}
	}
	$htmjs =indexhtm().'$|$';
	if($met_webhtm==2 or $metadmin[pagename]){
		$htmjs.= classhtm($class1,0,0,0,1,0).'$|$';
		foreach($declass as $key1=>$val1){
			$htmjs.= classhtm($class1,$key1,0,0,2,0).'$|$';
			foreach($val1 as $key2=>$val2){
				$htmjs.= classhtm($class1,$key1,$key2,0,3,0);
			}
		}
	}
	if($met_webhtm==2){
		metsave($backurl,'',$depth,$htmjs);
	}else{
		metsave($backurl,'',$depth);
	}
}elseif($action=="editor"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
		$no_order = "no_order_$val";
		$no_order = $$no_order;
		$query = "update $met_download SET
			no_order   = '$no_order',
			lang       = '$lang'
			where id='$val'";
		$db->query($query);
	}
	$htmjs =indexhtm().'$|$';
	$htmjs.=classhtm($class1,$class2,$class3);
	metsave($backurl,'',$depth,$htmjs);
}else{
	$download_list = $db->get_one("SELECT * FROM $met_download WHERE id='$id'");
	if(!$download_list)metsave('-1',$lang_dataerror,$depth);
	if($met_webhtm!=0 or $metadmin[pagename]){
		if($met_htmpagename==1)$updatetime=date('Ymd',strtotime($download_list[updatetime]));
		deletepage($folder[foldername],$id,'showdownload',$updatetime,$download_list[filename]);
	}
	if(!$met_recycle){
		$query = "delete from $met_plist where listid='$id' and module='4'";
		$db->query($query);
	}
	$query = $met_recycle?"update $met_download set recycle='4',updatetime='".date('Y-m-d H:i:s')."' where id='$id'":"delete from $met_download where id='$id'";
	$db->query($query);
	if(!$met_recycle){if($download_list){delimg($download_list,1,2);}else{delimg($id,3,2);}}
	$htmjs =indexhtm().'$|$';
	$class1=$download_list[class1];
	$class2=$download_list[class2];
	$class3=$download_list[class3];
	$htmjs.=classhtm($class1,$class2,$class3);
	metsave($backurl,'',$depth,$htmjs);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>