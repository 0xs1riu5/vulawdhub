<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserve. 
require_once '../login/login_check.php';
require_once ROOTPATH.'include/export.func.php';
if($type==1){
$met_file='/dl/olupdate_curl.php';
}
if($type==2){

$met_file='/dl/app_curl.php';
}

function checksumdel($typedel){
global $checksum,$met_host,$met_file;
	if($typedel==2){
		delcodeb($checksum);
	}
}
function dl_error($alert,$type,$olid,$ver,$addr,$action,$num=0){
global $checksum,$met_host,$met_file,$db,$url_array,$lang_retested,$lang_redownload,$lang_updaterr21,$lang_updaterr22;
	if($action!='error'){
		if($action=='dl'){
			$num=$num-1;
			$conok="olflie('$olid','$ver','dl','$num');";
		}else{
			$conok="olupdate('$olid','$ver','$action');";
		}
		echo "{$lang_updaterr21}<script type=\"text/javascript\">
		var con;
		alert('{$alert}');";
		if($action=='check'){
			echo "olupdate('{$olid}','{$ver}','error');";
		}else{
			echo "con=confirm('{$lang_updaterr22}');
			if(con){
				$conok
			}else{
				olupdate('$olid','$ver','error');
			}";
		}
		echo "</script>";
		die();
	}
	if($type==1){
		echo "<a href=\"http://$met_host/dl/olupdate.php\" onclick=\"return olupdate('cms','new','test');\">{$lang_retested}</a>"; 
	}
	if($type==2){
		deldir("../app/$addr/");
		$query="select * from $met_app where no=$olid and download=1";
		$appver=$db->get_one($query);
		$verold=is_array($appver)?$appver['ver']:0;
		echo "<a href='http://$met_host/dl/app.php' onclick=\"return olupdate('$olid','$verold','testc');\">{$lang_redownload}</a>"; 
	}
	$adminfile=$url_array[count($url_array)-2];
	$str=file_get_contents(ROOTPATH_ADMIN."/update/$addr/filelist.txt");
	$strs=explode('|',$str);
	foreach($strs as $addrskey=>$strsval){	
		$strsvalto=readmin($strsval,$adminfile,2);
		$str=file_get_contents("../../$strsvalto");
		if($str=='metinfo'||$str=='No Date'){
			unlink("../../$strsvalto");
		}
	}	
	checksumdel($type);
	unlink("../../update.php");
	unlink("../../sql.sql");
	deldir("../update/$addr/");
	die();
}
@clearstatcache();
if($action=="check"){
	if(!get_extension_funcs('curl')&&!function_exists(fsockopen)&&!function_exists(pfsockopen)){
		echo '|';
		dl_error($lang_supportnot,$type,$olid,$ver,$addr,$action);
	}
	if($type==2){
		$query="select * from $met_app where no='$olid' and download='0'";
		$app=$db->get_one($query);
		if($app['power']){
			$return=varcodeb('app');
			if($return[re]=='DISREAD'){
				echo "|$lang_updaterr18|noaddr|";
				die();
			}
		}
		$met_file='/dl/app.php';
		$post_data = array('test'=>$test,'olid'=>$olid,'ver'=>$ver,'checksum'=>$return['md5'],'sys'=>$metcms_v);
		$result=curl_post($post_data,30);
		$results=explode('|',$result);
		if($results[2]=='noaddr'){
			delcodeb($return['md5']);
		}
		echo $result;
	}
	if($type==1){
		$met_file='/dl/olupdate.php';
		$post_data = array('test'=>$test,'ver'=>$ver);
		$result=curl_post($post_data,30);
		$results=explode('|',$result);
		$query="update $met_config set value='$results[2]' where name='met_newcmsv'";
		$db->query($query);
		echo $result;
	}
}
else if($action=='dateback'){
	//备份数据库
	$sqlfiles = glob('../databack/*.sql');
	$backup=1;
	foreach($sqlfiles as $id=>$sql_file){
		$sqlfileval=explode('_',basename($sql_file));
		if($sqlfileval[1]==date('Ymd',time())){
			$backup=0;
			break;
		}
	}
	if($backup==1){
		require_once '../system/database/global.func.php';
		$adminfile=$url_array[count($url_array)-2];
		$num=1;
		$random = mt_rand(1000,9999);
		$date=date('Ymd',time());
		do{
			$sqldump = '';
			$startrow = '';
			$tables=tableprearray($tablepre);
			$sizelimit=2048;
			$tableid = isset($tableid) ? $tableid - 1 : 0;
			$startfrom = isset($startfrom) ? intval($startfrom) : 0;
			$tablenumber = count($tables);
			for($i = $tableid; $i < $tablenumber && strlen($sqldump) < $sizelimit * 1000; $i++){
				$sqldump .= sql_dumptable($tables[$i], $startfrom, strlen($sqldump));
				$startfrom = 0;
			}
			$startfrom = $startrow;
			$tableid = $i;
			if(trim($sqldump)){
				$version='version:'.$metcms_v;
				$sqldump = "#MetInfo.cn Created {$version} \n#{$met_weburl}\n#{$tablepre}\n# --------------------------------------------------------\n\n\n".$sqldump;
				$sqlfile[]=$bakfile = "../databack/{$con_db_name}_{$date}_{$random}_{$num}.sql";
				if(!file_put_contents($bakfile, $sqldump)){
					dl_error($lang_updaterr2."({$adminfile}/databack/{$con_db_name}_{$date}_{$random}_{$num}.sql)",$type,$olid,$ver,$addr,$action);
				}
			}
			$num++;
		}
		while(trim($sqldump));
		if(is_array($sqlfile)) $string = "<?php\n \$sqlfile = ".var_export($sqlfile, true)."; ?>";
		filetest("../update/$addr/sqlist.php");
		if(!file_put_contents("../update/$addr/sqlist.php",$string)){
			dl_error($lang_updaterr2."({$adminfile}/update/{$addr}/sqlist.php)",$type,$olid,$ver,$addr,$action);
		}
	}
	//备份文件
	$adminfile=$url_array[count($url_array)-2];
	filetest("../update/$addr/filelist.txt");
	$return=dlfile("$addr/filelist.txt","../update/$addr/filelist.txt");
	if($return!=1){		
		dl_error($lang_updownerrs."({$adminfile}/update/{$addr}/filelist.txt)".dlerror($return),$type,$olid,$ver,$addr,$action);
	}
	$str=file_get_contents("../update/$addr/filelist.txt");	
	$strs=explode('|',$str);
	foreach($strs as $addrskey=>$strsval){	
		$strsvalto=readmin($strsval,$adminfile,2);
		$str=file_get_contents("../../$strsvalto");
		if($str){
			if(!filetest("../update/$addr/dateback/$strsvalto")){
				dl_error("{$adminfile}/update/{$addr}/dateback/{$strsvalto}{$lang_updaterr1}",$type,$olid,$ver,$addr,$action);
			}
			if(!file_put_contents("../update/$addr/dateback/$strsvalto",$str)){
				dl_error("{$adminfile}/update/{$addr}/dateback/{$strsvalto}{$lang_updaterr1}",$type,$olid,$ver,$addr,$action);
			}
		}
	}
	if($date_back){
		echo "{$lang_updaterr5}<script type=\"text/javascript\">setTimeout(function (){olupdate('$olid','$ver','dirpower');},500);</script>";
	}
	else{
		echo "{$lang_updaterr16}&nbsp;&nbsp;<a href='#' onclick=\"return olupdate('$olid','$ver','datebackall');\">{$lang_updaterr17}</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onclick=\"return olupdate('$olid','$ver','dirpower');\">跳过</a>"; 
	}
}
else if($action=='datebackall'){
	$adminfile=$url_array[count($url_array)-2];
	include "../include/pclzip.lib.php";
	if(!file_exists('../databack/web'))@mkdir ('../databack/web', 0777);  
	$sqlzip='../databack/web/metinfo_web_'.date('YmdHis',time()).'.zip';
	$zipfile="../../";
	$archive = new PclZip($sqlzip);
	$zip_list = $archive->create($zipfile,PCLZIP_OPT_REMOVE_PATH,'../../',PCLZIP_CB_PRE_ADD,'myPreAddCallBack');
	if($zip_list==0){
		dl_error($lang_updaterr3."({$adminfile}/databack/web/metinfo_web_".date('YmdHis',time()).".zip)",$type,$olid,$ver,$addr,$action);
	}
	echo "{$lang_updaterr5}<script type=\"text/javascript\">setTimeout(function (){olupdate('$olid','$ver','dirpower');},500);</script>";
}
else if($action=='dirpower'){/*目录权限检测*/
	$adminfile=$url_array[count($url_array)-2];
	if($type==2){
		filetest("../update/$addr/filelist.txt");
		$return=dlfile("$addr/filelist.txt","../update/$addr/filelist.txt");
		if($return!=1){		
			dl_error($lang_updownerrs.dlerror($return)."({$adminfile}/update/$addr/filelist.txt)",$type,$olid,$ver,$addr,$action);
		}
	}
	$str=file_get_contents("../update/$addr/filelist.txt");
	$strs=explode('|',$str);
	array_push($strs,"$addr/update.php");
	array_push($strs,"$addr/sql.sql");
	array_push($strs,"$addr/dlfilelist.txt");
	array_push($strs,"$addr/check.php");
	array_push($strs,"$addr/dladd.php");
	foreach($strs as $addrskey=>$strsval){
		$strsvalto=readmin($strsval,$adminfile,2);
		if(!filetest('../../'.$strsvalto)){
			dl_error("{$strsvalto}{$lang_updaterr6}",$type,$olid,$ver,$addr,$action); 
		}
		if(!filetest("../update/$addr/$strsval")){
			dl_error("{$adminfile}/update/{$addr}/{$strsval}{$lang_updaterr6}",$type,$olid,$ver,$addr,$action);
		}
	}
	unlink("../../$addr/update.php");
	unlink("../../$addr/sql.sql");
	unlink("../../$addr/dlfilelist.txt");
	unlink("../../$addr/check.php");
	unlink("../../$addr/dladd.php");
	rmdir("../../$addr/");
	$return=dlfile("$addr/check.php","../update/$addr/check.php");
	if($return!=1){		
		dl_error($lang_updownerrs.dlerror($return)."({$adminfile}/update/$addr/check.php)",$type,$olid,$ver,$addr,$action);
	}
	$check=file_get_contents("../update/$addr/check.php");
	if($check!="No Date"){
		include "../update/$addr/check.php";
	}
	echo "{$lang_updaterr7}<script type=\"text/javascript\">setTimeout(function (){olupdate('$olid','$ver','down');},500);</script>";
}
else if($action=='down'){/*下载文件*/
	$adminfile=$url_array[count($url_array)-2];
	$return=dlfile("$addr/dlfilelist.txt","../update/$addr/dlfilelist.txt");
	if($return!=1){
		dl_error("{$lang_updaterr8}".dlerror($return)."({$adminfile}/update/$addr/dlfilelist.txt)",$type,$olid,$ver,$addr,$action);
	}
	$return=dlfile("$addr/dladd.php","../update/$addr/dladd.php");
	if($return!=1){
		dl_error("{$lang_updaterr8}".dlerror($return)."({$adminfile}/update/$addr/dladd.php)",$type,$olid,$ver,$addr,$action);
	}
	$dladd=file_get_contents("../update/$addr/dladd.php");
	if($dladd!="No Date"){
		include "../update/$addr/dladd.php";
	}
	$str=file_get_contents("../update/$addr/dlfilelist.txt");
	if($str){
		if($str!='No Date')$strs=explode('|',$str);
		array_push($strs,'update.php');
		array_push($strs,'sql.sql');
		$string = "<?php\n \$strs=".var_export($strs,true)."; ?>";
		file_put_contents("../update/$addr/dlfilelist.txt",$string);
		echo "{$lang_updaterr9}0%<script type=\"text/javascript\">olflie('$olid','$ver','dl',0);</script>";
	}
}
else if($action=='dl'){
	include "../update/$addr/dlfilelist.txt";
	$strsnum=count($strs);
	$return=dlfile("$addr/$strs[$numnow]","../update/$addr/$strs[$numnow]");
	$numnow++;
	if($return!=1){
		dl_error($strs[$numnow-1].$lang_updownerrs.dlerror($return),$type,$olid,$ver,$addr,$action,$numnow);
	}
	if($strsnum==$numnow){
		$sql=file_get_contents("../update/$addr/sql.sql");
		if($sql!="No Date"){
			echo "{$lang_updaterr10}<script type=\"text/javascript\">setTimeout(function (){olupdate('$olid','$ver','sql');},500);</script>"; 
		}else{
			echo $lang_jsx26."<script type=\"text/javascript\">olupdate('$olid','$ver','update');</script>";
		}
		checksumdel($type);
	}
	else{
		$percentage=floor((($numnow)/$strsnum)*100);
		echo $lang_updaterr9."$percentage%<script type=\"text/javascript\">olflie('$olid','$ver','dl','$numnow');</script>";
	}
}
else if($action=='sql'){/*数据库更新，注：如建立新表，升级SQL文件中先因该删除此表，在新建。*/
	$sql=file_get_contents("../update/$addr/sql.sql");
	$adminfile=$url_array[count($url_array)-2];
	if($sql!="No Date"){
		if(file_exists("../update/$addr/sqlist.php"))include "../update/$addr/sqlist.php";
		if(!is_array($sqlfile)){
			$num=1;
			$random = mt_rand(1000,9999);
			$date=date('Ymd',time());
			require_once '../system/database/global.func.php';
			do{
				$sqldump = '';
				$startrow = '';
				$tables=tableprearray($tablepre);
				$sizelimit=2048;
				$tableid = isset($tableid) ? $tableid - 1 : 0;
				$startfrom = isset($startfrom) ? intval($startfrom) : 0;
				$tablenumber = count($tables);
				for($i = $tableid; $i < $tablenumber && strlen($sqldump) < $sizelimit * 1000; $i++){
					$sqldump .= sql_dumptable($tables[$i], $startfrom, strlen($sqldump));
					$startfrom = 0;
				}
				$startfrom = $startrow;
				$tableid = $i;
				if(trim($sqldump)){
					$sqlfile[]=$bakfile = "../update/$addr/{$con_db_name}_{$date}_{$random}_{$num}.sql";
					$version='version:'.$metcms_v;
					$sqldump = "#MetInfo.cn Created {$version} \n#{$met_weburl}\n#{$tablepre}\n# --------------------------------------------------------\n\n\n".$sqldump;
					if(!file_put_contents($bakfile, $sqldump)){
						dl_error($lang_updaterr2."({$adminfile}/update/$addr/{$con_db_name}_{$date}_{$random}_{$num}.sql)",$type,$olid,$ver,$addr,$action);
					}
				}
				$num++;
			}
			while(trim($sqldump));
			if(is_array($sqlfile)) $string = "<?php\n \$sqlfile = ".var_export($sqlfile, true)."; ?>";
			filetest("../update/$addr/sqlist.php");
			if(!file_put_contents("../update/$addr/sqlist.php",$string)){
				dl_error($lang_updaterr2."({$adminfile}/update/$addr/sqlist.php)",$type,$olid,$ver,$addr,$action);
			}
		}
		require_once '../system/database/global.func.php';
		$return=sql_execute($sql,1);
		if($return){
			echo $lang_updaterr11."<script type=\"text/javascript\">setTimeout(function (){olupdate('$olid','$ver','update');},500);</script>"; 
		}
		else{
			foreach($sqlfile as $sqlkey=>$sqlval){
				$sql=file_get_contents($sqlval);
				sql_execute($sql);
			}
			dl_error($lang_updaterr12.mysql_error(),$type,$olid,$ver,$addr,$action);
		}
	}
	else{
		echo $lang_updaterr13."<script type=\"text/javascript\">setTimeout(function (){olupdate('$olid','$ver','update');},500);</script>"; 
	}
}
else if($action=='update'){/*文件更新 注升级包中admin文件不需要改名，直接写admin*/
	$adminfile=$url_array[count($url_array)-2];
	include "../update/$addr/dlfilelist.txt";
	$file_error[0]=0;
	foreach($strs as $addrskey=>$strsval){	
		$strsvalto=readmin($strsval,$adminfile,2);
		$str=file_get_contents("../update/$addr/$strsval");
		if(!file_put_contents("../../$strsvalto",$str)){
			$file_error[0]=1;
			$file_error[2]=$strsvalto;
		}
	}
	if($file_error[0]==1){
		foreach($strs as $addrskey=>$strsval){	
			$strsvalto=readmin($strsval,$adminfile,2);
			$str=file_get_contents("../update/$addr/dateback/$strsvalto");
			if($str){
				file_put_contents("../../$strsvalto",$str);
			}
			else{
				unlink("../../$strsvalto");
			}
		}
		require_once '../system/database/global.func.php';
		if(file_exists("../update/$addr/sqlist.php"))include "../update/$addr/sqlist.php";
		foreach($sqlfile as $sqlkey=>$sqlval){
			$sql=file_get_contents($sqlval);
			sql_execute($sql);
		}
		dl_error("{$file_error[2]}{$lang_updaterr14}",$type,$olid,$ver,$addr,$action);
	}
	$str=file_get_contents("../update/$addr/update.php");
	if($str!='No Date'){
		include "../update/$addr/update.php";
	}
	if($type==1){
		$db->query("update $met_config set value='$ver' where name='metcms_v'");
		$authinfo=$db->get_one("SELECT * FROM $met_otherinfo where id=1");
		$met_file='/dl/record_dl.php';
		$post_data = array('cmd'=>'sys','url'=>$met_weburl,'code'=>$authinfo['authcode'],'key'=>$authinfo['authpass'],'ver'=>$ver);
		curl_post($post_data,10);
		
		$num=1;
		$random = mt_rand(1000,9999);
		$date=date('Ymd',time());
		require_once '../system/database/global.func.php';
		do{
			$sqldump = '';
			$startrow = '';
			$tables=tableprearray($tablepre);
			$sizelimit=2048;
			$tableid = isset($tableid) ? $tableid - 1 : 0;
			$startfrom = isset($startfrom) ? intval($startfrom) : 0;
			$tablenumber = count($tables);
			for($i = $tableid; $i < $tablenumber && strlen($sqldump) < $sizelimit * 1000; $i++){
				$sqldump .= sql_dumptable($tables[$i], $startfrom, strlen($sqldump));
				$startfrom = 0;
			}
			$startfrom = $startrow;
			$tableid = $i;
			if(trim($sqldump)){
				$version='version:'.$ver;
				$sqldump = "#MetInfo.cn Created {$version} \n#{$met_weburl}\n#{$tablepre}\n# --------------------------------------------------------\n\n\n".$sqldump;
				$bakfile = "../databack/{$con_db_name}_{$date}_{$random}_{$num}.sql";
				file_put_contents($bakfile, $sqldump);
			}
			$num++;
		}
		while(trim($sqldump));
		
	}else if($type==2){
		$query="select * from $met_app where no=$olid and download=0";
		$app=$db->get_one($query);
		$query="select * from $met_app where no=$olid and download=1";
		if($db->get_one($query)){
			$query="update $met_app set name='$app[name]',ver='$app[ver]',img='$app[img]',info='$app[info]',file='$app[file]',power='$app[power]',sys='$app[sys]',site='$app[site]',url='$app[url]' where no='$app[no]' and download=1";
			$db->query($query);
		}
		else{
			$query="insert into $met_app set name='$app[name]',no='$app[no]',ver='$app[ver]',img='$app[img]',info='$app[info]',file='$app[file]',power='$app[power]',sys='$app[sys]',site='$app[site]',url='$app[url]',download=1";	
			$db->query($query);
		}
		$met_file='/dl/record_dl.php';
		$post_data = array('cmd'=>'app','addr'=>$addr);
		curl_post($post_data,10);
	}
	deldir("../update/$addr/");
	unlink("../../update.php");
	unlink("../../sql.sql");
	checksumdel($type);
	echo $lang_updaterr15."<script type=\"text/javascript\">setTimeout(function (){olupdate('$olid','$ver','testc');},500);</script>";	
}else if($action=='error'){
	dl_error('',$type,$olid,$ver,$addr,$action);
}else if($action=='patch'){
	$adminfile=$url_array[count($url_array)-2];
	$met_file='/dl/patch.php';
	$post_data = array('ver'=>$metcms_v,'patch'=>$met_patch);
	$difilelist=curl_post($post_data,10);
	$difilelists=explode('*',$difilelist);
	$met_file='/dl/olupdate_curl.php';
	foreach($difilelists as $key=>$val){
		$difilelistss=explode('|',$val);
		$met_patch=$difilelistss[0];
		unset($difilelistss[0]);
		foreach($difilelistss as $key1=>$val1){
			$val2=readmin($val1,$adminfile,2);
			$re=dlfile("v$metcms_v/$val1","../../$val2");
			if($re!=1){
				die();
			}
		}
		if(file_exists("../update/v{$metcms_v}_{$met_patch}.php")){
			require_once "../update/v{$metcms_v}_{$met_patch}.php";
		}
		@unlink("../update/v{$metcms_v}_{$met_patch}.php");
		$query="update $met_config set value='$met_patch' where name='met_patch'";
		$db->query($query);
	}
	die();
}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>