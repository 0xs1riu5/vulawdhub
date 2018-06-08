<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once ROOTPATH.'include/export.func.php';
if($action=='modify'){
	if(!$message || !$phone){
		echo $lang_sms1;
		die();
	}

	$price=smspreice();
	if($price['re']=='nohost'){
		echo $lang_hosterror;
		die();
	}
	if($price[price]!=$met_smsprice){
		$re.='当前短信发送价格和服务器设定价格不一致，点击<a href=\'\'>此处</a>刷新页面，重新获取发送价格';
		if($price['re']!='SUC'){
			$re.='错误原因：'.powererr($price['re']);
		}
		$query="update $met_config set value='$price[price]' where name='met_smsprice'";
		$db->query($query);
		echo $re;
		die();
	}
	
	$phone = implode(',',array_unique(array_filter(explode(',',str_replace("\n",",",$phone)))));/*去除重复|空值*/
	$sms = sendsms($phone,$message,1);
	echo $sms;
	die;
}elseif($action=='membertel'){
	$query = "SELECT admin_mobile FROM $met_admin_table where usertype<3 && checkid=1";
	$result = $db->query($query);
	$member_list='';
	while($list= $db->fetch_array($result)){
		if($list['admin_mobile']!='' && eregi("^1[0-9]{9}",$list['admin_mobile']) && strlen($list['admin_mobile'])==11){
			$member_list.=$list['admin_mobile'].'|';
		}
	}
	echo substr($member_list, 0, -1);
}elseif($action=='balance'){
	$re='';
	$balance=smsremain();
	if($balance['re']=='nohost'){
		echo $lang_hosterror;
		die();
	}
	echo number_format($balance[balance],2);
}else{
	$met_smsprice=number_format($met_smsprice,2);
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('app/sms/sms');footer();
}
die;
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>