<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$filename=preg_replace("/\s/","_",trim($filename)); 
if($filename_okno){
	$metinfo=1;
	$filename=str_replace("\\",'',$filename);
	$filename=unescape($filename);
	if($filename!=''){
		$id=$class1;
		$foldername=$met_class[$id]['foldername'];
		$filenameok = $db->get_one("SELECT * FROM $met_column WHERE filename='$filename' and foldername='$foldername' and id!=$id");
		if($filenameok)$metinfo=0;
		if(is_numeric($filename) && $filename!=$id && $met_pseudo){
			$filenameok1 = $db->get_one("SELECT * FROM $met_column WHERE id='{$filename}' and foldername='$foldername'");
			if($filenameok1)$metinfo=2;
		}
	}
	echo $metinfo;
	die;
}  
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
