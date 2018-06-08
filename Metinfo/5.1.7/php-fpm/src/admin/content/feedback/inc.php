<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.  
$depth='../';
require_once $depth.'../login/login_check.php';
$fnam=$db->get_one("SELECT * FROM $met_column WHERE id='$class1' and lang='$lang'");
if($action=="modify"){	
	$columnid=$fnam['id'];
	require_once $depth.'../include/config.php';
	$htmjs = onepagehtm($foldename,'index',1,$htmpack,$fnam['filename'],$class1);
	metsave('../content/feedback/inc.php?lang='.$lang.'&class1='.$class1.'&anyid='.$anyid,'',$depth,$htmjs );
}else{
	foreach($settings_arr as $key=>$val){
		if($val['columnid']==$fnam['id'])$$val['name']=$val['value'];
	}
	$query = "SELECT * FROM $met_parameter where module=8 and lang='$lang' and class1='$class1' order by no_order";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
	$fd_para[$list[type]][]=$list;
	if($list[type]==2||$list[type]==6)$fd_paraall[]=$list;
	}
	$cs=isset($cs)?$cs:1;
	$listclass[$cs]='class="now"';
	$met_fd_ok1[$met_fd_ok]="checked='checked'";
	$met_fd_type1[$met_fd_type]="checked='checked'";
	$met_fd_back1=($met_fd_back)?"checked='checked'":"";
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('content/feedback/fd_inc');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>