<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../common.inc.php';
require_once ROOTPATH.'include/export.func.php';
$total_passok = $db->get_one("SELECT * FROM $met_otherinfo WHERE lang='met_sms' and authpass='$site_md5'");
if($total_passok && $met_nurse_stat){
	$domain = strdomain($met_weburl);
	$ztime=strtotime(date("Y-m-d",strtotime("-1 day")));
	$phone = implode(',',array_unique(array_filter(explode(',',$site_tel))));//去除重复|空值
	$summarylist = $db->get_all("SELECT * FROM $met_visit_summary ");
	foreach($summarylist as $key=>$val){
		if($val['stattime']==$ztime)$visit_summary=$val;
	}
	$ztimes = date("m月d日",$ztime);
	$per_visit=sprintf("%.2f",($visit_summary['pv']/$visit_summary['alone']));
	$message="您网站[{$domain}]昨日[{$ztimes}]被访问{$visit_summary[pv]}次[独访客{$visit_summary[alone]}][IP{$visit_summary[ip]}][人均浏览次数{$per_visit}]";
	$sms = sendsms($phone,$message,2);
	echo $sms;
	die;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>