<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
/*体检函数*/
/*遍历文件*/
function traversal($jkdir,$suffix='[A-Za-z]*',$jump=null)
{
	global $filenamearray;
	//$hand=@dir($jkdir);
	$hand=opendir($jkdir);
	//while ($file=$hand->read())
	while ($file=readdir($hand))
	{	
		$filename=$jkdir.'/'.$file;
		if(@is_dir($filename) && $file != '.' && $file!= '..'&& $file!='./..'){ 
			if($jump!=null){
					$filefrist=str_replace('../','',$filename);
					$filefrist=explode('/',$filefrist);
					if(preg_match_all ("/^($jump)$/",$filefrist[0],$out))continue;
			}
			traversal($filename,$suffix,$jump);
		}
		else{
			if($file != '.' && $file!= '..'&& $file!='./..'&&preg_match_all ("/\.($suffix)/i",$filename,$out)){
				$str=file_get_contents($filename);
				$str=str_replace(array("\n","\r","\t"," "),'',$str);
				$filesize= strlen($str);
				$filename=str_replace('../','',$filename);	
				$info=pathinfo($filename);
				if(stristr(PHP_OS,"WIN")){
					$filename=iconv("gbk","utf-8",$filename);		
				}
				$filenamearray[$filename]['have']=1;
				$filenamearray[$filename]['filesize']=$filesize; 
			}
		}	

	}
}
/*输出文件大小*/
function met_filesize($dir){
	$str=file_get_contents($dir);
	$str=str_replace(array("\n","\r","\t"," "),'',$str);
	$filesize=strlen($str);
	return $filesize;	
}
/*输出指纹*/
function nameout($jkdir,$file){
global $filenamearray,$url_array;
$adminfile=$url_array[count($url_array)-2];
$filenamearray=array();
traversal($jkdir,'php|jsp|asp|aspx|js',"templates|$adminfile|cache");
foreach($filenamearray as $key=>$val){
$string.="[$key]
have=1
filesize=$val[filesize]
";
}
$filenamearray=array();
traversal($jkdir.'/templates','php|jsp|asp|aspx|js|html|htm');
foreach($filenamearray as $key=>$val){
$string.="[$key]
have=1
filesize=$val[filesize]
";
}
$filenamearray=array();
traversal($jkdir."/$adminfile",'php|jsp|asp|aspx|js|html|htm','update');
foreach($filenamearray as $key=>$val){
$string.="[$key]
have=1
filesize=$val[filesize]
";
}
$filenamearray=array();
traversal($jkdir."/member/templates",'php|jsp|asp|aspx|js|htm|html',"member/templates");
foreach($filenamearray as $key=>$val){
$string.="[$key]
have=1
filesize=$val[filesize]
";
}
file_put_contents($file,$string);
}
/*比对指纹 $fileback为指纹文件*/
function fingerprint($jkdir,$fileback){
	global $filenamearray,$physical_fingerprint,$url_array;
	$adminfile=$url_array[count($url_array)-2];
	$physical_fingerprint="";
	$fbdir=$fileback;
	$fileback=parse_ini_file($fileback,true);
	$filenamearray=array();
	traversal($jkdir,'php|jsp|asp|aspx|js',"templates|$adminfile|cache");
	$filenow=$filenamearray;
	$filenamearray=array();
	traversal($jkdir.'/templates','php|jsp|asp|aspx|js|html|htm');
	foreach($filenamearray as $key=>$val){
		$filenow[$key]=$val;
	}
	$filenamearray=array();
	traversal($jkdir."/$adminfile",'php|jsp|asp|aspx|js|html|htm','update');
	foreach($filenamearray as $key=>$val){
		$filenow[$key]=$val;
	}
	$filenamearray=array();
	traversal($jkdir."/member/templates",'php|jsp|asp|aspx|js|html|htm');
	foreach($filenamearray as $key=>$val){
		$filenow[$key]=$val;
	}
	deldir(ROOTPATH.'/cache');
	mkdir(ROOTPATH.'cache/','0755');
	deldir(ROOTPATH."/$adminfile/update");
	if($fbdir=='fingerprint_metinfo.php'){
		unset($filenow['config/config_db.php']);
		unset($filenow["$adminfile/app/physical/fingerprint_metinfo.php"]);
		unset($filenow["$adminfile/app/physical/standard.php"]);
	}
	foreach($fileback as $key=>$val){
		if(stripos($key,'admin/add.php')!==false){
			$admin_filebacks=explode('/',$key);
			$admin_fileback=$admin_filebacks[0];
		}
	}
	if($admin_fileback!=$adminfile){
		$fileback_temp=$fileback;
		$fileback=array();
		$len=strlen($admin_fileback);
		foreach($fileback_temp as $key=>$val){
			$key_temp=preg_replace("/^$admin_fileback\//",$adminfile.'/',$key);
			$fileback[$key_temp]['have']=$val['have'];
			$fileback[$key_temp]['filesize']=$val['filesize'];
		}
	}
	foreach($fileback as $key=>$val){
		if($filenow[$key]['have']!=1){
			$physical_fingerprint .="3|$key|,";
		}
	}
	foreach($filenow as $key=>$val){
		if($fileback[$key]['have']!=1){
			$physical_fingerprint .="1|$key|,";
		}
		else{
			$keys=explode('/',$key);
			if($fileback[$key]['filesize']!=$filenow[$key]['filesize']&&(!preg_match_all ("/\.ini/i",$key,$out)&&$keys[count($keys)-1]!='fingerprint.inc.php')){
				$physical_fingerprint .="2|$key|,";
			}
		}
	}
	$physical_fingerprint=trim($physical_fingerprint,',');
	$physical_fingerprint=$physical_fingerprint==null?"1":$physical_fingerprint;
}
function dangerfun($jkdir,$danger,$suffix,$trust){
	global $filenamearray,$physical_function,$db,$met_column,$url_array;
	@unlink('../../../install/phpinfo.php');
	$physical_function="";
	$adminfile=$url_array[count($url_array)-2];
	$column=$db->get_all("select * from $met_column where classtype=1 or releclass!=0");
	$columnfile=array('about',$adminfile,'cache','config','download','feedback','img','include','job','lang','link','member','message','news','product','public','search','sitemap','templates','upload','wap','install','update');
	foreach($column as $key=>$val){
		array_push($columnfile,$val['foldername']);
	}
	$columnfile=array_unique($columnfile);
	$hand=@dir($jkdir);
	while ($file=$hand->read()){
		if(is_dir('../../../'.$file)&&$file!='.'&&$file!='..'){
			$fileroot[]=$file;
		}
	}
	$diff=array_diff($fileroot,$columnfile);
	foreach($diff as $key=>$val){
		$physical_function.="3|$val|,";
	}
	$diff=implode('|',$diff);
	$filenamearray=array();
	$trust=parse_ini_file($trust,1);
	traversal($jkdir,$suffix,$diff);
	$filenow=$filenamearray;
	$danger=explode('|',$danger);
	foreach($filenow as $key=>$val){
	if(preg_match_all ("/\.(php)/i",$key,$out)){
		$str='';
		$handle = @fopen('../../../'.$key,"rb");
		$str = @fread($handle,@filesize('../../../'.$key));
		@fclose($handle);
		foreach($danger as $key1 => $val1){
			if(preg_match_all ("/([^A-Za-z0-9_]$val1)[\r\n\t]{0,}([\[\(])/i",$str,$out)){	
				$dir=readmin($key,$adminfile,1);
				if($trust[$val1][$dir]!=1){$physical_function.="1|$key|$val1,";}
			}
		}
		if(preg_match_all ("/[A-Za-z0-9+\/]{100}/i",$str,$out)){	
			$dir=readmin($key,$adminfile,1);
			if($trust['encryption'][$dir]!=1&&!preg_match_all ("/authtemp/i",$str,$out)){$physical_function.="1|$key,";}
		}
		if($val[filesize]<100&&$val[filesize]>0){
			if(substr($key,0,6)=='cache/'){
				unlink('../../../'.$key);
			}else{
				if($trust['size'][$dir]!=1)$physical_function.="1|$key,";
			}
		}
	}
	else{
		$physical_function.="2|$key|,";
	}
		
	}
	if(file_exists('../../../install'))file_put_contents('../../../install/phpinfo.php','<?php phpinfo(); ?>');
	$physical_function=trim($physical_function,',');
	$physical_function=$physical_function==null?"1":$physical_function;
}
function filescan($jkdir,$fileback){
	global $filenamearray,$physical_file,$met_langok,$db,$met_column,$url_array;
	$physical_file="";
	$adminfile=$url_array[count($url_array)-2];
	$fileback=parse_ini_file($fileback,true);
	if($adminfile!='admin'){
		foreach($fileback as $key=>$val){
			$strsvals=explode('/',$key);
			if($strsvals[0]=='admin'){
				$strsvals[0]=$adminfile;
				$strsvalto=implode('/',$strsvals);	
				$fileback_temp[$strsvalto]=$fileback[$key];
				unset($fileback[$key]);
			}
		}
	$fileback=array_merge($fileback,$fileback_temp);
	}
	$filenamearray=array();
	//traversal($jkdir);
	//$filenow=$filenamearray;
	$column=$db->get_all("select * from $met_column where classtype=1 or releclass!=0");
	$column1=array('about',$adminfile,'cache','config','download','feedback','img','include','job','lang','link','member','message','news','product','public','search','sitemap','templates','upload','upload_thumbs','wap','install');
	$i=0;
	foreach($column as $key=>$val){
		$column2[]=$val['foldername'];
		$column3[]=$val;
	}
	$columndiff=array_diff($column2,$column1);
	foreach($columndiff as $key=>$val){
		switch($column3[$key]['module']){
		case 1:
			$filediff["{$column3[$key][foldername]}/index.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/index.php"]['filesize']=381;
			$filediff["{$column3[$key][foldername]}/index.php"]['module']=1;
			$filediff["{$column3[$key][foldername]}/show.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/show.php"]['filesize']=311;
			$filediff["{$column3[$key][foldername]}/show.php"]['module']=1;
		break;
		case 2:
			$filediff["{$column3[$key][foldername]}/index.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/index.php"]['filesize']=381;
			$filediff["{$column3[$key][foldername]}/index.php"]['module']=2;
			$filediff["{$column3[$key][foldername]}/news.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/news.php"]['filesize']=310;
			$filediff["{$column3[$key][foldername]}/news.php"]['module']=2;
			$filediff["{$column3[$key][foldername]}/shownews.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/shownews.php"]['filesize']=314;
			$filediff["{$column3[$key][foldername]}/shownews.php"]['module']=2;
		break;
		case 3:
			$filediff["{$column3[$key][foldername]}/index.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/index.php"]['filesize']=381;
			$filediff["{$column3[$key][foldername]}/index.php"]['module']=3;
			$filediff["{$column3[$key][foldername]}/product.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/product.php"]['filesize']=316;
			$filediff["{$column3[$key][foldername]}/product.php"]['module']=3;
			$filediff["{$column3[$key][foldername]}/showproduct.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/showproduct.php"]['filesize']=320;
			$filediff["{$column3[$key][foldername]}/showproduct.php"]['module']=3;
		break;
		case 4:
			$filediff["{$column3[$key][foldername]}/index.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/index.php"]['filesize']=381;
			$filediff["{$column3[$key][foldername]}/index.php"]['module']=4;
			$filediff["{$column3[$key][foldername]}/download.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/download.php"]['filesize']=318;
			$filediff["{$column3[$key][foldername]}/download.php"]['module']=4;
			$filediff["{$column3[$key][foldername]}/showdownload.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/showdownload.php"]['filesize']=322;
			$filediff["{$column3[$key][foldername]}/showdownload.php"]['module']=4;
			$filediff["{$column3[$key][foldername]}/down.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/down.php"]['filesize']=314;
			$filediff["{$column3[$key][foldername]}/down.php"]['module']=4;
		break;
		case 5:
			$filediff["{$column3[$key][foldername]}/index.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/index.php"]['filesize']=381;
			$filediff["{$column3[$key][foldername]}/index.php"]['module']=5;
			$filediff["{$column3[$key][foldername]}/img.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/img.php"]['filesize']=308;
			$filediff["{$column3[$key][foldername]}/img.php"]['module']=5;
			$filediff["{$column3[$key][foldername]}/showimg.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/showimg.php"]['filesize']=312;
			$filediff["{$column3[$key][foldername]}/showimg.php"]['module']=5;
		break;
		case 8:
			$filediff["{$column3[$key][foldername]}/index.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/index.php"]['filesize']=381;
			$filediff["{$column3[$key][foldername]}/index.php"]['module']=8;
			$filediff["{$column3[$key][foldername]}/uploadfile_save.php"]['have']=1;
			$filediff["{$column3[$key][foldername]}/uploadfile_save.php"]['filesize']=325;
			$filediff["{$column3[$key][foldername]}/uploadfile_save.php"]['module']=8;
		break;	
		}
	}
	foreach($fileback as $key=>$val){
		if(!file_exists('../../../'.$key)){
			$physical_file .="1|$key|,";
		}
		else{
			if($fileback[$key]['filesize']!=met_filesize('../../../'.$key)){
				$physical_file .="2|$key|,";
			}
		}
	}
	foreach($filediff as $key=>$val){
		if(!file_exists('../../../'.$key)){
			$physical_file .="4|$key|$val[module],";
		}
		else{
			if($filediff[$key]['filesize']!=met_filesize('../../../'.$key)){
				$physical_file .="5|$key|$val[module],";
			}
		}
	}
	//die();
	$physical_file=trim($physical_file,',');
	$physical_file=$physical_file==null?"1":$physical_file;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>