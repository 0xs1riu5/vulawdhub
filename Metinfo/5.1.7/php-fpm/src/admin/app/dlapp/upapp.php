<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once ROOTPATH.'include/export.func.php';
$met_file='/dl/app_inc.php';
$authinfo=$db->get_one("SELECT * FROM $met_otherinfo where id=1");
$post_data = array('met_code'=>$authinfo['authcode'],'met_key'=>$authinfo['authpass'],'checksum'=>'info');
$info=curl_post($post_data,30);
$post_data = array('checksum'=>'ver');
$sysver=curl_post($post_data,30);
if($info!='nohost'){
$query = "update $met_config SET value = '$sysver' where name ='met_app_sysver'";
$db->query($query);
$query = "update $met_config SET value = '$info' where name ='met_app_info'";
$db->query($query);
	$info=explode('|',$info);
	$info[0]=ltrim($info[0],'metinfo');
	$query="select * from $met_app where download=1";
	$apptemp=$db->get_all($query);
	foreach($apptemp as $keyapptemp=>$valapptemp){
		$app[$valapptemp['no']]=$valapptemp;
	}
	$query="select * from $met_app where download=0";
	$apptemp=$db->get_all($query);
	foreach($apptemp as $keyapptemp=>$valapptemp){
		$str_apps[$valapptemp['no']][0]=$valapptemp['name'];
		$str_apps[$valapptemp['no']][1]=$valapptemp['no'];
		$str_apps[$valapptemp['no']][2]=$valapptemp['ver'];
		$str_apps[$valapptemp['no']][3]=$valapptemp['img'];
		$str_apps[$valapptemp['no']][4]=$valapptemp['info'];
		$str_apps[$valapptemp['no']][5]=$valapptemp['file'];
		$str_apps[$valapptemp['no']][6]=$valapptemp['power'];
		$str_apps[$valapptemp['no']][7]=$valapptemp['sys'];
		$str_apps[$valapptemp['no']][8]=$valapptemp['addtime'];
		$str_apps[$valapptemp['no']][9]=$valapptemp['updatetime'];
	}
	$appaddok=$db->get_one("SELECT * FROM $met_app where name!=''");
	if($met_apptime!=$info[1]||!$appaddok){
		$checksum='metinfo';
		$result=dlfile('app/applist.inc.php','');
		$str_temp=explode('|',$result);
		foreach($str_temp as $strkey=>$strval){
			$str_app[]=explode(',',$strval);
		}
		foreach($str_app as $appkey=>$appval){
			if(is_array($str_apps[$appval[1]])){
				if($appval[7]==0){
					$query="delete from $met_app where no='$appval[1]' and download=0";
					$db->query($query);
					unset($str_apps[$appval[1]]);
				}
				else{
					$query="update $met_app set name='$appval[0]',ver='$appval[2]',img='$appval[3]',info='$appval[4]',file='$appval[5]',power='$appval[6]',sys='$appval[7]',site='$appval[8]',url='$appval[9]',addtime='$appval[10]',updatetime='$appval[11]' where no='$appval[1]' and download=0";
					$db->query($query);
					$str_apps[$appval[1]]=$appval;
				}
			}
			else{
				$query="insert into $met_app set name='$appval[0]',no='$appval[1]',ver='$appval[2]',img='$appval[3]',info='$appval[4]',file='$appval[5]',power='$appval[6]',sys='$appval[7]',site='$appval[8]',url='$appval[9]',addtime='$appval[10]',updatetime='$appval[11]',download='0'";
				$db->query($query);
				$str_apps[$appval[1]]=$appval;
			}
		}
		$checksum='img';
		foreach($str_apps as $appskey=>$appsval){
			dlfile($appsval[3],"../dlapp/img/$appsval[3]");
		}
		$appaddokx=$db->get_one("SELECT * FROM $met_app where name!=''");
		if($appaddokx){
			$query="update $met_config set value='$info[1]' where name='met_apptime'";
			$db->query($query);
		}
	}
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>