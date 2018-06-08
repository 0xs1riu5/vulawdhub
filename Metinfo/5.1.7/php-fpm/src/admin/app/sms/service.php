<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=='modify'){
	if($new_codeto!=$new_code)metsave('-1',$lang_js6,$depth);
	require_once ROOTPATH.'include/export.func.php';
	$total_passok = $db->get_one("SELECT * FROM $met_otherinfo WHERE lang='met_sms'");
	$met_file='/sms/service/passchange.php';
	$post=array(
		'total_pass'=>$total_passok['authpass'],
		'old_code'=>$old_code,
		'new_code'=>$new_code);
	$metinfo = curl_post($post,30);
	if(trim($metinfo)=='OK'){
		metsave("../app/sms/sms.php?lang=$lang&anyid=$anyid",'',$depth);
	}else{
		metsave('-1',$lang_sms2,$depth);
	}
}else{
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('app/sms/service');footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>