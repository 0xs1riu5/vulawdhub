<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
	
	$htmname=($list[filename]<>"" and $metadmin[pagename])?$filename."/".$list[filename]:$filename."/".$filenamenow.$list[id];
	$phpname=$filename."/show".$pagename.".php?id=".$list[id];	
	$pseudoname=($list[filename]<>"" and $metadmin[pagename])?$filename."/".$list[filename]:$filename."/".$list[id];
	$list[url]=$met_pseudo?$pseudoname.'-'.$lang.$met_htmtype:($met_webhtm?$htmname.$met_htmtype:$phpname."&lang=".$lang);
if($met_member_use==2){
 if(intval($metinfo_member_type)>=intval($nowaccess))$search_list[]=$list;
}else{
$search_list[]=$list;	
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>