<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once 'common.inc.php';
$packurl = 'http://'.$_SERVER['HTTP_HOST'].'/';
foreach($met_langok as $key=>$val){
	$indexmark=($val[mark]==$met_index_type)?"index.":"index_".$val[mark].".";
	$val[met_weburl]=$val[met_weburl]<>""?$val[met_weburl]:$met_weburl;
	$val[met_htmtype]=$val[met_htmtype]<>""?$val[met_htmtype]:$met_htmtype;
	if($val[useok]){
		$met_index_url[$val[mark]]=$val[met_webhtm]?$val[met_weburl].$indexmark.$val[met_htmtype]:$val[met_weburl]."index.php?lang=".$val[mark];
		if($met_pseudo)$met_index_url[$val['mark']] = $val['met_weburl'].'index-'.$val['mark'].'.html';
		if($htmpack){
			$navurls = $index=='index'?'':'../';
			$met_index_url[$val['mark']]=$navurls.$indexmark.$val['met_htmtype'];
		}
		if($val[mark]==$met_index_type)$met_index_url[$val[mark]]=$val[met_weburl];
		if($htmpack && $val[mark]==$met_index_type){
			$met_index_url[$val[mark]]=$navurls;
		}
		if($val[link]!="")$met_index_url[$val[mark]]=$val[link];
		if(!strstr($val[flag], 'http://')){
			$navurls = $index=='index'?'':'../';
			if($index=="index"&&strstr($val[flag], '../')){
				$met_langlogoarray=explode("../",$val[flag]);
				$val[flag]=$met_langlogoarray[1];
			}
			if(!strstr($val[flag], 'http://')&&!strstr($val[flag], 'public/images/flag/'))$val[flag]=$navurls.'public/images/flag/'.$val[flag];
		}
		$met_langok[$val[mark]]=$val;
	}
}
//2.0
$index_c_url=$met_index_url[cn];
$index_e_url=$met_index_url[en];
$index_o_url=$met_index_url[other];
//2.0
$searchurl           =$met_weburl."search/search.php?lang=".$lang;
$file_basicname      =ROOTPATH."lang/language_".$lang.".ini";
$file_name           =ROOTPATH."templates/".$met_skin_user."/lang/language_".$lang.".ini";
$str="";
if(!file_exists(ROOTPATH.'cache/lang_'.$lang.'.php')){
	$query="select * from $met_language where lang='$lang' and site='0' and array!='0'";
	$result= $db->query($query);
	while($listlang= $db->fetch_array($result)){
		$name = 'lang_'.$listlang['name'];
		$$name= trim($listlang['value']);
		$str.='$'."{$name}='".str_replace(array('\\',"'"),array("\\\\","\\'"),trim($listlang['value']))."';";
	}
	$str="<?php\n".$str."\n?>";
	file_put_contents(ROOTPATH.'cache/lang_'.$lang.'.php',$str);
}else{
	require_once ROOTPATH.'cache/lang_'.$lang.'.php';
}
if(!file_exists($file_name)){
  if(file_exists(ROOTPATH."templates/".$met_skin_user.'/lang/language_cn.ini')){
 $file_name           =ROOTPATH."templates/".$met_skin_user.'/lang/language_cn.ini';
 }else{
 $file_name           =ROOTPATH."templates/".$met_skin_user.'/lang/language_china.ini';
 }}
if(file_exists($file_name)){
$fp = @fopen($file_name, "r") or die("Cannot open $file_name");
while ($conf_line = @fgets($fp, 1024)){    
if(substr($conf_line,0,1)=="#"){   
$line = ereg_replace("#.*$", "", $conf_line);
}else{
$line = $conf_line;
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
$name = 'lang_'.daddslashes(trim($name),1,'metinfo');
$$name= trim($value);
}
fclose($fp) or die("Can't close file $file_name");
}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>