<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
$depth='../';
require_once $depth.'../login/login_check.php';
if(!isset($checkid)) $checkid=0;
if($action=="editor"){
if(isset($checkid) && $checkid==1) $approval_date=$m_now_date;
else $approval_date='';
$query = "update $met_admin_table SET
                      admin_id           = '$useid',
					  admin_name         = '$name',
					  admin_sex          = '$sex',
					  admin_tel          = '$tel',
					  admin_mobile       = '$mobile',
					  admin_email        = '$email',
					  admin_qq           = '$qq',
					  admin_msn          = '$msn',
					  admin_taobao       = '$taobao',
					  admin_introduction = '$admin_introduction',
					  admin_approval_date= '$approval_date',
					  usertype			 = '$usertype',
					  companyname		 = '$companyname',
					  companyaddress     = '$companyaddress',
					  companyfax	     = '$companyfax',
					  companycode	     = '$companycode',
					  companywebsite     = '$companywebsite',
					  checkid            = '$checkid'";

if($pass1){
$pass1=md5($pass1);
$query .=", admin_pass         = '$pass1'";
}
$query .="  where id='$id'";
$db->query($query);
metsave('../mobile/member/index.php?lang='.$lang.'&anyid='.$anyid,'',$depth);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
