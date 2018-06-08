<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$rurl='../interface/flash/flash.php?anyid='.$anyid.'&lang='.$lang.'&module='.$module;
$path=($met_flash_type==2)?"flash_path":"img_path";
if($$path=='')metsave('-1',$lang_js27,$depth);
if($action=="add"){
	$module=$met_clumid_all==10002?'metinfo':$f_columnlist;
	$query = "INSERT INTO $met_flash SET
		module             = '$module',
		img_title          = '$img_title',
		img_path           = '$img_path',
		img_link           = '$img_link',
		flash_path         = '$flash_path',
		flash_back         = '$flash_back',
		no_order           = '$no_order',
		width			   = '$width',
		height			   = '$height',
		lang               = '$lang'";
	$db->query($query);
	metsave($rurl,'',$depth);
}elseif($action=="editor"){
	$module=$met_clumid_all==10002?'metinfo':$f_columnlist;
	$query = "update {$met_flash} SET
		module             = '$module',
		img_title          = '$img_title',
		img_path           = '$img_path',
		img_link           = '$img_link',
		flash_path         = '$flash_path',
		flash_back         = '$flash_back',
		no_order           = '$no_order',
		width			   = '$width',
		height			   = '$height',
		lang               = '$lang'
		where id='$id'";
	$db->query($query);
	metsave($rurl,'',$depth);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
