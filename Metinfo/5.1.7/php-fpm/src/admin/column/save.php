<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.  
require_once '../login/login_check.php';
require_once 'global.func.php';
if($action=="editor"){
	if($name=='')metsave('-1',$lang_js11);
	if($if_in==1 and $out_url=='')metsave('-1',$lang_modOuturl);
	if($module==1 &&$isshow==0 && !($met_class2[$id]||$met_class3[$id]))metsave('-1',$lang_columnerr8);
	$filename=preg_replace("/\s/","_",trim($filename)); 
	$filenameold=preg_replace("/\s/","_",trim($filenameold));
	$indeximg =$metadmin[categorymarkimage]?$indeximg:'';
	$columnimg=$metadmin[categoryimage]?$columnimg:'';
	if($if_in==0){
		if($filename!='' && $filename!=$filenameold){
			$filenameok = $db->get_one("SELECT * FROM {$met_column} WHERE filename='{$filename}' and foldername='$foldername' and id!='$id'");
			if($filenameok)metsave('-1',$lang_modFilenameok);
			if(is_numeric($filename) && $filename!=$id && $met_pseudo){
				$filenameok1 = $db->get_one("SELECT * FROM {$met_column} WHERE id='{$filename}' and foldername='$foldername'");
				if($filenameok1)metsave('-1',$lang_jsx30);
			}
		}
		$filedir="../../".$foldername;  
		if(!file_exists($filedir))@mkdir($filedir,0777); 		
		if(!file_exists($filedir))metsave('-1',$lang_modFiledir);
		column_copyconfig($foldername,$module,$id);
		if($met_member_use)require_once 'check.php';
		$query = "update $met_column SET 
				  name               = '$name',
				  namemark           = '$namemark',
				  out_url            = '',
				  keywords           = '$keywords',
				  description        = '$description',
				  no_order           = '$no_order',
				  wap_ok             = '$wap_ok',
				  list_order         = '$list_order',
				  new_windows        = '$new_windows', 
				  bigclass           = '$bigclass',
				  releclass          = '$releclass',
				  nav                = '$nav',
				  ctitle             = '$ctitle',
				  if_in              = '$if_in',
				  filename           = '$filename',
				  foldername         = '$foldername',
				  module             = '$module',
				  index_num          = '$index_num',					  
				  classtype          = '$classtype',					  
				  access      		 = '$access',
				  indeximg			 = '$indeximg',
				  columnimg			 = '$columnimg',
				  lang			     = '$lang',";
	if($module>=2&&$module<=5){
		$query .="content            = '$content',";
	}				  
		$query .="isshow			 =  $isshow
				  where id='$id'"; 
		$db->query($query);
	}elseif($if_in==1){
		$query = "update $met_column SET 
				  name               = '$name',
				  namemark           = '$namemark',
				  out_url            = '$out_url',
				  no_order           = '$no_order',
				  wap_ok             = '$wap_ok',
				  new_windows        = '$new_windows',
				  bigclass           = '$bigclass',
				  releclass          = '$releclass',
				  nav                = '$nav',
				  if_in              = '$if_in',
				  foldername         = '$foldername',
				  module             = '$module',
				  index_num          = '$index_num',					  
				  classtype          = '$classtype',
				  indeximg			 = '$indeximg',
				  lang			     = '$lang',
				  columnimg			 = '$columnimg'
				  where id='$id'"; 
		$db->query($query);
	}
	if($module==9){
		require_once $depth.'../include/config.php';
	}
	file_unlink("../../cache/column_$lang.inc.php");
	metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang);	
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
