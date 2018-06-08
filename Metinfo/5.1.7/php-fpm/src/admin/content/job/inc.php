<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.  
$depth='../';
require_once $depth.'../login/login_check.php';
$backurl="../content/job/inc.php?anyid={$anyid}&lang=$lang";
if($action=="modify"){
	require_once $depth.'../include/config.php';
	$htmjs=onepagehtm('job','cv',1);
	metsave($backurl,'',$depth,$htmjs);
}else{
	$query = "SELECT * FROM $met_parameter where module=6 and lang='$lang' order by no_order";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
		$cv_para[$list[type]][]=$list;
	}
	$met_cv_type1[$met_cv_type]="checked";
	$met_cv_emtype1[$met_cv_emtype]="checked";
	$met_cv_back1=($met_cv_back)?"checked":"";
	$m_list = $db->get_one("SELECT * FROM $met_column WHERE module='6' and lang='$lang'");
	$class1 = $m_list['id'];
	$cs=3;
	$listclass[$cs]='class="now"';
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('content/job/inc');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>