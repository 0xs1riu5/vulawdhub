<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
$adminfile=$url_array[count($url_array)-2];
if($action=="delete"){
	if($filename=='update')@chmod('../../update/install.lock',0777);
	function deldirs($dir){
	  $dh=opendir($dir);
	  while ($file=readdir($dh)) {
		if($file!="." && $file!="..") {
		  $fullpath=$dir."/".$file;
		  if(!is_dir($fullpath)) {
			  unlink($fullpath);
		  } else {
			  deldir($fullpath);
		  }
		}
	}

	  closedir($dh);
	  if($dir!='../../upload'){
		if(rmdir($dir)) {
		return true;
		} else {
		return false;
		}
		}
	} 
	$dir='../../'.$filename;
	deldirs($dir);
	metsave('../system/safe.php?anyid='.$anyid.'&lang='.$lang);
}
if($action=="modify"){
	require_once $depth.'../include/config.php';
	if($met_adminfile!=""&&$met_adminfile!=$adminfile){
		$robots=file_get_contents('../../robots.txt');
		$robots=str_replace(": /$adminfile/",": /admin/",$robots);
		file_put_contents('../../robots.txt',$robots);
		Header("Location: ../index.php?lang=".$lang."&action=renameadmin&met_adminfile=".$met_adminfile);
	}else{
		metsave('../system/safe.php?anyid='.$anyid.'&lang='.$lang);
	}
}else{
$localurl="http://";
$localurl.=$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
$localurl_a=explode("/",$localurl);
$localurl_count=count($localurl_a);
$localurl_admin=$localurl_a[$localurl_count-3];
$localurl_admin=$localurl_admin."/system/safe";
$localurl_real=explode($localurl_admin,$localurl);
$localurl=$localurl_real[0];
if(!is_dir('../../install'))$installstyle="display:none;";
if(!is_dir('../../update'))$updatestyle="display:none;";
$met_login_code1[$met_login_code]="checked='checked'";
$met_memberlogin_code1[$met_memberlogin_code]="checked='checked'";
$met_recycle1[$met_recycle]="checked='checked'";
$met_smspass1[$met_smspass]="checked='checked'";
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('system/set_safe');
footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>