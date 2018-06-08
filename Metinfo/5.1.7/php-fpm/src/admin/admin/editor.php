<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../login/login_check.php';
$admin_listed = $db->get_one("SELECT * FROM $met_admin_table WHERE id='$id'");
if(!$admin_listed)metsave('-1',$lang_dataerror);
if($admin_list[langok]!='metinfo'){
	foreach($met_langok as $key=>$val){
		$langoka=explode('-',$admin_list[langok]);
		for($i=0;$i<count($langoka);$i++){
			if($langoka[$i]==$val[mark])$met_langoka[]=$val;
		}
	}
}else{
	$met_langoka=$met_langok;
}
if($admin_listed[langok]=="metinfo"){
	$langok1="checked='checked'";
	foreach($met_langok as $key=>$val){
		$langok2[$val[mark]]="checked='checked'";
	}
}else{
	$langokb=explode('-',$admin_listed[langok]);
	foreach($langokb as $key=>$val){
		$langok2[$val]="checked='checked'";
	}
}
if($admin_listed[admin_group]==0){
	if($admin_listed[admin_issueok]==1)$admin_issue_ok="checked='checked'";
	$admin_op=explode('-',$admin_listed['admin_op']);
	if($admin_op[0]=="metinfo"||$admin_listed[admin_op]=="metinfo"){
		$admin_op_0="checked='checked'";
		$admin_op_1="checked='checked'";
		$admin_op_2="checked='checked'";
		$admin_op_3="checked='checked'";
	}else{
		if($admin_op[1]=="add")$admin_op_1="checked='checked'";
		if($admin_op[2]=="editor")$admin_op_2="checked='checked'";
		if($admin_op[3]=="del")$admin_op_3="checked='checked'";
	}
}
$sexx[$admin_listed[admin_sex]]="checked='checked'";
$admin_groupx[$admin_listed[admin_group]]="checked='checked'";
$metmanager=1;
require_once '../include/metlist.php';
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('admin/admin_editor');
footer();
# 本程序是一个开源系统,使用时请你仔细阅读使用协议,商业用途请自觉购买商业授权.
# Copyright (C) 长沙米拓信息技术有限公司 (http://www.metinfo.cn). All rights reserved.
?>