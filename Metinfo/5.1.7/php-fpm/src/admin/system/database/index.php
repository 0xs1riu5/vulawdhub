<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once 'global.func.php';
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
$labtype=isset($labtype)?($labtype==''?1:$labtype):1;
$labtypeclass[$labtype]='class="now"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
$rurls='../system/database/filedown.php?anyid='.$anyid.'&lang='.$lang.'&cs=6';
if($action=='allfile'){
	$localurl="http://";
	$localurl.=$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
	$localurl_a=explode("/",$localurl);
	$localurl_count=count($localurl_a);
	$localurl_admin=$localurl_a[$localurl_count-4];
	$localurl_admin=$localurl_admin."/system/database/index";
	$localurl_real=explode($localurl_admin,$localurl);
	$localurl=$localurl_real[0];
	$fileid = isset($fileid)?$fileid:1;
	$tables=tableprearray($tablepre);
	$sizelimit=2048;
	if($fileid==1){
		$random = mt_rand(1000,9999);
		cache_write('bakup_tables.php', $tables);
	}
	$sqldump = '';
	$tableid = isset($tableid) ? $tableid - 1 : 0;
	$startfrom = isset($startfrom) ? intval($startfrom) : 0;
	$tablenumber = count($tables);
	for($i = $tableid; $i < $tablenumber && strlen($sqldump) < $sizelimit * 1000; $i++){
		$sqldump .= sql_dumptable($tables[$i], $startfrom, strlen($sqldump));
		$startfrom = 0;
	}
	include "../../include/pclzip.lib.php";
	if(trim($sqldump)){
		$version='version:'.$metcms_v;
		$sqldump = "#MetInfo.cn Created {$version} \n#$localurl\n#$tablepre\n# --------------------------------------------------------\n\n\n".$sqldump;
		$tableid = $i;
		$filename = $con_db_name.'_'.date('Ymd').'_'.$random.'_'.$fileid.'.sql';
		$zipname  = $con_db_name.'_'.date('Ymd').'_'.$random.'_'.$fileid;
		$fileid++;
		$bakfile = '../../databack/'.$filename;
		if(!is_writable('../../databack/'))metsave('-1',$lang_setdbTip2.'databack/'.$lang_setdbTip3,$depth);  
		file_put_contents($bakfile, $sqldump);
		if(!file_exists('../../databack/sql'))@mkdir ('../../databack/sql', 0777);  
		$sqlzip='../../databack/sql/'.$met_agents_backup.'_'.$zipname.'.zip';
		$archive = new PclZip($sqlzip);
		$zip_list = $archive->create('../../databack/'.$filename,PCLZIP_OPT_REMOVE_PATH,'../../databack/');
		if($zip_list == 0){
			die("Error : ".$archive->errorInfo(true));
		}
		header('location:index.php?lang='.$lang.'&data_msg='.$data_msg.'&action='.$action.'&sizelimit='.$sizelimit.'&tableid='.$tableid.'&fileid='.$fileid.'&startfrom='.$startrow.'&random='.$random.'&anyid='.$anyid.'&cs='.$cs);
	}else{
		cache_delete('bakup_tables.php');
		$adminfile=$url_array[count($url_array)-2];
		if(!file_exists('../../databack/web'))@mkdir ('../../databack/web', 0777);  
		$sqlzip='../../databack/web/'.$met_agents_backup.'_web_'.date('YmdHis',time()).'.zip';
		$zipfile="../../../";
		$archive = new PclZip($sqlzip);
		$zip_list = $archive->create($zipfile,PCLZIP_OPT_REMOVE_PATH,'../../../',PCLZIP_CB_PRE_ADD,'myPreAddCallBack');
		if($zip_list==0){
			die("Error : ".$archive->errorInfo(true));
		}
		metsave($rurls,$lang_setdbArchiveOK,$depth);
	}
}elseif($action=='uploadimg'){
	include "../../include/pclzip.lib.php";
	if(!file_exists('../../databack/upload'))@mkdir ('../../databack/upload', 0777);  
	$sqlzip='../../databack/upload/'.$met_agents_backup.'_upload_'.date('YmdHis',time()).'.zip';
	$zipfile="../../../upload";
	$archive = new PclZip($sqlzip);
	$zip_list = $archive->create($zipfile,PCLZIP_OPT_REMOVE_PATH,'../../../');
	if ($zip_list == 0) {
		die("Error : ".$archive->errorInfo(true));
	}
	metsave($rurls,$lang_setdbArchiveOK,$depth);
}elseif($action=='config'){
	foreach($met_langok as $key=>$val){
		if(file_exists("../../../config/config_".$val[mark].".inc.php"))$zipfile.="../../../config/config_".$val[mark].".inc.php,";
		if(file_exists("../../../config/flash_".$val[mark].".inc.php"))$zipfile.="../../../config/flash_".$val[mark].".inc.php,";
		if(file_exists("../../../config/str_".$val[mark].".inc.php"))$zipfile.="../../../config/str_".$val[mark].".inc.php,";
		if(file_exists("../../../config/feedback_".$val[mark].".inc.php"))$zipfile.="../../../config/feedback_".$val[mark].".inc.php,";
		if(file_exists("../../../config/job_".$val[mark].".inc.php"))$zipfile.="../../../config/job_".$val[mark].".inc.php,";
		if(file_exists("../../../config/message_".$val[mark].".inc.php"))$zipfile.="../../../config/message_".$val[mark].".inc.php,";
	}
	include "../../include/pclzip.lib.php";
	if(!file_exists('../../databack/config'))@mkdir ('../../databack/config', 0777);  
	$sqlzip='../../databack/config/'.$met_agents_backup.'_config_'.date('YmdHis',time()).'.zip';
	$zipfile.="../../../lang,../../../config/lang.inc.php,../../../config/config_db.php";
	$archive = new PclZip($sqlzip);
	$zip_list = $archive->create($zipfile,PCLZIP_OPT_REMOVE_PATH,'../../../');
	if ($zip_list == 0) {
		die("Error : ".$archive->errorInfo(true));
	}
	metsave($rurls,$lang_setdbArchiveOK,$depth);
}elseif($action=='allbase'){
	$localurl="http://";
	$localurl.=$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
	$localurl_a=explode("/",$localurl);
	$localurl_count=count($localurl_a);
	$localurl_admin=$localurl_a[$localurl_count-4];
	$localurl_admin=$localurl_admin."/system/database/index";
	$localurl_real=explode($localurl_admin,$localurl);
	$localurl=$localurl_real[0];
	$fileid = isset($fileid)?$fileid:1;
	if($tbl){
		$tables=tableprearray($tablepre);
		$sizelimit=2048;
	}else{
		foreach($tables as $key=>$val){
			$tablestx.=$val.'|';
		}
	}
	if($fileid==1){
		$random = mt_rand(1000,9999);
		cache_write('bakup_tables.php', $tables);
	}elseif(!$tbl){
		$allidlist=explode('|',$tablestx);
		for($i=0;$i<count($allidlist)-1;$i++){
			$tables[$i]=$allidlist[$i];
		}
	}
	$sqldump = '';
	$tableid = isset($tableid) ? $tableid - 1 : 0;
	$startfrom = isset($startfrom) ? intval($startfrom) : 0;
	$tablenumber = count($tables);
	for($i = $tableid; $i < $tablenumber && strlen($sqldump) < $sizelimit * 1000; $i++){
		$sqldump .= sql_dumptable($tables[$i], $startfrom, strlen($sqldump));
		$startfrom = 0;
	}
	if(trim($sqldump)){
		$version='version:'.$metcms_v;
		$sqldump = "#MetInfo.cn Created {$version} \n#$localurl\n#$tablepre\n# --------------------------------------------------------\n\n\n".$sqldump;
		$tableid = $i;
		$filename = $con_db_name.'_'.date('Ymd').'_'.$random.'_'.$fileid.'.sql';
		$zipname  = $con_db_name.'_'.date('Ymd').'_'.$random.'_'.$fileid;
		$fileid++;
		$bakfile = '../../databack/'.$filename;
		if(!is_writable('../../databack/'))metsave('-1',$lang_setdbTip2.'databack/'.$lang_setdbTip3,$depth); 
		file_put_contents($bakfile, $sqldump);
		include "../../include/pclzip.lib.php";
		if(!file_exists('../../databack/sql'))@mkdir ('../../databack/sql', 0777);  
		$sqlzip='../../databack/sql/'.$met_agents_backup.'_'.$zipname.'.zip';
		$archive = new PclZip($sqlzip);
		$zip_list = $archive->create('../../databack/'.$filename,PCLZIP_OPT_REMOVE_PATH,'../../databack/');
		if($zip_list == 0){
			die("Error : ".$archive->errorInfo(true));
		}
		header('location:index.php?lang='.$lang.'&data_msg='.$data_msg.'&action='.$action.'&sizelimit='.$sizelimit.'&tableid='.$tableid.'&fileid='.$fileid.'&startfrom='.$startrow.'&random='.$random.'&anyid='.$anyid.'&tablestx='.$tablestx.'&tbl='.$tbl);
	}else{
		cache_delete('bakup_tables.php');
		metsave($rurls,$lang_setdbBackupOK,$depth);
	}
}
/*获取数据表*/
if($labtype==2){
	$size = $bktables = $bkresults = $results= array();
	$k = 0;
	$totalsize = 0;
	$query = $db->query("SHOW TABLES FROM ".$con_db_name);
	while($r = $db->fetch_row($query)){  
		if(strstr($r[0], $tablepre)){
			$tables[$k] = $r[0];
			$count = $db->get_one("SELECT count(*) as number FROM $r[0] WHERE 1");
			$results[$k] = $count['number'];
			$bktables[$k] = $r[0];
			$bkresults[$k] = $count['number'];
			$q = $db->query("OPTIMIZE TABLE $r[0]");
			$q = $db->query("SHOW TABLE STATUS FROM `".$con_db_name."` LIKE '".$r[0]."'");
			$s = $db->fetch_array($q);
			$size[$k] = round($s['Data_length']/1024/1024, 2);
			$totalsize += $size[$k];
			$k++;
		}
	}
}
include_once template('system/database/index');footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>