<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$backurl ="../content/article/index.php?anyid={$anyid}&lang=$lang&class1=$class1";
$backurl.=$cengci==1?'':($cengci==2?'&class2='.$class2:'&class2='.$class2.'&class3='.$class3);
if($met_htmpagename==2)$folder=$db->get_one("select * from $met_column where id='$class1'");
if($action=="del"){
	$allidlist=explode(',',$allid);	
	foreach($allidlist as $key=>$val){
		if($met_webhtm!=0 or $metadmin[pagename]){
			$news_list=$db->get_one("select * from $met_news where id='$val'");
			if($met_htmpagename==1)$updatetime=date('Ymd',strtotime($news_list[updatetime]));
			deletepage($folder[foldername],$val,'shownews',$updatetime,$news_list[filename]);
			$declass[$news_list['class2']][$news_list['class3']]=1;
		}
		$query = $met_recycle?"update {$met_news} set recycle='2',updatetime='".date('Y-m-d H:i:s')."' where id='$val'":"delete from {$met_news} where id='$val'";
		$db->query($query);
		if(!$met_recycle){if($news_list){delimg($news_list,1,2);}else{delimg($val,3,2);}}
	}
	$htmjs = indexhtm().'$|$';
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
		$query = "update $met_news SET
			no_order       	 = '$no_order',
			lang               = '$lang'
			where id='$val'";
		$db->query($query);
	}
	$htmjs =indexhtm().'$|$';
	$htmjs.=classhtm($class1,$class2,$class3);
	metsave($backurl,'',$depth,$htmjs);
}else{
	$news_list = $db->get_one("SELECT * FROM $met_news WHERE id='$id'");
	if(!$news_list)metsave('-1',$lang_dataerror,$depth);
	if($met_webhtm!=0 or $metadmin[pagename]){
		if($met_htmpagename==1)$updatetime=date('Ymd',strtotime($news_list[updatetime]));
		deletepage($folder[foldername],$id,'shownews',$updatetime,$news_list[filename]);
	}
	$query = $met_recycle?"update $met_news set recycle='2',updatetime='".date('Y-m-d H:i:s')."' where id='$id'":"delete from $met_news where id='$id'";
	$db->query($query);
	if(!$met_recycle){if($news_list){delimg($news_list,1,2);}else{delimg($id,3,2);}}
	$htmjs =indexhtm().'$|$';
	$class1=$news_list['class1'];
	$class2=$news_list['class2'];
	$class3=$news_list['class3'];
	$htmjs.=classhtm($class1,$class2,$class3);
	metsave($backurl,'',$depth,$htmjs);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
