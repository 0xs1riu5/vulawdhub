<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
$depth='../';
require_once $depth.'../login/login_check.php';
$backurl="../content/job/cv.php?anyid={$anyid}&lang=".$lang.'&jobid='.$jobid.'&class1='.$class1.'&customerid='.$customerid;
$query = "select * from $met_parameter where lang='$lang' and module='6' and type='5' order by no_order";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
$para_list[]=$list;
}
if($action=="del"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
		if($met_deleteimg){
			foreach($para_list as $key=>$val1){
				$imagelist=$db->get_one("select * from $met_plist where lang='$lang' and  paraid='$val1[id]' and listid='$val'");
				file_unlink($depth."../".$imagelist[info]);
			}
		}
		$query = "delete from $met_plist where listid='$val' and module='6'";
		$db->query($query);
		$query = "delete from $met_cv where id='$val'";
		$db->query($query);
	}
	metsave($backurl,'',$depth);
}else{
	if($met_deleteimg){
		foreach($para_list as $key=>$val){
			$imagelist=$db->get_one("select * from $met_plist where lang='$lang' and  paraid='$val[id]' and listid='$id'");
			file_unlink($depth."../".$imagelist[info]);
		}
	}
	$query = "delete from $met_plist where listid='$id' and module='6'";
	$db->query($query);
	$query = "delete from $met_cv where id='$id'";
	$db->query($query);
	metsave($backurl,'',$depth);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
