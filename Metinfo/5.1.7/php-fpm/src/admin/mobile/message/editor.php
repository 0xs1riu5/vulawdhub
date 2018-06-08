<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$navoks[1]='ui-btn-active';
$backurl="../mobile/message/index.php?lang={$lang}";
if($action=="editor"){
	$query = "update $met_message SET
						  info               = '$info',
						  useinfo            = '$useinfo',
						  readok             = '$readok',
						  access			 = '$access'
						  where id='$id'";
	$db->query($query);
	$htmjs = classhtm('message',0,0);
	metsave($backurl,'',$depth,$htmjs);
}else{
	$message_list=$db->get_one("select * from $met_message where id='$id'");
	$message_list['customerid']=metidtype($message_list['customerid']);
	if(!$message_list)metsave('-1',$lang_dataerror,$depth);
	$lev=$met_module[7][0][access];
	$list_access['access']=$message_list['access'];
	require '../../content/access.php';
	$met_readok=($message_list[readok])?"checked='checked'":"";
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('mobile/message/editor');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>