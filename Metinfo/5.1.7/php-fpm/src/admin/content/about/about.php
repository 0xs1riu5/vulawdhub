<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=="modify"){ 
	$filename=preg_replace("/\s/","_",trim($filename)); 
	$filenameold=preg_replace("/\s/","_",trim($filenameold)); 
	if($filename!='' && $filename != $filenameold){
		$foldername=$met_class[$id]['foldername'];
		$filenameok = $db->get_one("SELECT * FROM $met_column WHERE filename='$filename' and foldername='$foldername' and id!=$id");
		if($filenameok)metsave('-1',$lang_modFilenameok,$depth);
	}
	$query = "update $met_column SET 
						  content     = '$content',
						  keywords    = '$keywords',
						  filename    = '$filename',
						  ctitle      = '$ctitle',
						  description = '$description'
						  where id='$id'";
	$db->query($query);
	$html = showhtm($id);
	if($filenameold<>$filename and $metadmin[pagename])deletepage($met_class[$id][foldername],$id,'about',$updatetimeold,$filenameold);
	metsave('../content/about/about.php?anyid='.$anyid.'&lang='.$lang.'&id='.$id,'',$depth,$html);
}else{
	$about = $db->get_one("SELECT * FROM $met_column WHERE id='$id'");
	if(!$about)metsave('-1',$lang_dataerror,$depth);
	$class1=$about[bigclass]==0?$id:$about[bigclass];
	$class2=$id;
	if($about[classtype]==3||$about[classtype]==2)$ctp=1;
	$ctype = $about[classtype]==2&&count($met_class3[$id])?1:0;
	$ctype1 = $about[classtype]==2&&count($met_class3[$id])?1:0;
	if($about[classtype]==3){
		$about2 = $db->get_one("SELECT * FROM $met_column WHERE id='$about[bigclass]'");
		$class2=$about2[id];
		$class1=$about2[bigclass];
		$ctype1=1;
	}
	$nott=$class1==$class2||$class2==$id?0:1;
	if($met_class[$class2][releclass]){
		$class1=$class2;
	}
	$about['ctitle']=str_replace('"', '&#34;', str_replace("'", '&#39;',$about['ctitle']));
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('content/about/about');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>