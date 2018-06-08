<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once ROOTPATH.'include/export.func.php';
if($action=='code'){
	$met_file='/sms/code.php';
	$post=array('total_pass'=>$total_pass,'total_email'=>$total_email,'total_weburl'=>$total_weburl,'total_code'=>$total_code);
	$re = curl_post($post,30);
	if($re=='error_no'){
		$lang_re=$lang_smstips79;
	}elseif($re=='error_use'){
		$lang_re=$lang_smstips80;
	}elseif($re=='error_time'){
		$lang_re=$lang_smstips81;
	}else{
		$lang_re=$lang_smstips82;
	}
	metsave("../app/sms/index.php?lang=$lang&anyid=$anyid&cs=$cs",$lang_re,$depth);
}
$firstcharge=1;
$total_passok = $db->get_one("SELECT * FROM $met_otherinfo WHERE lang='met_sms'");
$met_file='/sms/remain.php';
$post=array('total_pass'=>$total_passok['authpass'],'metcms_v'=>$metcms_v);
$balance = curl_post($post,30);
if($total_passok['authpass']!=''){
	$total_pass=$total_passok['authpass'];
	if($balance=='Error'){
		$post=array('total_pass'=>'');
		$balance = curl_post($post,30);
		$total_pass = $balance;
		$balance = 0;
	}elseif($balance=='no_user'){
		$balance = '0.00';
	}
	elseif(!is_numeric($balance)){
		$balance = '';
	}else{
		$firstcharge=0;
	}
}else{
	$query = "delete from $met_otherinfo where lang='met_sms'";
	$db->query($query);
	$query = "INSERT INTO $met_otherinfo SET 
						  authpass = '$balance',
						  lang     = 'met_sms'";				  
	$db->query($query);
	$total_pass=$balance;
	$balance=0;
}
if(!function_exists('fsockopen')&&!function_exists('pfsockopen')&&!get_extension_funcs('curl')){
	$disable="disabled=disabled";
	$fstr.=$lang_smstips77;
}
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('app/sms/index');footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>