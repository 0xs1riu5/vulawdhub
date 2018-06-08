<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once 'physical.fun.php';
require_once $depth.'../../include/export.func.php';
if($action=="do"){
	$physicaldo[1]=1;
	$physicaldo[2]=1;
	$physicaldo[3]=1;
	$physicaldo[4]=1;
	$physicaldo[5]=1;
	$physicaldo[6]=1;
	$physicaldo[7]=1;
	$physicaldo[8]=1;
	$physicaldo[9]=1;
	$physicaldo[10]=1;
	$physicaldo[11]=1;
	@set_time_limit(0);
	$physical_time=date('Y-m-d H:i:s');
	/*后台文件*/
	$physical_admin="";
	if($physicaldo[1]==1){
		$adminfile=$url_array[count($url_array)-2];
		$physical_admin =$adminfile=="admin"?"0":"1";
	}
	$physical_admin=$physical_admin==null?"-1":$physical_admin;
	/*备份*/
	$physical_backup="";
	if($physicaldo[2]==1){
		$sqlfiles = glob('../../databack/*.sql');
		if(is_array($sqlfiles)){
			foreach($sqlfiles as $val){
				$timearray[]= date('Y-m-d H:i:s', filemtime($val));
			}
			arsort($timearray);
			$timearray=array_merge($timearray);
		}
		if($timearray[0]){
			$timenow=strtotime(date('Y-m-d H:i:s'));
			$timebackup=strtotime($timearray[0]);
			$timedifference=$timenow-$timebackup;
			$timedays = intval($timedifference/86400);
			$physical_backup="$timedays";
		}
		else{
			$physical_backup=-2;
		}
	}
	$physical_backup=$physical_backup==null?"-1":$physical_backup;
	/*网站更新*/
	$physical_update="";
	if($physicaldo[3]==1){
		$updatearray[]=$db->get_one("select max(addtime) as time from $met_news where lang='$lang'");
		$updatearray[]=$db->get_one("select max(addtime) as time from $met_product where lang='$lang'");
		$updatearray[]=$db->get_one("select max(addtime) as time from $met_download where lang='$lang'");
		$updatearray[]=$db->get_one("select max(addtime) as time from $met_img where lang='$lang'");
		arsort($updatearray);
		$updatearray=array_merge($updatearray);
		$updatetime=$updatearray[0]['time'];
		if($updatetime){
			$timenow=strtotime(date('Y-m-d H:i:s'));
			$timebackup=strtotime($updatetime);
			$timedifference=$timenow-$timebackup;
			$timedays = intval($timedifference/86400);
			$physical_update="$timedays";
		}
	}
	$physical_update=$physical_update==null?"-1":$physical_update;
	/*网站关键词*/
	$physical_seo="";
	if($physicaldo[4]==1){
		$physical_seo.=$met_keywords?'1|':'0|';
		$physical_seo.=stristr($met_keywords,'，')?'0|':'1|';
		$physical_seo.=$met_description?'1|':'0|';
	}
	/*静态页面*/
	$physical_static="";
	if($physicaldo[5]==1){
		$physical_static=$met_webhtm!=0&&$met_pseudo?"0":"1";
	}
	$physical_static=$physical_static==null?"-1":$physical_static;
	/*未读信息*/
	$physical_unread="";
	if($physicaldo[6]==1){
		$feedbackcount=$db->counter($met_feedback," where readok=0","*");
		$messagecount=$db->counter($met_message," where readok=0","*");
		$jobcount=$db->counter($met_cv," where readok=0","*");
		$physical_unread="$feedbackcount|$messagecount|$jobcount";
	}
	$physical_unread=$physical_unread==null?"-1":$physical_unread;
	/*垃圾信息*/
	$physical_spam="";
	if($physicaldo[7]==1){
		$count_spam=0;
		$count_spam+=$db->counter($met_news," where recycle>0 and lang='$lang'","*");
		$count_spam+=$db->counter($met_product," where recycle>0 and lang='$lang'","*");
		$count_spam+=$db->counter($met_download," where recycle>0 and lang='$lang'","*");
		$count_spam+=$db->counter($met_img," where recycle>0 and lang='$lang'","*");
		$physical_spam=$count_spam==0?"1":"0";
	}
	$physical_spam=$physical_spam==null?"-1":$physical_spam;
	/*会员激活*/
	$physical_member="";
	if($physicaldo[8]==1){
		$count_member=$db->counter($met_news," where admin_type is null and checkid=0","*");
		$physical_member=$count_member==0?"1":"0";
	}
	$physical_member=$physical_member==null?"-1":$physical_member;
	/*网站网址*/
	$physical_web="";
	if($physicaldo[9]==1){
		$localurl="http://";
		$localurl.=$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
		$localurl=str_replace($met_adminfile."/app/physical/physical.php","",$localurl);
		$physical_web=$localurl==$met_weburl?"1":"0";
	}
	$physical_web=$physical_web==null?"-1":$physical_web;
	/*网站扫描*/
	$physical_file="";
	if($physicaldo[11]==1){
		if(file_exists('standard.php')){filescan('../../..','standard.php');}
		else{$physical_file="0";}
	}
	$physical_file=$physical_file==null?"-1":$physical_file;

	require_once $depth.'../include/config.php';
	header("location:index.php?lang={$lang}&anyid={$anyid}&phy=1");exit;
}elseif($action=="fingerprint"){
	/*指纹比对*/
	$time=date('YmdHis');
	$vers=explode('.',$metcms_v);
	nameout('../../..',"fingerprint_{$time}_{$vers[0]}_{$vers[1]}_{$vers[2]}.txt");
	header("location:advanced.php?lang={$lang}&anyid={$anyid}&cs=3");exit;
}
elseif($action=="fingerprintdo"){
	if(file_exists($f_filename)){fingerprint('../../..',$f_filename);}
	else{$physical_fingerprint="-1";}
	require_once $depth.'../include/config.php';
	header("location:advanced.php?lang={$lang}&anyid={$anyid}&cs=3&phy=1");exit;
}
elseif($action=="fingerprintdel"){
	@unlink($f_filename);
	header("location:advanced.php?lang={$lang}&anyid={$anyid}&cs=3");exit;
}
elseif($action=="dangerfundo"){
	/*敏感函数扫描*/
	$danger="eval|cmd|passthru|system|gzuncompress|exec|shell_exec|fsockopen|pfsockopen|proc_open|scandir";
	$suffix="php|jsp|asp";
	dangerfun('../../..',$danger,$suffix,'trust.php');
	require_once $depth.'../include/config.php';
	header("location:advanced.php?lang={$lang}&anyid={$anyid}&cs=2&phy=1");exit;
}
elseif($action=="op"){
	switch($type){
	case 1:
		$physical_op= &$physical_function;
		break;
	case 2:
		$physical_op= &$physical_file;
		break;
	case 3:
		$physical_op= &$physical_fingerprint;
		break;
	}
	$val=explode('|',$valphy);
	if(stristr(PHP_OS,"WIN")){
		$val[1]=iconv("utf-8","gbk",$val[1]);
	}
	if(!$val[1]){
		echo $lang_physicaldelno;
		die();
	}
	switch($op){
		case 1:
		if(is_dir('../../../'.$val[1])){
			deldir('../../../'.$val[1]);
			echo $lang_physicaldelok;
		}
		else{unlink('../../../'.$val[1]);
			echo $lang_physicaldelok;
			}
		break;
		case 2:
			$met_file='/dl/olupdate_curl.php';
			$adminfile=$url_array[count($url_array)-2];
			$strsvalto=readmin($val[1],$adminfile,1);
			$stringfile=dlfile("v$metcms_v/$strsvalto","../../../$val[1]");
			if($stringfile==1){
				echo $lang_physicalupdatesuc;
			}
			else{
				echo dlerror($stringfile);
				die();
			}
		break;
		case 3:
			$fileaddr=explode('/',$val[1]);
			$filedir="../../../".$fileaddr[0];  
			if(!file_exists($filedir)){ @mkdir ($filedir, 0777); } 
			if($fileaddr[1]=="index.php"){
				Copyindx("../../../".$val[1],$val[2]);
			}
			else{
			switch($val[2]){
				case 1:
					$address="../about/$fileaddr[1]";
				break;
				case 2:
					$address="../news/$fileaddr[1]";
				break;
				case 3:
					$address="../product/$fileaddr[1]";
				break;
				case 4:
					$address="../download/$fileaddr[1]";
				break;
				case 5:
					$address="../img/$fileaddr[1]";
				break;
				case 8:
					$address="../feedback/$fileaddr[1]";
				break;

			}   
				$newfile  ="../../../$val[1]";  			
				Copyfile($address,$newfile);
			}
			echo $lang_physicalgenok;
			break;
		case 4:
			$filename="../../../$val[1]";
			$handle = @fopen($filename,"rb");
			$filecode = @fread($handle,@filesize($filename));
			@fclose($handle);
			echo nl2br(htmlspecialchars($filecode));
			exit;
		break;
	}
	$fun=explode(',',$physical_op);
	$physical_op=NULL;
	foreach($fun as $key1=>$val1){
		$val2=explode('|',$val1);
		if($val2[1]!=$val[1]){
			$physical_op.="$val2[0]|$val2[1]|$val2[2],";
		}
	}
	$physical_op[strlen($physical_op)-1]="";
	require_once $depth.'../include/config.php';
}elseif($action=="download"){
	echo $lang_physicalupdatesuc;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>