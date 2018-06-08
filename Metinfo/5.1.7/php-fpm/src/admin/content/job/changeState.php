<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$backurl="../content/job/index.php?anyid={$anyid}&lang=$lang&class1=$class1";
if($action=='copy'){
	$query= "select * from $met_column where id='$copyclass1'";
	$result1=$db->get_one($query);
	if(!$result1){
		metsave('-1',$lang_dataerror,$depth);
		exit();
	}
	$allidlist=explode(',',$allid);
	$k=count($allidlist)-1;
	for($i=0;$i<$k; $i++){
		$query="select * from {$met_job} where id='{$allidlist[$i]}'";
		$copy=$db->get_one($query);
		$copy[content]=str_replace('\'','\'\'',$copy[content]);
		$query = "insert into {$met_job} set position='$copy[position]',count='$copy[count]',place='$copy[place]',deal='$copy[deal]',addtime='$copy[addtime]',useful_life='$copy[useful_life]',content='$copy[content]',access='$copy[access]',no_order='$copy[no_order]',wap_ok='$copy[wap_ok]',top_ok='$copy[top_ok]',email='$copy[email]',lang='{$copylang}'";
		$db->query($query);
	}
	metsave($backurl,'',$depth);
}elseif($action=="moveto"){
	$allidlist=explode(',',$allid);
	$k=count($allidlist)-1;
	$query= "select * from $met_column where id='$moveclass1'";
	$result1=$db->get_one($query);
	if(!$result1){
		metsave('-1',$lang_dataerror,$depth);
		exit();
	}
	for($i=0;$i<$k; $i++){
		$filname= '';
		if($movelang!=$lang)$filname = "filename = '',";
		$query = "update {$met_job} SET";
		$query = $query."
						  access             = '$access',
						  {$filname}
						  lang               = '$movelang'";
		$query = $query." where id='$allidlist[$i]'";
		$db->query($query);
	}
	metsave($backurl,'',$depth);
}else{
	$job_list = $db->get_one("SELECT * FROM $met_job WHERE id='$id'");
	if(!$job_list)metsave('-1',$lang_loginNoid,$depth);
	$query = "update $met_job SET ";
	if(isset($top_ok)){
		$top_ok=$top_ok==1?0:1;
		$query = $query."top_ok             = '$top_ok',";
	}
	$query = $query."id='$id' where id='$id'";
	$db->query($query);
	if($top_ok==1)$page=0;
	metsave($backurl.'&modify='.$id.'&page='.$page,'',$depth);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
