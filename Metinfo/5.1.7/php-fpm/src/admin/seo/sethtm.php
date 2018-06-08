<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($action=='modify'){
	if($cs==1){
		$query = "update $met_lang SET met_webhtm = '$met_webhtm' where lang='$lang'";
		$db->query($query);
		$query = "update $met_lang SET met_htmtype = '$met_htmtype' where lang='$lang'";
		$db->query($query);
		require_once $depth.'../include/config.php';
		require_once '404.php';
		if(($met_webhtm==0 && $dehtm=='deleteall') || $dehtm=='bianhtm'){
			$query = "SELECT * FROM $met_column where (bigclass=0 or releclass!=0) and if_in=0 and lang='$lang'";
			$result = $db->query($query);
			while($list= $db->fetch_array($result)){
				$dir='../../'.$list['foldername']; 
				$file=met_scandir($dir);
				foreach ($file as $value){
					if($lang==$met_index_type){
						if($value != "." && $value !=".."){
							$langmarkarray=explode("_",$value);
							$k=count($langmarkarray)-1;
							$langmark=$k?$langmarkarray[$k]:"";
							if((substr($value,-4,4)=="html" || substr($value,-3,3)=="htm") and (!strstr($htmlang, "_".$langmark) || $langmark=="")){
								unlink($dir."/".$value); 
							}
						} 
					}else{
						if($value != "." && $value !=".."){
							if(strstr($value,".htm")){
								unlink($dir."/".$value);
							}	
						} 
					}
				}
			}
		}
		if($met_webhtm==0 || $dehtm=='bianhtm'){
			if($lang==$met_index_type && file_exists("../../index.htm"))@unlink("../../index.htm");
			if($lang==$met_index_type && file_exists("../../index.html"))@unlink("../../index.html");
			if(file_exists("../../index_".$lang.".htm"))@unlink("../../index_".$lang.".htm");
			if(file_exists("../../index_".$lang.".html"))@unlink("../../index_".$lang.".html");
		}
	}elseif($cs==3){
		require_once $depth.'../include/config.php';
		require_once 'pseudo.php';
	}
	if($dehtm=='newhtm' || $dehtm=='bianhtm'){
		metsave('../seo/htm.php?lang='.$lang.'&anyid='.$anyid.'&cs=2&newhtm_open=1');
	}else{
		metsave('../seo/sethtm.php?lang='.$lang.'&anyid='.$anyid.'&cs='.$cs);
	}
}else{
	if($met_webhtm && !$cs)header('location:htm.php?lang='.$lang.'&anyid='.$anyid);
	$cs=isset($cs)?$cs:1;
	$listclass[$cs]='class="now"';
	if($cs==1){
		$met_webhtm1[$met_webhtm]='checked';
		if($met_htmtype=='htm')$met_htmtype1[0]='checked';
		if($met_htmtype=='html')$met_htmtype1[1]='checked';
		$met_htmpagename1[$met_htmpagename]='checked';
		$met_listhtmltype1[$met_listhtmltype]='checked';
		$met_htmlistname1[$met_htmlistname]='checked';
		$met_htmway1[$met_htmway]='checked';
	}elseif($cs==3){
		$met_pseudo1[$met_pseudo]='checked';
	}
	$css_url="../templates/".$met_skin."/css";
	$img_url="../templates/".$met_skin."/images";
	include template('seo/sethtm');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>