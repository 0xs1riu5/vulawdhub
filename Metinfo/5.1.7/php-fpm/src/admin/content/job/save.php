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
		$filenameok = $db->get_one("SELECT * FROM $met_job WHERE filename='$filename'");
		if($filenameok)$metinfo=0;
		if(is_numeric($filename) && $filename!=$id && $met_pseudo){
			$filenameok1 = $db->get_one("SELECT * FROM {$met_job} WHERE id='{$filename}'");
			if($filenameok1)$metinfo=2;
		}
	}
	echo $metinfo;
	die;
}  
if($action=="add"){
	if($filename!=''){
		$filenameok = $db->get_one("SELECT * FROM $met_job WHERE filename='$filename'");
		if($filenameok)metsave('-1',$lang_modFilenameok,$depth);
	}
	$query = "INSERT INTO $met_job SET
						position           = '$position',
						count              = '$count',
						place              = '$place',
						deal               = '$deal',
						content            = '$content',
						useful_life        = '$useful_life',
						addtime            = '$addtime',
						access			   = '$access',
						lang			   = '$lang',
						no_order		   = '$no_order',
						filename           = '$filename',
						email              = '$email',
						wap_ok             = '$wap_ok',
						top_ok             = '$top_ok'";
			 $db->query($query);	 
	$later_job=$db->get_one("select * from $met_job where lang='$lang' order by id desc");
	$id=$later_job[id];
	$htmjs =indexhtm().'$|$';
	$htmjs.=contenthtm($class1,$id,'showjob',$filename,0,'job',$addtime).'$|$';
	$htmjs.=classhtm($class1,0,0);
	metsave('../content/job/index.php?anyid='.$anyid.'&lang='.$lang.'&class1='.$class1,'',$depth,$htmjs);
}

if($action=="editor"){
	if($filename!='' && $filename != $filenameold){
		$filenameok = $db->get_one("SELECT * FROM $met_job WHERE filename='$filename'");
		if($filenameok)metsave('-1',$lang_modFilenameok,$depth);
	}
	$query = "update $met_job SET 
						  position           = '$position',
						  place              = '$place',
						  deal               = '$deal',
						  content            = '$content',
						  count              = '$count',
						  useful_life        = '$useful_life',
						  addtime            = '$addtime',
						  access			 = '$access',
						  no_order		     = '$no_order',";
	if($metadmin[pagename])$query .= "
						  filename       	 = '$filename',";
						  $query .= "
						  email              = '$email',
						  wap_ok             = '$wap_ok',
						  top_ok             = '$top_ok'
						  where id='$id'";
	$db->query($query);
	$htmjs =indexhtm().'$|$';
	$htmjs.=contenthtm($class1,$id,'showjob',$filename,0,'job',$addtime).'$|$';
	$htmjs.=classhtm($class1,0,0);
	if($filenameold<>$filename and $metadmin[pagename])deletepage($met_class[$class1][foldername],$id,'showjob',$updatetimeold,$filenameold);
	if($top_ok==1)$page=0;
	$turl='../content/job/index.php?anyid='.$anyid.'&lang='.$lang.'&class1='.$class1.'&modify='.$id.'&page='.$page;
	metsave($turl,'',$depth,$htmjs);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
