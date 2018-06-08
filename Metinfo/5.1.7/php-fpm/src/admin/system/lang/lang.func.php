<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once $depth.'../../include/export.func.php';
function syn_lang($post,$filename,$langmark,$site,$type){
	global $met_host,$met_file,$met_language,$db;
	$met_file='/dl/lang/lang.php';
	$restr=curl_post($post,30);
	$link=link_error($restr);
	if($link!=1){
		return $link;
	}
	filetest($filename);
	file_put_contents($filename,$restr);
	$array=0;
	$no_order=0;
	$array_l=0;
	$no_order_l=0;
	$array_s=0;
	$no_order_s=0;
	if(file_exists($filename)){
		if($type!=1){
			$query="delete from $met_language where site='$site' and lang='$langmark'";
			$db->query($query);
		}
		$fp = @fopen($filename, "r");
		while ($conf_line = @fgets($fp, 1024)){    
			if(substr($conf_line,0,1)=="#"){
				$no_order_l++;
				$array_l=0;	
				$no_order_s=0;				
				$array=$array_l;
				$no_order=$no_order_l;
				$line = ereg_replace("^#", "", $conf_line);
				$flag=1;
			}else{
				$no_order_s++;
				$array_s=$no_order_l;		
				$line = $conf_line;
				$array=$array_s;
				$no_order=$no_order_s;
				$flag=0;
			}
			if (trim($line) == "") continue;
			$linearray=explode ('=', $line);
			$linenum=count($linearray);
			if($linenum==2){
			list($name, $value) = explode ('=', $line);
			}else{

			  for($i=0;$i<$linenum;$i++){

				 $linetra=$i?$linetra."=".$linearray[$i]:$linearray[$i].'metinfo_';
			   }
			list($name, $value) = explode ('metinfo_=', $linetra);
			}
			$value=str_replace("\"","&quot;",$value);
			list($value, $valueinfo)=explode ('/*', $value);
			$name = str_replace('\\','',daddslashes(trim($name),1,'metinfo'));
			$value=str_replace("'","''",$value);
			$value=str_replace("\\","\\\\",$value);
			$value=trim($value,"\n");
			$value=trim($value,"\r");
			$value=trim($value,"\n");
			$value=trim($value,"\r");
			$value=str_replace('\\n',',',$value);
			$query="insert into $met_language set name='$name',value='$value',site='$site',no_order='{$no_order}',array='$array',lang='$langmark'";
			$db->query($query);
		}
		fclose($fp);
	}
	unlink($filename);
	return 1;
}
function copyconfig(){
	global $db,$met_config,$met_language,$langfile,$synchronous,$langmark,$langautor,$thisurl,$lang_langcopyfile,$langdlok,$met_skin_user,$depth;
	global $met_file,$met_host,$metcms_v;
	if($langdlok=='1'){
		$newlangmark=$langautor?$langmark:$synchronous;
		$post=array('newlangmark'=>$newlangmark,'metcms_v'=>$metcms_v);
		$file_basicname=$depth.'../update/lang/lang_'.$newlangmark.'.ini';
		$sun_re=syn_lang($post,$file_basicname,$langmark,0,1);
	}else{
		$query="select * from $met_language where site='0' and lang='$langfile'";
		$languages=$db->get_all($query);
		foreach($languages as $key=>$val){
			$val[value] = str_replace("'","''",$val[value]);
			$val[value] = str_replace("\\","\\\\",$val[value]);
			$query = "insert into $met_language set name='$val[name]',value='$val[value]',site='0',no_order='$val[no_order]',array='$val[array]',lang='$langmark'";
			$db->query($query);
		}
		$sun_re=1;
	}
	$query="select * from $met_config where lang='$langfile' and columnid=0";
	$configs=$db->get_all($query);
	foreach($configs as $key=>$val){
		$val[value] = str_replace("'","''",$val[value]);
		$val[value] = str_replace("\\","\\\\",$val[value]);
		$query = "insert into $met_config set name='$val[name]',value='$val[value]',columnid='$val[columnid]',flashid='$val[flashid]',lang='$langmark'";
		$db->query($query);
	}
	$oldfile      =$depth."../../templates/$met_skin_user/lang/language_$langfile.ini";   
	$newfile      =$depth."../../templates/$met_skin_user/lang/language_$langmark.ini"; 
	//if(!is_writable($depth."../../templates/".$met_skin_user."/lang/"))@chmod($depth."../../templates/".$met_skin_user."/lang/", 0777); 
	if(!file_exists($newfile)){  
		if (!copy($oldfile,   $newfile))metsave('-1',$lang_langcopyfile);
	}
	return $sun_re;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>