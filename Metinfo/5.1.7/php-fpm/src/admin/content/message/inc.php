<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$fnam=$db->get_one("SELECT * FROM $met_column WHERE id='$class1' and lang='$lang'");
if($action=="modify"){
	$columnid=$fnam['id'];
	require_once $depth.'../include/config.php';
	$htmjs = onepagehtm('message','message').'$|$';
	$htmjs.= classhtm($class1,0,0);
	metsave('../content/message/inc.php?lang='.$lang.'&class1='.$class1.'&anyid='.$anyid,'',$depth,$htmjs);
}else{
	foreach($settings_arr as $key=>$val){
		if($val['columnid']==$fnam['id'])$$val['name']=$val['value'];
	}
	$met_fd_ok1[$met_fd_ok]="checked='checked'";
	$met_fd_email1=($met_fd_email)?"checked":"";
	$met_fd_type1=($met_fd_type)?"checked":"";
	$met_fd_back1=($met_fd_back)?"checked":"";
	$m_list = $db->get_one("SELECT * FROM $met_column WHERE module='7' and lang='$lang'");
	$class1 = $m_list['id'];
	$listclass[2]='class="now"';
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('content/message/inc');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>