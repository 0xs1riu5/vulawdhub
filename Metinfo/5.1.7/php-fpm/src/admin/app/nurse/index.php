<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$cs=isset($cs)?$cs:2;
$listclass[$cs]='class="now"';
if($action=='modify'){
	switch($cs){
		case 2:
			$met_nurse_stat_tel = str_replace(chr(13).chr(10),",",$met_nurse_stat_tel);
			$type               = 2;
			$met_nurse_tel      = $met_nurse_stat_tel;
			$met_nurse_ok       = $met_nurse_stat;
		break;
		case 3:
			$met_nurse_monitor_tel = str_replace(chr(13).chr(10),",",$met_nurse_monitor_tel);
			$type = 3;
			$met_nurse_ok = $met_nurse_monitor;
			$met_nurse_tel= $met_nurse_monitor_tel;
			$noun=$met_nurse_monitor_fre;
		break;
		case 4:
			$met_nurse_member_tel = str_replace(chr(13).chr(10),",",$met_nurse_member_tel);
			$met_nurse_feed_tel   = str_replace(chr(13).chr(10),",",$met_nurse_feed_tel);
			$met_nurse_massge_tel = str_replace(chr(13).chr(10),",",$met_nurse_massge_tel);
			$met_nurse_job_tel    = str_replace(chr(13).chr(10),",",$met_nurse_job_tel);
			$met_nurse_link_tel   = str_replace(chr(13).chr(10),",",$met_nurse_link_tel);
		break;
	}
	if($cs==2||$cs==3){
		require_once ROOTPATH.'include/export.func.php';
		$total_passok = $db->get_one("SELECT * FROM $met_otherinfo WHERE lang='met_sms'");
		$met_file='/timing/record.php';
		$post=array(
			'total_pass'=>$total_passok['authpass'],
			'met_nurse_ok'=>$met_nurse_ok,
			'met_nurse_tel'=>$met_nurse_tel,
			'met_weburl'=>$met_weburl,
			'noun'=>$noun,
			'weeka'=>$met_nurse_monitor_weeka,
			'weekb'=>$met_nurse_monitor_weekb,
			'timea'=>$met_nurse_monitor_timea,
			'timeb'=>$met_nurse_monitor_timeb,
			'sendtime'=>$met_nurse_sendtime,
			'type'=>$type);
		$metinfo = curl_post($post,30);
		if(trim($metinfo)=='OK'){
			require_once $depth.'../include/config.php';
			metsave('../app/nurse/index.php?lang='.$lang.'&anyid='.$anyid.'&cs='.$cs,'',$depth);
		}else{
			require_once $depth.'../include/config.php';
			metsave('-1',$lang_nursenomoney,$depth);
		}
	}else{
		require_once $depth.'../include/config.php';
		metsave('../app/nurse/index.php?lang='.$lang.'&anyid='.$anyid.'&cs='.$cs,'',$depth);
	}
}else{
	switch($cs){
		case 2:
			$met_nurse_statx[$met_nurse_stat]='checked';
			$met_nurse_stat_tel = str_replace(",",chr(13).chr(10),$met_nurse_stat_tel);
			$met_nurse_statfreax[$met_nurse_statfrea]='checked';
			$met_nurse_statfrebx[$met_nurse_statfreb]='checked';
			$met_nurse_statfrecx[$met_nurse_statfrec]='checked';
		break;
		case 3:
			$met_nurse_monitorx[$met_nurse_monitor]='checked';
			$met_nurse_monitor_frex[$met_nurse_monitor_fre]='checked';
			$met_nurse_monitor_peax[$met_nurse_monitor_pea]='checked';
			$met_nurse_monitor_pebx[$met_nurse_monitor_peb]='checked';
			$met_nurse_monitor_pecx[$met_nurse_monitor_pec]='checked';
			$met_nurse_monitor_pedx[$met_nurse_monitor_ped]='checked';
			$met_nurse_monitor_tel = str_replace(",",chr(13).chr(10),$met_nurse_monitor_tel);
			$weeka[$met_nurse_monitor_weeka]='selected';
			$weekb[$met_nurse_monitor_weekb]='selected';
			switch($met_nurse_monitor_fre){
				case 1:
				$monitor[3]='none';
				$monitor[4]='none';
				break;
				case 2:
				$monitor[3]='none';
				$monitor[4]='none';
				break;
				case 3:
				$monitor[1]='none';
				$monitor[4]='none';
				break;
				case 4:
				$monitor[1]='none';
				$monitor[3]='none';
				break;
			}
		break;
		case 4:
			$met_nurse_memberx[$met_nurse_member]='checked';
			$met_nurse_feedx[$met_nurse_feed]='checked';
			$met_nurse_massgex[$met_nurse_massge]='checked';
			$met_nurse_jobx[$met_nurse_job]='checked';
			$met_nurse_linkx[$met_nurse_link]='checked';
			$met_nurse_member_tel = str_replace(",",chr(13).chr(10),$met_nurse_member_tel);
			$met_nurse_feed_tel   = str_replace(",",chr(13).chr(10),$met_nurse_feed_tel);
			$met_nurse_massge_tel = str_replace(",",chr(13).chr(10),$met_nurse_massge_tel);
			$met_nurse_job_tel    = str_replace(",",chr(13).chr(10),$met_nurse_job_tel);
			$met_nurse_link_tel   = str_replace(",",chr(13).chr(10),$met_nurse_link_tel);
		break;
	}

	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('app/nurse/index');footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>