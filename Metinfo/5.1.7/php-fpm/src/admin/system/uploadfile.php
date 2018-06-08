<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../login/login_check.php';
require_once 'mydir.class.php';
$rurls='../system/uploadfile.php?anyid='.$anyid.'&cs='.$cs.'&lang='.$lang;
if($action=='deletefolder'){
   $filedir="../../".$filename;
   deldir($filedir,0);
   metsave($rurls);
}
if($action=='delete'){
	$rurls.='&fileurl='.$fileurl.'&file_classnow='.$file_classnow.'&page='.$page;
	if($action_type=="del"){
		$allidlist=explode(',',$allid);
		$k=count($allidlist)-1;
		for($i=0;$i<$k; $i++){
			if(strcasecmp(substr(trim($allidlist[$i]),0,13),'../../upload/')!=0)die('met1');
			if(substr_count(trim($allidlist[$i]),'../')!=2)die('met2');
			if(file_exists($allidlist[$i]))@unlink($allidlist[$i]);
		}
		metsave($rurls);
	}else{
		if(strcasecmp(substr(trim($filename),0,13),'../../upload/')!=0)die('met1');
		if(substr_count(trim($filename),'../')!=2)die('met2');
		if(file_exists($filename)){
			@unlink($filename);
			metsave($rurls);
		}else{
			metsave($rurls,$lang_setfilenourl);
		}
	}
}else{
	function getDir($dir){
		$fileArr = array();
		$dp = opendir($dir);
		while(($file = readdir($dp)) !== false) {
			if($file !="." AND $file !=".." AND $file !="") {   
				if(is_dir($dir."/".$file)) {   
					$fileArr = array_merge($fileArr, getDir($dir."/".$file));   
					$fileArr[] = $dir."/".$file; 
				} 
			}   
		}   
		closedir($dp);   
		return $fileArr;   
	}
	$fileurl2=$fileurl;
	$metnowdir="upload";
	$metdirfile=getDir('../../'.$metnowdir);
	$i=0;
	foreach($metdirfile as $val){
		$fileclassarray=explode('/',$val);
		$fileclassnum=count($fileclassarray)-3;
		$fileclassnum1=count($fileclassarray)-1;
		$fileclass[$fileclassnum][$i][name]=$fileclassarray[$fileclassnum1];
		$fileclass[$fileclassnum][$i][url]=$val;
		$i++;
	}
	if($fileurl<>"")$metnowdir=$fileurl;
	if($file_classnow==3){
		$fileurl1=explode('/',$fileurl);
		$fileurl=$fileurl1[0].'/'.$fileurl1[1];
	}
	if(strcasecmp(substr(trim($metnowdir),0,6),'upload')!=0)die('met2');
	$metdir = new myDIR; 
	$metdir->setMASK("*.gif,*.txt,*.jpg*,*.rar*,*.jpeg*,*.doc*,*.pdf*,*.bmp*,*.png*,*.tif*,*.psd*,*.swf*,*.swf*");
	$metdir->setFIND("files"); 
	$metdir->setROOT('../../'.$metnowdir); 
	$metfile = $metdir->getRESULT(); 
	$total_count = count($metfile);
	foreach($metfile as $key=>$val){
		if(!preg_match('/((\.gif)|(\.txt)|(\.jpg)|(\.rar)|(\.jpeg)|(\.doc)|(\.pdf)|(\.bmp)|(\.png)|(\.tif)|(\.psd)|(\.swf))$/',$val['file'])){
			unset($metfile[$key]);
		}
	}
	require_once 'include/pager.class.php';
	$page = (int)$page;
	if($page_input){$page=$page_input;}
	$list_num = 11;
	$rowset = new Pager($total_count,$list_num,$page);
	$page=$page?$page:1;
	$startnum=($page-1)*$list_num;
	$endnum=$page*$list_num;
	$page_list = $rowset->link("uploadfile.php?anyid={$anyid}&lang=$lang&fileurl=$fileurl2&file_classnow=$file_classnow&page=");
	$cs=isset($cs)?$cs:1;
	$listclass[$cs]='class="now"';
	$css_url="../templates/".$met_skin."/css";
	$img_url="../templates/".$met_skin."/images";
	include template('system/uploadfile');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>