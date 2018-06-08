<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
function dump($vars, $label = '', $return = false){
    if (ini_get('html_errors')){
        $content = "<pre>\n";
        if ($label != '') {
            $content .= "<strong>{$label} :</strong>\n";
        }
        $content .= htmlspecialchars(print_r($vars, true));
        $content .= "\n</pre>\n";
    } else {
        $content = $label . " :\n" . print_r($vars, true);
    }
    if ($return) { return $content; }
    echo $content;
    return null;
}
function metfiletype($qz){
	$list=explode(".",$qz);
	$metinfo=$list[count($list)-1];
	return $metinfo;
}
/*去除空格*/
function metdetrim($str){
    $str = trim($str);
    $str = ereg_replace("\t","",$str);
    $str = ereg_replace("\r\n","",$str);
    $str = ereg_replace("\r","",$str);
    $str = ereg_replace("\n","",$str);
    $str = ereg_replace(" ","",$str);
    return trim($str);
}
	function inject_check($sql_str) {
  if(strtoupper($sql_str)=="UPDATETIME" ){
  return eregi('select|insert|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str);   
  }else{	
  return eregi('select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str);   
  }     
} 
/*post和get变量变成普通变量，防注入。*/
function daddslashes($string, $force = 0,$metinfo) {
global $met_sqlinsert;
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	if(!is_array($string)){
		$string_old = $string;
		$string = str_ireplace("\"","/",$string);
		$string = str_ireplace("'","/",$string);
		$string = str_ireplace("*","/",$string);
		$string = str_ireplace("~","/",$string);
		$string = str_ireplace("select", "\sel\ect", $string);
		$string = str_ireplace("insert", "\ins\ert", $string);
		$string = str_ireplace("update", "\up\date", $string);
		$string = str_ireplace("delete", "\de\lete", $string);
		$string = str_ireplace("union", "\un\ion", $string);
		$string = str_ireplace("into", "\in\to", $string);
		$string = str_ireplace("load_file", "\load\_\file", $string);
		$string = str_ireplace("outfile", "\out\file", $string);
		$string_html=$string;
		$string = strip_tags($string);
		if($string_html!=$string){
			$string='';
		}
		$string = str_replace("%", "\%", $string);     //   	
		/*
		if(strlen($string_html)!=strlen($string)){
			$reurl="http://".$_SERVER["HTTP_HOST"];
			echo("<script type='text/javascript'> alert('Submitted information is not legal!'); location.href='$reurl'; </script>");
			die("Parameter Error！");
		}
		*/
		if(strlen($string_old)!=strlen($string)&&$met_sqlinsert){
			$reurl="http://".$_SERVER["HTTP_HOST"];
			echo("<script type='text/javascript'> alert('Submitted information is not legal!'); location.href='$reurl'; </script>");
			die("Parameter Error！");
		}
		$string = trim($string);
	}
	if($id!=""){
	if(!is_numeric($id)){
	$reurl="http://".$_SERVER["HTTP_HOST"];
	echo("<script type='text/javascript'> alert('Parameter Error！'); location.href='$reurl'; </script>");
	die("Parameter Error！");
	}}
	if($class1!=""){
	if(!is_numeric($class1)){
	$reurl="http://".$_SERVER["HTTP_HOST"];
	echo("<script type='text/javascript'> alert('Parameter Error！'); location.href='$reurl'; </script>");
	die("Parameter Error！");
	}}
	if($class2!=""){
	if(!is_numeric($class2)){
	$reurl="http://".$_SERVER["HTTP_HOST"];
	echo("<script type='text/javascript'> alert('Parameter Error！'); location.href='$reurl'; </script>");
	die("Parameter Error！");
	}}
	if($class3!=""){
	if(!is_numeric($class3)){
	$reurl="http://".$_SERVER["HTTP_HOST"];
	echo("<script type='text/javascript'> alert('Parameter Error！'); location.href='$reurl'; </script>");
	die("Parameter Error！");
	}}   
	return $string;
}
/*载入模板*/
function template($template,$EXT="html"){
	global $met_skin_user,$skin,$dataoptimize_html;
	$EXT=($dataoptimize_html=="")?$EXT:$dataoptimize_html;
	if(empty($skin)){
	    $skin = $met_skin_user;
	}
	unset($GLOBALS[con_db_id],$GLOBALS[con_db_pass],$GLOBALS[con_db_name]);
	$path = ROOTPATH."templates/$skin/$template.$EXT";
	
	!file_exists($path) && $path=ROOTPATH."public/ui/met/$template.html";
	return  $path;
}
/*全站静态页面打包时，文件保存地址。*/
function htmpacks($murl,$adminfile){
	global $classnow;
		$met_htmpack_url=$adminfile.'/databack/htmpack/';
		$dir=getcwd();
		$dir=basename($dir);
		$pack_url = $met_htmpack_url;
		$murlb=$murl;
		$murl = $pack_url.$murl;
		if($classnow!=10001)$murl = '../'.$pack_url.$dir.'/'.$murlb;
		$url.=$_SERVER["PHP_SELF"];
		$mlist = explode('/',$url);
		$mnum = count($mlist)-2;
		$remurl = $murl;
		metnew_dir($murl);
		return $remurl;
}
/*新建目录*/
function metnew_dir($pathf){
	global $lang_modFiledir;
	$dirs = explode('/',$pathf);
	$num  = count($dirs) - 1;
	for($i=0;$i<$num;$i++){
		$dirpath .= $i==0?$dirs[$i].'/':$dirs[$i].'/';
		if(!is_dir($dirpath)){
			mkdir($dirpath);
			//if(!chmod($dirpath,0777))die($lang_modFiledir);
		}
	}
}
function unescape($str){ 
    $ret = ''; 
    $len = strlen($str); 

    for ($i = 0; $i < $len; $i++) { 
        if ($str[$i] == '%' && $str[$i+1] == 'u') { 
            $val = hexdec(substr($str, $i+2, 4)); 

            if ($val < 0x7f) $ret .= chr($val); 
            else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f)); 
            else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f)); 

            $i += 5; 
        }else if ($str[$i] == '%') { 
            $ret .= urldecode(substr($str, $i, 3)); 
            $i += 2; 
        } 
        else $ret .= $str[$i]; 
    } 
    return $ret; 
}

/*页面输出*/
function footer(){	
	global $output,$db,$met_htmtype,$html_filename,$metinfonow,$met_member_force,$met_webhtm,$htmpack,$lang_htmcreate,$lang_htmsuccess,$index_url,$indexy,$met_chtmtype,$adminfile;
	$output = str_replace(array('<!--<!---->','<!---->','<!--fck-->','<!--fck','fck-->','',"\r",substr($admin_url,0,-1)),'',ob_get_contents());
	$output=trim($output,"\n");
	$db->close();	
	ob_end_clean();	
	if($metinfonow==$met_member_force and $met_webhtm){
		$html_filename=str_replace("\\",'',$html_filename);
		$html_filename=unescape($html_filename);
		$html_filename.=$indexy=='index'?$met_htmtype:($indexy==1?$met_chtmtype:$met_htmtype);
		if($htmpack)$html_filename = htmpacks($html_filename,$adminfile);
		$newhtm = explode('/',$html_filename);
		$newhtm = $newhtm[count($newhtm)-1];
		if(stristr(PHP_OS,"WIN")){
			$html_filename=@iconv("utf-8","GBK",$html_filename);
		}
		$handle = fopen($html_filename,"w");
		if (!is_writable($html_filename)){
			$jsok=2;
		}elseif(!fwrite($handle,$output)){
			$jsok=1;
		}else{
			$jsok=0;
		}
		fclose($handle);  
		echo $jsok;
	}else{
	   echo $output;
	}
	exit();
}
/*手机页面输出*/
function wapfooter(){
	global $output,$db,$html_filename,$metwaphtm;
	$output = str_replace(array('<!--<!---->','<!---->','<!--fck-->','<!--fck','fck-->','',"\r",substr($admin_url,0,-1)),'',ob_get_contents());
	$output=trim($output,"\n");
	$db->close();	
	ob_end_clean();	
	echo $output;
	exit;
}
/*前台跳转*/
function okinfo($url = '../site/sysadmin.php',$langinfo){
	if($langinfo){
		echo("<script type='text/javascript'> alert('$langinfo'); location.href='$url'; </script>");
	}
	else{	
		header('HTTP/1.1 404 Not Found');	    
		echo("<script type='text/javascript'>location.href='$url'; </script>");
	}
	exit();
}
/*字段权限控制代码加密后（加密后可用URL传递）*/
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0){

        $ckey_length = 4;  
        $key = md5($key ? $key : UC_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }

    }
/*和authcode配合使用*/
function codetra($content,$codetype) {
	if($codetype==1){
		$content = str_replace('+','metinfo',$content);
	}else{
		$content = str_replace('metinfo','+',$content);
	}
	return $content;
}
/*内容分页*/
function pageBreak($content,$type){ 
	$content = substr($content,0,strlen($content)-6); 
    $pattern = "/<div style=\"page-break-after: always;?\">\s*<span style=\"display: none;?\">&nbsp;<\/span>\s*<\/div>/";      
	$strSplit = preg_split($pattern, $content); 
	$count = count($strSplit); 
	if($type)return $count;
	$outStr = ""; 
	$i = 1; 
	if ($count > 1 ) { 
	$outStr = "<div id='page_break'>"; 
	foreach($strSplit as $value) { 
	if ($i <= 1) { 
	$value=substr($value,5);
	$outStr .= "<div id='page_{$i}'>{$value}</div>"; 
	} else { 
	$outStr .= "<div id='page_$i' class='collapse'>$value</div>"; 
	} 
	$i++; 
	} 

	$outStr .= "<div class='num'>"; 
	for ($i = 1; $i <= $count; $i++) { 
	$outStr .= "<li>$i</li>"; 
	} 
	$outStr .= "</div></div>"; 
	return $outStr; 
	} else { 
	return $content; 
	} 
} 

/*内容页面容热门标签替换和内容分页*/
function contentshow($content) {
global $lang_PagePre,$lang_PageNext,$navurl,$index,$lang;
global $met_atitle,$met_alt;
$str=met_cache('str_'.$lang.'.inc.php');
if(!$str){$str=cache_str();}
foreach ($str as $key=>$val){
	$val[3]=html_entity_decode($val[0],ENT_QUOTES,'UTF-8');
	$val[3]=str_replace(array('\\','/','.','$','^','*','(',')','-','['.']'.'{','}','|','?','+'),array('\\\\','\/','\.','\$','\^','\*','\(','\)','\-','\['.'\]'.'\{','\}','\|','\?','\+'),$val[3]);
	if($val[2]!=0){
		$tmp1 = explode("<",$content);
		$num=$val[2];
		foreach ($tmp1 as $key=>$item){
			$tmp2 = explode(">",$item);
			if (sizeof($tmp2)>1&&strlen($tmp2[1])>0) {
				if (substr($tmp2[0],0,1)!="a" && substr($tmp2[0],0,1)!="A"){
					$valnum=substr_count($tmp2[1],$val[0]);
					if($num-$valnum>=0){
						$num=$num-$valnum;
					}
					else{
						$valnum=$num;
						$num=0;
					}
					$tmp2[1] = preg_replace("/".$val[3]."/",$val[1],$tmp2[1],$valnum);
					$tmp1[$key] = implode(">",$tmp2);
				}
			}
		}
		$content = implode("<",$tmp1);
	}
}
$tmp1 = explode("<",$content);
foreach ($tmp1 as $key=>$item){
	$tmp2 = explode(">",$item);
	if (substr($tmp2[0],0,1)=="a" || substr($tmp2[0],0,1)=="A"){
		$tmp2[0]=str_replace(array("title=''","title=\"\""),'',$tmp2[0]);
		if(!strpos($tmp2[0],'title')){
			$tmp2[0].=" title=\"$met_atitle\"";
			$tmp1[$key] = implode(">",$tmp2);
		}
	}
	if (substr($tmp2[0],0,3)=="img" || substr($tmp2[0],0,3)=="IMG"){
		$tmp2[0]=str_replace(array("alt=''","alt=\"\""),'',$tmp2[0]);
		if(!strpos($tmp2[0],'alt')){
			$tmp2[0].=" alt=\"$met_alt\"";
			$tmp1[$key] = implode(">",$tmp2);
		}
	}
}
$content = implode("<",$tmp1);
if(pageBreak($content,1)>1){
	$content = pageBreak($content);
	$content.="<link rel='stylesheet' type='text/css' href='{$navurl}public/css/contentpage.css' />\n"; 
	$content.="
<script type='text/javascript'>
$(document).ready(function(){
	$('#page_break .num li:first').addClass('on');
	$('#page_break .num li').click(function(){
		$('#page_break').find(\"div[id^='page_']\").hide();
		if ($(this).hasClass('on')) {
			$('#page_break #page_' + $(this).text()).show();
		} else {
			$('#page_break').find('.num li').removeClass('on'); 
			$(this).addClass('on'); 
			$('#page_break').find('#page_' + $(this).text()).show(); 
		} 
	});
});
</script>
	"; 
}
if($content=='<div><div id="metinfo_additional"></div></div>')$content='';
return $content;
}

/*删除文件*/
function file_unlink($file_name) {
	if(file_exists($file_name)) {
		//@chmod($file_name,0777);
		$area_lord = @unlink($file_name);
	}
	return $area_lord;
}


/*列表页排序方式*/
function list_order($listid){
switch($listid){
case '0':
$list_order=" order by top_ok desc,no_order desc,updatetime desc,id desc";
return $list_order;
break;

case '1':
$list_order=" order by top_ok desc,no_order desc,updatetime desc,id desc";
return $list_order;
break;

case '2':
$list_order=" order by top_ok desc,no_order desc,addtime desc,id desc";
return $list_order;
break;

case '3':
$list_order=" order by top_ok desc,no_order desc,hits desc,id desc";
return $list_order;
break;

case '4':
$list_order=" order by top_ok desc,no_order desc,id desc";
return $list_order;
break;

case '5':
$list_order=" order by top_ok desc,no_order desc,id asc ";
return $list_order;
break;
}
}
/*上一条下一条排序*/
function pn_order($list_order,$news){
switch($list_order){
case '0':
$pn_order[0]="(updatetime > '$news[updatetime_order]') order by updatetime asc";
$pn_order[1]="(updatetime < '$news[updatetime_order]') order by updatetime desc";

$pn_order[2]="(updatetime = '$news[updatetime_order]') order by id desc";
return $pn_order;
break;

case '1':
$pn_order[0]="(updatetime > '$news[updatetime_order]') order by updatetime asc";
$pn_order[1]="(updatetime < '$news[updatetime_order]') order by updatetime desc";

$pn_order[2]="(updatetime = '$news[updatetime_order]') order by id desc";
return $pn_order;
break;

case '2':
$pn_order[0]="(addtime > '$news[addtime]') order by addtime asc";
$pn_order[1]="(addtime < '$news[addtime]') order by addtime desc";

$pn_order[2]="(addtime = '$news[addtime]') order by id desc";
return $pn_order;
break;

case '3':
$pn_order[0]="(hits > '$news[hits]') order by hits asc";
$pn_order[1]="(hits < '$news[hits]') order by hits desc";

$pn_order[2]="(hits = '$news[hits]') order by id desc";
return $pn_order;
break;

case '4':
$pn_order[0]="id > '$news[id]' order by id asc";
$pn_order[1]="id < '$news[id]' order by id desc";
return $pn_order;
break;

case '5':
$pn_order[0]="(id < '$news[id]') order by id desc";
$pn_order[1]="(id > '$news[id]') order by id asc";
return $pn_order;
break;
}
}
/*转UTF-8码*/
function utf8Substr($str, $from, $len) 
{
if(mb_strlen($str,'utf-8')>intval($len)){
return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'. 
'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s', 
'$1',$str).".."; 
}else{
return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'. 
'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s', 
'$1',$str); 
}
}
/*搜索关键词*/
function get_keyword_str($str,$keyword,$getstrlen,$searchtype,$type){
	$str=str_ireplace('<p>','&nbsp;',$str);
	$str=str_ireplace('</p>','&nbsp;',$str);
	$str=str_ireplace('<br />','&nbsp;',$str);
	$str=str_ireplace('<br>','&nbsp;',$str);
	if($type){
		$searchtype=$searchtype!=2?1:0;
	}else{
		$searchtype=$searchtype!=1?1:0;
	}
	if(mb_strlen($str,'utf-8')> $getstrlen){
		$strlen = mb_strlen($keyword,'utf-8');
		if(function_exists('mb_stripos')){
			$strpos = mb_stripos($str,$keyword,0,'utf-8');
		}else{
			$strpos = mb_strpos($str,$keyword,0,'utf-8');
		}	
		$halfStr = intval(($getstrlen-$strlen)/2);
		if($strpos!=""){
			if($strpos>=$halfStr){
				$str = mb_substr($str,($strpos - $halfStr),$halfStr,'utf-8').$keyword.mb_substr($str,($strpos + $strlen),$halfStr,'utf-8');
			}else{
				$str = mb_substr($str,0,$strpos,'utf-8').$keyword.mb_substr($str,($strpos + $strlen),($halfStr*2),'utf-8');
			}	
		}else{
			$str = mb_substr($str,0,$getstrlen,'utf-8');
		}
		$metinfo=$str.'...';
		if($searchtype){
			$metinfo=str_ireplace($keyword,'<em style="font-style:normal;">'.$keyword.'</em>',$str).'...';
		}
		return $metinfo;
	}else{
		$metinfo=$str;
		if($searchtype){
			$metinfo=str_ireplace($keyword,'<em style="font-style:normal;">'.$keyword.'</em>',$str);
		}
		return $metinfo;
	}
	
}
/*模板未授权*/
function authtemp($code){
global $au_site,$met_weburl;
if(function_exists(authcode))
run_strtext(authcode($code,DECODE,md5("metinfo")));
$au_site=explode("|",$au_site);
foreach($au_site as $val)
{
	if(stristr($met_weburl,$val))
	{
		return;
	}
}
var_export("-->");
okinfo("http://www.metinfo.cn","{$met_weburl}未授权使用此模板或已经过期! Powered by MetInfo");exit();
}
/*把字符串当成代码运行*/
function run_strtext($code){
    return eval($code);
}
/*会员模板加载*/
function templatemember($template,$EXT="html"){
	if(empty($skin)){
	    $skin ="met";
	}
	unset($GLOBALS[con_db_id],$GLOBALS[con_db_pass],$GLOBALS[con_db_name]);
	$path = ROOTPATH."member/templates/$skin/$template.$EXT";
	!file_exists($path) && $path=ROOTPATH."member/templates/met/$template.$EXT";
	return  $path;
}
/*手机内容替换*/
function wap_replace($text,$tag,$tag1,$tag2){
	$text = preg_replace("/<(\/?$tag.*?)>/si","",$text);
	if($tag1){
		$cndes=explode('|',$tag1);
		for($i=0;$i<count($cndes);$i++){
			if($cndes[$i]!=''){
				$text=preg_replace("/<(".$cndes[$i].".*?)>(.*?)<(\/".$cndes[$i].".*?)>/si","",$text);
				$text=preg_replace("/<(\/?".$cndes[$i].".*?)> /si","",$text);
			}
		}
	}
	if($tag2){
		$cndes=explode('|',$tag2);
		for($i=0;$i<count($cndes);$i++){
			if($cndes[$i]!=''){
				$text=preg_replace("/<(".$cndes[$i].".*?)>/si","<$cndes[$i]>",$text);
				$text=preg_replace("/<(\/".$cndes[$i].".*?)>/si","</$cndes[$i]>",$text);
			}
		}
	}
	return $text;
}
/*手机模板加载*/
function waptemplate($template,$EXT="html"){
	if(empty($skin)){
	    $skin ="met";
	}
	unset($GLOBALS[con_db_id],$GLOBALS[con_db_pass],$GLOBALS[con_db_name]);
	$path = ROOTPATH."wap/templates/$skin/$template.$EXT";
	!file_exists($path) && $path=ROOTPATH."wap/templates/met/$template.$EXT";
	return  $path;
}
/*会员输出*/
function footermember(){
	$output = str_replace(array('<!--<!---->','<!---->','<!--fck-->','<!--fck','fck-->','',"\r",substr($admin_url,0,-1)),'',ob_get_contents());
    ob_end_clean();
    echo $output; unset($output);
	mysql_close();
	exit;
}
/*图片显示大小*/
function met_imgxy($xy,$module){
	global $met_newsimg_x,$met_newsimg_y,$met_productimg_x,$met_productimg_y,$met_imgs_x,$met_imgs_y;
	switch($module){
		case 'news':
			$met_imgxy=$xy==1?$met_newsimg_x:$met_newsimg_y;
			break;
		case 'product':
			$met_imgxy=$xy==1?$met_productimg_x:$met_productimg_y;
		    break;
		case 'img':
			$met_imgxy=$xy==1?$met_imgs_x:$met_imgs_y;
		    break;
	}
	return $met_imgxy;
}
/*更具模块编号返回表名称*/
function metmodname($module){
	$metmodname='';
	switch($module){
		case 1:
			$metmodname='about';
			break;
		case 2:
			$metmodname='news';
			break;
		case 3:
			$metmodname='product';
		    break;
		case 4:
			$metmodname='download';
		    break;
		case 5:
			$metmodname='img';
		    break;
		case 6:
			$metmodname='job';
		    break;
		case 100:
			$metmodname='product';
		    break;
		case 101:
			$metmodname='img';
		    break;
	}
	return $metmodname;
}
/*手机跳转*/
function wapjump(){
	global $met_wap_tpa,$met_wap_tpb,$met_wap_url,$met_wap;
	$Loaction = 'wap/';
	if($met_wap_tpa==1){
		$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
		if($_SERVER['HTTP_USER_AGENT']){
			$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile|wap|Android|ucweb)/i";
			if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap')){
				if (!empty($Loaction)){
					header("Location: $Loaction\n");
					exit;
				}
			}
		}
	}
	if($met_wap_tpb==1){
		$localurl="http://";
		$localurl.=$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
		$localurl=dirname($localurl);
		if(substr($localurl,-1,1)!="/")$localurl.="/";
		if(strstr($localurl,$met_wap_url)){
			header("Location: $Loaction\n");
			exit;
		}
	}
}
function imgxytype($list,$type){
	global $met_newsimg_x,$met_newsimg_y,$met_productimg_x,$met_productimg_y,$met_imgs_x,$met_imgs_y;
	$lists=array();
	foreach($list as $key=>$val){
		switch($val['module']){
			case 2:
				$val['img_x']=$met_newsimg_x;
				$val['img_y']=$met_newsimg_y;
			break;
			case 3:
				$val['img_x']=$met_productimg_x;
				$val['img_y']=$met_productimg_y;
			break;
			case 5:
				$val['img_x']=$met_imgs_x;
				$val['img_y']=$met_imgs_y;
			break;
		}
		$lists[$val[$type]]=$val;
	}
	return $lists;
}
//获取当前页面URL
function request_uri(){
    $pageURL='http';
    if($_SERVER["HTTPS"]=="on")
    {
        $pageURL.="s";
    }
    $pageURL.="://";

    if($_SERVER["SERVER_PORT"]!="80")
    {
        $pageURL.=$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    }
    else
    {
        $pageURL.=$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return$pageURL;
}
function tmpcentarr($cd){
	global $class_list,$module_listall;
	$hngy5=explode('-',$cd);
	if($hngy5[1]=='cm')$metinfo=$class_list[$hngy5[0]];
	if($hngy5[1]=='md')$metinfo=$module_listall[$hngy5[0]][0];
	return $metinfo;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>