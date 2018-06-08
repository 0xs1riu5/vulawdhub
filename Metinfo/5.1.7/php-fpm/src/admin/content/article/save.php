<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$filename=preg_replace("/\s/","_",trim($filename)); 
$filenameold=preg_replace("/\s/","_",trim($filenameold));  
if($filename_okno){
	$metinfo=1;
	$filename=str_replace("\\",'',$filename);
	$filename=unescape($filename);
	if($filename!=''){
		$sql="class1='$class1'";
		foreach($column_pop as $key=>$val){
			if($key!=$lang){
				foreach($val as $key1=>$val1){
					if($val1['foldername']==$met_class[$class1]['foldername'])$sql.=" or class1='$val1[id]'";
				}
			}
		}
		$filenameok = $db->get_one("SELECT * FROM $met_news WHERE ($sql) and filename='$filename'");
		if($filenameok)$metinfo=0;
		if(is_numeric($filename) && $filename!=$id && $met_pseudo){
			$filenameok1 = $db->get_one("SELECT * FROM {$met_news} WHERE id='{$filename}' and class1='$class1'");
			if($filenameok1)$metinfo=2;
		}
	}
	echo $metinfo;
	die;
}  
$save_type=$action=="add"?1:($filename!=$filenameold?2:0);
if($filename!='' && $save_type){
		$sql="class1='$class1'";
		foreach($column_pop as $key=>$val){
			if($key!=$lang){
				foreach($val as $key1=>$val1){
					if($val1['foldername']==$met_class[$class1]['foldername'])$sql.=" or class1='$val1[id]'";
				}
			}
		}
		$sql1=$save_type==2?" and id!=$id":'';
		$filenameok = $db->get_one("SELECT * FROM $met_news WHERE ($sql) {$sql1} and filename='$filename'");
		if($filenameok)metsave('-1',$lang_modFilenameok,$depth);
}
if($action=="add"){
$access=$access<>""?$access:"0";
$query = "INSERT INTO $met_news SET
                      title              = '$title',
                      ctitle             = '$ctitle',
					  keywords           = '$keywords',
					  description        = '$description',
					  content            = '$content',
					  class1             = '$class1',
					  class2             = '$class2',
					  class3             = '$class3',
					  img_ok             = '$img_ok',
					  imgurl             = '$imgurl',
					  imgurls            = '$imgurls',
				      com_ok             = '$com_ok',
				      wap_ok             = '$wap_ok',
					  issue              = '$issue',
					  hits               = '$hits', 
					  addtime            = '$addtime', 
					  updatetime         = '$updatetime',
					  access          	 = '$access',
					  filename       	 = '$filename',
					  no_order       	 = '$no_order',
					  lang          	 = '$lang',
					  top_ok             = '$top_ok'";
         $db->query($query);
$later_news=$db->get_one("select * from $met_news where updatetime='$updatetime' and lang='$lang'");
$id=$later_news[id];
$htmjs = contenthtm($class1,$id,'shownews',$filename,0,'',$addtime).'$|$';
$htmjs.= indexhtm().'$|$';
$htmjs.= classhtm($class1,$class2,$class3);
$turl  ="../content/article/index.php?anyid=$anyid&lang=$lang&class1=$class1&class2=$class2&class3=$class3";
metsave($turl,'',$depth,$htmjs);
}

if($action=="editor"){
$query = "update $met_news SET 
                      title              = '$title',
                      ctitle             = '$ctitle',
					  keywords           = '$keywords',
					  description        = '$description',
					  content            = '$content',
                      class1             = '$class1',
					  class2             = '$class2',
					  class3             = '$class3',";
if($metadmin[newsimage])$query .= "					  
					  img_ok             = '$img_ok',
					  imgurl             = '$imgurl',
					  imgurls            = '$imgurls',";
if($metadmin[newscom])$query .= "	
				      com_ok             = '$com_ok',";
					  $query .= "
					  wap_ok             = '$wap_ok',
					  issue              = '$issue',
					  hits               = '$hits', 
					  addtime            = '$addtime', 
					  updatetime         = '$updatetime',";
if($met_member_use)  $query .= "
					  access			 = '$access',";
if($metadmin[pagename])  $query .= "
					  filename       	 = '$filename',";
					  $query .= "
					  top_ok             = '$top_ok',
					  no_order       	 = '$no_order',
					  lang               = '$lang'
					  where id='$id'";
$db->query($query);
$htmjs = contenthtm($class1,$id,'shownews',$filename,0,'',$addtime).'$|$';
$htmjs.= indexhtm().'$|$';
$htmjs.= classhtm($class1,$class2,$class3);
if($filenameold<>$filename and $metadmin[pagename])deletepage($met_class[$class1][foldername],$id,'shownews',$updatetimeold,$filenameold);
$classnow=$class3?$class3:($class2?$class2:$class1);
if(($addtime != $updatetime && $met_class[$classnow]['list_order']<2) || $top_ok==1)$page=0;
$turl  ="../content/article/index.php?anyid=$anyid&lang=$lang&class1=$class1&class2=$class2&class3=$class3&modify=$id&pcage=$page";
metsave($turl,'',$depth,$htmjs);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>