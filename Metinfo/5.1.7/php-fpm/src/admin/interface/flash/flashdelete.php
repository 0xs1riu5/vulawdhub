<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$rurl='../interface/flash/flash.php?anyid='.$anyid.'&lang='.$lang.'&module='.$module;
if($action=="del"){
	$allidlist=explode(',',$allid);
	foreach($allidlist as $key=>$val){
		if($met_deleteimg){
			$flashrec=$db->get_one("SELECT * FROM $met_flash where id='$val'");
			file_unlink("../../".$flashrec[img_path]);
			file_unlink("../../".$flashrec[flash_path]);
			file_unlink("../../".$flashrec[flash_back]);
		}
		$query = "delete from $met_flash where id='$val'";
		$db->query($query);
	}
	metsave($rurl,'',$depth);
}else{
	if($met_deleteimg){
		$flashrec=$db->get_one("SELECT * FROM {$met_flash} where id='{$id}'");
		file_unlink("../../".$flashrec[img_path]);
		file_unlink("../../".$flashrec[flash_path]);
		file_unlink("../../".$flashrec[flash_back]);
	}
	$query = "delete from {$met_flash} where id='{$id}'";
	$db->query($query);
	metsave($rurl,'',$depth);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
