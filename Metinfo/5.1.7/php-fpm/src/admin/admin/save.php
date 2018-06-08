<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
define('ADMIN_POWER','metinfo');
require_once '../login/login_check.php';
$admin_ok = 1;
$admin_issueok=0;
if($admin_issue=="yes")$admin_issueok=1;
$admin_op=$admin_op0."-".$admin_op1."-".$admin_op2."-".$admin_op3;
if($langok<>'metinfo'){
	foreach($met_langok as $key=>$val){
	$langvalue="langok_".$val[mark];
	if($$langvalue<>"")$langok.="-".$$langvalue;
	}
	$langok.="-";
}
if($langok=="-" or $langok=="")$langok='metinfo';
if($admin_pop=="yes"){
	$admin_type="metinfo";
}else{
	foreach($metinfocolumn as $key=>$val){
		foreach($sidebarcolumn as $key=>$val2){
			if($val2[bigclass]==$val[id] && $val2[field]){
				$admin_pop="admin_pop".$val2[field];
				if($$admin_pop!="")$admin_type.=$$admin_pop."-";
			}
		}
	}
	foreach($met_langok as $key=>$val4){
		foreach($column_pop[$val4[lang]] as $key=>$val){
			if($val['module']<9 && !$val['if_in']){
				$admin_pop="admin_pop".$val[id];
				if($$admin_pop!="")$admin_type.=$$admin_pop."-";
			}
		}
	}
	if($admin_pop9999)$admin_type.=$admin_pop9999.'-';
}
if($action=="add"){
	if(($admincp_ok['admin_group']=='10000'||($admincp_ok['admin_group']=='3'&&$admincp_ok['admin_group']>$admin_group))&&$admin_group!='10000'){
		$admin_if=$db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$useid'");
		if($admin_if)metsave('-1',$lang_loginUserMudb1);
		$pass1=md5($pass1);
		$query = "INSERT INTO $met_admin_table SET
			admin_id           = '$useid',
			admin_pass         = '$pass1',
			admin_name         = '$name',
			admin_sex          = '$sex',
			admin_tel          = '$tel',
			admin_mobile       = '$mobile',
			admin_email        = '$email',
			admin_qq           = '$qq',
			admin_msn          = '$msn',
			admin_taobao       = '$taobao',
			admin_introduction = '$admin_introduction',
			admin_type         = '$admin_type',
			admin_register_date= '$m_now_date',
			admin_approval_date= '$m_now_date',
			admin_issueok      = '$admin_issueok',
			admin_group        = '$admin_group',
			admin_op           = '$admin_op',
			usertype           = '3',
			admin_ok           = '$admin_ok',
			langok             = '$langok'";
		$db->query($query);
	}
	metsave('../admin/index.php?lang='.$lang.'&anyid='.$anyid);
}

if($action=="editor"){
	$query = "select * from $met_admin_table where id='$id'";
	$modify = $db->get_one($query);
	if(($admincp_ok['admin_group']=='10000')||($admincp_ok['admin_group']=='3'&&$admincp_ok['admin_group']>$modify['admin_group'])||$modify['id']==$admincp_ok['id']){
		$query = "update $met_admin_table SET admin_ok = '$admin_ok'";
		if($edtp!=1){
		$query.= ", admin_name   = '$name',
			admin_sex          = '$sex',
			admin_tel          = '$tel',
			admin_mobile       = '$mobile',
			admin_email        = '$email',
			admin_qq           = '$qq',
			admin_msn          = '$msn',
			admin_taobao       = '$taobao',
			admin_introduction = '$admin_introduction'";
		}
		if(($admincp_ok['admin_group']=='10000'&&$admincp_ok['id']!=$modify['id'])||($admincp_ok['admin_group']=='3'&&$admincp_ok['admin_group']>$modify['admin_group'])||$modify['id']==$admincp_ok['id']){
			if($editorpass!=1 && $edtp==1 && $modify['id']!=$admincp_ok['id']){
				$query .=", langok         = '$langok'";
				$query .=", admin_type     = '$admin_type'";
				$query .=", admin_issueok  = '$admin_issueok'";
				$query .=", admin_group    = '$admin_group'";
				$query .=", admin_op       = '$admin_op'";
			}
			if($pass1){
				$pass1  =md5($pass1);
				$query .=", admin_pass         = '$pass1'";
			}
		}
		$query .="  where id='$id'";
		if($nosql)$db->query($query);
	}
	if($editorpass!=1){
		metsave('../admin/index.php?lang='.$lang.'&anyid='.$anyid);
	}else{
		metsave('../admin/editor_pass.php?lang='.$lang.'&id='.$id.'&anyid='.$anyid);
	}
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>