<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
function skin_desc($txt,$type){
	$metcms=$type?$txt:'';
	if(strstr($txt,'$DESC$')){
		$metcmsx=explode('$DESC$',$txt);
		$metcms=$type?$metcmsx[0]:$metcmsx[1];
	}
	return $metcms;
}
function linkrules($listc){
	global $met_weburl,$lang;
		$modulename[1] = array(0=>'show',1=>'show');
		$modulename[2] = array(0=>'news',1=>'shownews');
		$modulename[3] = array(0=>'product',1=>'showproduct');
		$modulename[4] = array(0=>'download',1=>'showdownload');
		$modulename[5] = array(0=>'img',1=>'showimg');
		$modulename[6] = array(0=>'job',1=>'showjob');
		$modulename[7] = array(0=>'message',1=>'index');
		$modulename[8] = array(0=>'feedback',1=>'index');	
		$modulename[9] = array(0=>'link',1=>'index');	
		$modulename[10]= array(0=>'member',1=>'index');	
		$modulename[11]= array(0=>'search',1=>'search');	
		$modulename[12]= array(0=>'sitemap',1=>'sitemap');
		$modulename[100]= array(0=>'product',1=>'showproduct');
		$modulename[101]= array(0=>'img',1=>'showimg');
		$urltop = $met_weburl.$listc['foldername'].'/';
		$langmark='lang='.$lang;
		switch($listc['module']){
			default:
				$urltop2 = $urltop.$modulename[$listc['module']][0].'.php?'.$langmark;
				if($listc['releclass']){
					$listc['url']=$urltop2."&class1=".$listc['id'];
				}else{
					$classtypenum=$cache_column[$listc['bigclass']]['releclass']?$listc['classtype']-1:$listc['classtype'];
					switch($classtypenum){
						case 1:
						$listc['url']=$urltop2."&class1=".$listc['id'];
						break;
						case 2:
						$listc['url']=$urltop2."&class2=".$listc['id'];
						break;
						case 3:
						$listc['url']=$urltop2."&class3=".$listc['id'];
						break;
					}
				}
				break;
			case 1:
				if($listc['isshow']!=0){
					$listc['url']=$urltop.'show.php?'.$langmark.'&id='.$listc['id'];
				}
				break;
			case 6:
				$listc['url']=$urltop.'index.php?'.$langmark;
				break;
			case 7:
				$listc['url']=$urltop.'index.php?'.$langmark;
				break;
			case 8:
				$listc['url']=$urltop.'index.php?'.$langmark.'&id='.$listc['id'];
				break;
			case 9:
			case 10:
			case 12:
				$listc['url']=$urltop.'index.php?'.$langmark;
				break;	
			case 11:
				$listc['url']=$urltop.'index.php?'.$langmark;
				break;
		}
	return $listc['url'];
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
/*验证邮箱地址*/
function is_email($user_email){
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false){
        if (preg_match($chars, $user_email)){
            return true;
        }
        else{
            return false;
        }
    }else{
        return false;
    }
}
/*数组输出*/
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
/*编码转换*/
function is_utf8($liehuo_net){
	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$liehuo_net) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$liehuo_net) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$liehuo_net) == true){
		return true;
	}else{
		return false;
	}
}
/*截取字符串长度*/
function utf8Substr($str, $from, $len){
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
/*POST变量转换*/
function daddslashes($string, $force = 0 ,$sql_injection =0){
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
	if((SQL_DETECT!=1 || $sql_injection==1)&&!is_array($string)){
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
		$string = strip_tags($string);
		$string = str_replace("%", "\%", $string);     //   
	}
	return $string;
}
/*模板加载*/
function template($template,$EXT="html"){
	global $met_skin_name,$skin;
	if(empty($skin)){
	    $skin = $met_skin_name;
	}
	unset($GLOBALS[con_db_id],$GLOBALS[con_db_pass],$GLOBALS[con_db_name]);
	$path = ROOTPATH_ADMIN."templates/$skin/$template.$EXT";
	!file_exists($path) && $path=ROOTPATH_ADMIN."templates/met/$template.$EXT";
	return  $path;
}
function template_app($template,$EXT="html"){
	unset($GLOBALS[con_db_id],$GLOBALS[con_db_pass],$GLOBALS[con_db_name]);
	$path = ROOTPATH_ADMIN."app/$template.$EXT";
	return  $path;
}

/*页面输出*/
function footer(){
	global $output;
	$output = str_replace(array('<!--<!---->','<!---->','<!--fck-->','<!--fck','fck-->','',"\r",substr($admin_url,0,-1)),'',ob_get_contents());
    ob_end_clean();
	echo $output;
	mysql_close();
	exit;
}
/*删掉多余页面*/
function delnull($htm){
	$htmjs=$htm;
	$htmjs_array=explode('$|$',$htmjs);
	$htmjs='';
	foreach($htmjs_array as $key=>$val){
		if($val!=''){
			$htmjs.=$val.'$|$';
		}
	}
	$htmjs=trim($htmjs,'$|$');
	return $htmjs;
}
/*页面跳转*/
function metsave($url,$text,$depth,$htm,$gent,$prent){
global $db,$met_config,$lang;
	$htm=$htm!=''?delnull($htm):'';
	$url=$url=='-1'?$url:urlencode($url);
	$text=urlencode($text);
	$gent=urlencode($gent);
	if($htm){
		$query = "INSERT INTO $met_config SET name='metsave_html_list',value='{$htm}',lang='{$lang}'";
		$db->query($query);
		$htm=mysql_insert_id();
	}
	$url=$depth."../include/turnover.php?geturl={$url}&text={$text}&gent={$gent}&hml={$htm}&prent={$prent}";
	echo("<script type='text/javascript'>location.href='{$url}';</script>");
	exit;
}
/*alert页面跳转*/
function okinfo($url,$langinfo){
	echo("<script type='text/javascript'> alert('$langinfo'); location.href='$url'; </script>");
	exit;
}
/*主导航显示-根据导航类型返回代码*/
function navdisplay($nav){
global $lang_funNav1,$lang_funNav2,$lang_funNav3,$lang_funNav4;
	switch($nav){
		case '0':$nav=$lang_funNav1;break;
		case '1':$nav="<font class='red'>$lang_funNav2</font>";break;
		case '2':$nav="<font class='blue'>$lang_funNav3</font>";break;
		case '3':$nav="<font class='green'>$lang_funNav4</font>";break;
	}
	return $nav;
}
/*权限设置-根据权限返回代码*/
function accessdisplay($access){
global $lang_access1,$lang_access2,$lang_access3,$lang_access0;
	switch($access){
		case '1':$access=$lang_access1;break;
		case '2':$access=$lang_access2;break;
		case '3':$access=$lang_access3;break;
		default :$access=$lang_access0;break;
	}
	return $access;
}
/*模块设置-更具模块编号返回模块名*/
function module($module){
global $lang_modout,$lang_mod1,$lang_mod2,$lang_mod3,$lang_mod4,$lang_mod5,$lang_mod6,$lang_mod7,$lang_mod8,$lang_mod9,$lang_mod10,$lang_mod11,$lang_mod12,$lang_mod100,$lang_mod101;
switch($module){
case '0':
$module="<font color=red>$lang_modout</font>";
break;
case '1':
$module=$lang_mod1;
break;
case '2':
$module=$lang_mod2;
break;
case '3':
$module=$lang_mod3;
break;
case '4':
$module=$lang_mod4;
break;
case '5';
$module=$lang_mod5;
break;
case '6':
$module=$lang_mod6;
break;
case '7':
$module=$lang_mod7;
break;
case '8':
$module=$lang_mod8;
break;
case '9':
$module=$lang_mod9;
break;
case '10':
$module=$lang_mod10;
break;
case '11':
$module=$lang_mod11;
break;
case '12':
$module=$lang_mod12;
break;
case '100':
$module=$lang_mod100;
break;
case '101':
$module=$lang_mod101;
break;
}

return $module;
}
/*删除文件*/
function file_unlink($file_name) {
	if(stristr(PHP_OS,"WIN")){
		$file_name=@iconv("utf-8","gbk",$file_name);
	}
	if(file_exists($file_name)) {
		//@chmod($file_name,0777);
		$area_lord = @unlink($file_name);
	}
	return $area_lord;
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
/*静态页面生成*/
function createhtm($fromurl,$filename,$htmpack,$indexy=0){
	global $lang_funFile,$lang_funTip1,$lang_funCreate,$lang_funFail,$lang_funOK,$met_member_force,$met_member_use,$met_sitemap_xml,$met_weburl,$adminfile;
	if($met_member_use!=0)$fromurl=(strstr($fromurl,'?'))?$fromurl."&metmemberforce=".$met_member_force:$fromurl."?metmemberforce=".$met_member_force;
	if($met_sitemap_xml==1&&strstr($fromurl,'sitemap.php'))$fromurl=(strstr($fromurl,'?'))?$fromurl."&htmxml=".$met_member_force:$fromurl."?htmxml=".$met_member_force;
	$fromurl.="&html_filename=".$filename."&metinfonow=$met_member_force";
	if($htmpack)$fromurl.='&htmpack='.$htmpack.'&adminfile='.$adminfile;
	if($indexy)$fromurl.='&indexy='.$indexy;
	return $fromurl;
}

/*列表页面排序*/
function list_order($listid){
	switch($listid){
		case '0':
		$list_order=" order by top_ok desc,no_order desc,updatetime desc";
		return $list_order;
		break;

		case '1':
		$list_order=" order by top_ok desc,no_order desc,updatetime desc";
		return $list_order;
		break;

		case '2':
		$list_order=" order by top_ok desc,no_order desc,addtime desc";
		return $list_order;
		break;

		case '3':
		$list_order=" order by top_ok desc,no_order desc,hits desc";
		return $list_order;
		break;

		case '4':
		$list_order=" order by top_ok desc,no_order desc,id desc";
		return $list_order;
		break;

		case '5':
		$list_order=" order by top_ok desc,no_order desc,id";
		return $list_order;
		break;
		
		default :
		$list_order=" order by top_ok desc,no_order desc,updatetime desc";
		return $list_order;
		break;
	}
}

/*删除HTML代码*/
function dhtmlchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
		str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}
/*判断代码是否为空*/
function isblank($str) {
	if(eregi("[^[:space:]]",$str)) { return 0; } else { return 1; }
	return 0;
}
$php_text=$db->get_one("SELECT * FROM $met_mysql where id=1");
/*代码加密后用URL传递*/
 function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

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
/*首页生成*/
function indexhtm($htmway=0,$htmpack=0){
	global $lang,$met_webhtm,$met_htmty,$met_htmway,$met_index_type;
	$met_htmway=$htmway?0:$met_htmway;
	if($met_webhtm!=0 && $met_htmway==0){
		$fromurl="index.php?lang=".$lang;
		$filename="index";
		$indexy = 'index';
		return createhtm($fromurl,$filename,$htmpack,$indexy);
	}
}
/*内容页HTML代码生成*/
function contenthtm($class1,$id,$phpfilename,$htmlname,$htmway=0,$folder,$addtime,$htmpack=0){
	global $lang,$met_webhtm,$met_htmpagename,$m_now_time,$met_column,$met_htmway,$met_class;
	$met_htmway=$htmway?0:$met_htmway;
	if($met_webhtm!=0 && $met_htmway==0){
		if($addtime!=""){
			$addtime     = date('Ymd',strtotime($addtime));
		}else{
			$addtime     = date('Ymd',$m_now_time);
		}
		if($folder!=""){
			$foldername=$folder;
		}else{
			$foldername=$met_class[$class1][foldername];
		}
		switch($met_htmpagename){
			case 0:
			$pagename=$phpfilename.$id;
			break;
			case 1:
			$pagename=$addtime.$id;
			break;
			case 2:
			$pagename=$foldername.$id;
			break;
		}
		$fromurl=$foldername."/".$phpfilename.".php?id=".$id."&lang=".$lang;
		if($htmlname<>''){
			$filename=$htmlname;
			$indexy = 1;
		}else{
			$filename=$pagename;
			$indexy = 0;
		}
		return createhtm($fromurl,$filename,$htmpack,$indexy);
	}
}
$php_text=explode('|',$php_text[data]);
/*模块HTML代码生成*/
function classhtm($class1,$class2,$class3,$htmway=0,$classtype=0,$htmpack=0){
	global $lang,$met_webhtm,$met_listhtmltype,$met_htmlistname,$m_now_time,$db,$met_class,$met_module,$metadmin,$met_index_type;
	global $met_column,$met_news,$met_product,$met_download,$met_img,$met_job,$met_message,$met_feedback,$met_htmway;
	global $met_news_list,$met_product_list,$met_download_list,$met_img_list,$met_job_list,$met_message_list,$met_feedback_list,$met_product_page;
	$met_htmway=$htmway?0:$met_htmway;
	if($met_webhtm==2 && $met_htmway==0){
		$class1_info=$met_class[$class1];
		switch($class1_info['module']){
			case 2:
				$tablename=$met_news;
				$pagesize=$met_news_list;
				$phpfilename="news";
				break;
			case 3:
				$tablename=$met_product;
				$pagesize=$met_product_list;
				$phpfilename="product";
				break;
			case 4:
				$tablename=$met_download;
				$pagesize=$met_download_list;
				$phpfilename="download";
				break;
			case 5:
				$tablename=$met_img;
				$pagesize=$met_img_list;
				$phpfilename="img";
				break;
			case 6:
				$tablename=$met_job;
				$pagesize=$met_job_list;
				$phpfilename="job";
				break;
			case 7:
				$tablename=$met_message;
				$pagesize=$met_message_list;
				$phpfilename="index";
				break;
			case 8:
				$tablename=$met_feedback;
				$pagesize=$met_feedback_list;
				$phpfilename="feedback";
				break;
		}
		$foldername=$class1_info['foldername'];
		switch($met_htmlistname){
			case 0:
				$pagename=$phpfilename.$id;
				break;
			case 1:
				$pagename=$foldername.$id;
				break;
		}
		if($class1_info[module]<6){
			$total_count = $db->counter($tablename, " where lang='".$lang."' and class1=".$class1." and (recycle='0' or recycle='-1')", "*");
		}elseif($class1_info[module]==7){
			$settings = parse_ini_file('../../config/message_'.$lang.'.inc.php');
			@extract($settings);
			$sqls=($met_fd_type==1)?" where lang='".$lang."' and readok='1'":"";
			$total_count = $db->counter($tablename, $sqls, "*");
		}else{
			$total_count = $db->counter($tablename, "where lang='".$lang."' ", "*");
		}
		$page_count=ceil($total_count/$pagesize);
		$page_count=$page_count?$page_count:1;
		$indexname=0;
		if($class1_info['classtype']==1||$class1_info['releclass']){
			$dbtxt=$class1_info['releclass']?2:1;
			$folderone=$db->get_one("SELECT * FROM $met_column WHERE foldername='$class1_info[foldername]' and id !='$class1_info[id]' and classtype='$dbtxt' and lang='$lang'");
			if(!$folderone){
				$indexname='index';
				if($class1_info['lang']!=$met_index_type)$indexname=0;
			}
		}
		if($class1_info['module']>5 && $class1_info['module']<13 && $class1_info['lang']==$met_index_type)$indexname='index';
		if($class1_info[module]==3 and ($classtype==0 or $classtype==1)){
			$classproduct_info=$met_module[100][0];
			if($classproduct_info[nav]){
				if($met_product_page){
					$fromurl="product/product.php?lang=".$lang;
					if($metadmin[pagename] and $classproduct_info[filename]<>""){
						$filename=$classproduct_info[filename];
						$indexy = 1;
					}else{
						$filename="product_".$classproduct_info[id]."_1";
						$indexy = 0;
					}
					$metrn .= createhtm($fromurl,$filename,$htmpack,$indexy).'$|$';
				}else{
					$total_countproduct = $db->counter($met_product, " where lang='".$lang."' ", "*");
					$page_countproduct=ceil($total_countproduct/$met_product_list);
					$page_countproduct=$page_countproduct?$page_countproduct:1;
					for($i=1;$i<=$page_countproduct;$i++){
						$fromurl="product/product.php?lang=".$lang."&page=".$i;
						if($metadmin['pagename'] and $classproduct_info['filename']<>""){
							$filename=$classproduct_info['filename']."_".$i;
							$indexy =1;
						}else{
							$filename="product_".$classproduct_info[id]."_".$i;
							$indexy =0;
						}
						$metrn .= createhtm($fromurl,$filename,$htmpack,$indexy).'$|$';
					}
				 }
			}
		}
		if($class1_info[module]==5 and ($classtype==0 or $classtype==1)){
			$classimg_info=$met_module[101][0];
			if($classimg_info[nav]){
				if($met_img_page){
					$fromurl="img/img.php?lang=".$lang;
					if($metadmin[pagename] and $classimg_info[filename]<>""){
						$filename=$classimg_info[filename]."_1";
						$indexy =1;
					}else{
						$filename="img_".$classimg_info[id]."_1";
						$indexy =0;
					}
					$metrn .= createhtm($fromurl,$filename,$htmpack,$indexy).'$|$';
				}else{
					$total_countimg = $db->counter($met_img, " where lang='".$lang."' ", "*");
					$page_countimg=ceil($total_countimg/$met_img_list);
					$page_countimg=$page_countimg?$page_countimg:1;
					for($i=1;$i<=$page_countimg;$i++){
						$fromurl="img/img.php?lang=".$lang."&page=".$i;
						if($metadmin[pagename] and $classimg_info[filename]<>""){
							$filename=$classimg_info[filename]."_".$i;
							$indexy = 1;
						}else{
							$filename="img_".$classimg_info[id]."_".$i;
							$indexy =0;
						}
						$metrn .= createhtm($fromurl,$filename,$htmpack,$indexy).'$|$';
					}
				}
			}
		}
		if($class1_info[module]==3 && $met_product_page && $class2)$page_count=1;
		if($class1_info[module]==5 && $met_img_page && $class2)$page_count=1;
		if($classtype==0 or $classtype==1){
			for($i=1;$i<=$page_count;$i++){
				$fromurl=$foldername."/".$phpfilename.".php?class1=".$class1."&page=".$i."&lang=".$lang;
				if($metadmin['pagename'] and $met_class[$class1]['filename']<>""){
					$filename=$met_class[$class1]['filename']."_".$i;
					$indexy =1;
				}else{
					if($met_class[$class1]['module']==7)$class1="list";
					$filename=$pagename."_".$class1."_".$i;
					$indexy =0;
				}
				if($indexname && $i==1)$metrn .= createhtm($fromurl,$indexname,$htmpack,$indexy).'$|$';
				$metrn .= createhtm($fromurl,$filename,$htmpack,$indexy).'$|$';
			}
		}
		if($class2!=0 and ($classtype==0 or $classtype==2)){
			$total_count = $db->counter($tablename, " where lang='".$lang."' and class1=".$class1." and class2=".$class2." and (recycle='0' or recycle='-1')", "*");
			$page_count=ceil($total_count/$pagesize);
			$page_count=$page_count?$page_count:1;
			if($class1_info[module]==3 && $met_product_page && $class3)$page_count=1;
			if($class1_info[module]==5 && $met_img_page && $class3)$page_count=1;
			for($i=1;$i<=$page_count;$i++){
				$fromurl=$foldername."/".$phpfilename.".php?class1=".$class1."&class2=".$class2."&page=".$i."&lang=".$lang;
				if($metadmin[pagename] and $met_class[$class2][filename]<>""){
					$filename=$met_class[$class2][filename]."_".$i;
					$indexy =1;
				}else{
					$filename= ($met_listhtmltype==0)?$pagename."_".$class1."_".$class2."_".$i:$pagename."_".$class2."_".$i;
					$indexy =0;
				}
				$metrn .= createhtm($fromurl,$filename,$htmpack,$indexy).'$|$';
			}
		}
		if($class3!=0 and ($classtype==0 or $classtype==3)){
			$total_count = $db->counter($tablename, " where lang='".$lang."' and class1=".$class1." and class2=".$class2." and class3=".$class3." and (recycle='0' or recycle='-1')", "*");
			$page_count=ceil($total_count/$pagesize);
			$page_count=$page_count?$page_count:1;
			for($i=1;$i<=$page_count;$i++){
				$fromurl=$foldername."/".$phpfilename.".php?class1=".$class1."&class2=".$class2."&class3=".$class3."&page=".$i."&lang=".$lang;
				if($metadmin[pagename] and $met_class[$class3][filename]<>""){
					$filename=$met_class[$class3][filename]."_".$i;
					$indexy =1;
				}else{
					$filename= ($met_listhtmltype==0)?$pagename."_".$class1."_".$class2."_".$class3."_".$i:$pagename."_".$class3."_".$i;
					$indexy =0;
				}
				$metrn .= createhtm($fromurl,$filename,$htmpack,$indexy).'$|$';
			}
		}
		return $metrn;
	}
}
/*删除静态页面*/
function deletepage($foldername,$id,$phpfilename,$updatetime,$htmlname){
global $lang,$met_htmtypeadmin,$met_htmpagename,$depth;
switch($met_htmpagename){
case 0:
$pagename=$phpfilename.$id;
break;
case 1:
$pagename=$updatetime.$id;
break;
case 2:
$pagename=$foldername.$id;
break;
}
if($htmlname<>""){
$filename=$depth."../../".$foldername."/".$htmlname.$met_htmtypeadmin;
}else{
$filename=$depth."../../".$foldername."/".$pagename.$met_htmtypeadmin;
}
if(stristr(PHP_OS,"WIN")){
	$filename=@iconv("utf-8","GBK",$filename);
}
if(file_exists($filename))@unlink($filename);
}
/*简介模块静态页面*/
function showhtm($id,$htmway=0,$htmpack=0){
	global $db,$lang,$met_webhtm,$met_htmway,$met_column,$met_index_type,$met_class,$met_class2a,$met_class1;
	$met_htmway=$htmway?0:$met_htmway;
	if($met_webhtm!=0 && $met_htmway==0){
		$folder=$db->get_one("select * from $met_column where id='$id'");
		$fromurl=$folder['foldername']."/show.php?id=".$id."&lang=".$lang;
		$indexname=0;
		if($folder['classtype']==1||$folder['releclass']){
			$dbtxt=$folder['releclass']?2:1;
			$folderone=$db->get_one("SELECT * FROM $met_column WHERE foldername='$folder[foldername]' and id !='$folder[id]' and classtype='$dbtxt' and lang='$lang'");
			if(!$folderone){
				$indexname='index';
				if($folder['lang']!=$met_index_type)$indexname=0;
			}
		}
		if($indexname){
			$fromurl=$folder['foldername']."/index.php?id=".$id."&lang=".$lang;
			return createhtm($fromurl,$indexname,$htmpack,$indexy);
		}else{
			$filename=$folder['filename']!=''?$folder['filename']:$folder['foldername'].$id;
			$indexy = $folder['filename']!=''?1:0;
			return createhtm($fromurl,$filename,$htmpack,$indexy);
		}
	}
}
/*列表页静态页面*/
function onepagehtm($foldername,$phpfilename,$htmway=0,$htmpack=0,$filename,$class1){
	global $lang,$met_webhtm,$met_htmway;
	$met_htmway=$htmway?0:$met_htmway;
	if($met_webhtm!=0 && $met_htmway==0){
		if($class1)$class = '&id='.$class1;
		$fromurl=$foldername."/".$phpfilename.".php?lang=".$lang.$class;
		$indexy  = $filename!=''?1:0;
		$filename=$filename!=''?$filename:$phpfilename;
		if($phpfilename=='sitemap'){
			$metrn .= createhtm($fromurl,'index',$htmpack,$indexy).'$|$';
			$metrn .= createhtm($fromurl,$filename,$htmpack,$indexy).'$|$';
			return $metrn;
		}else{
			return createhtm($fromurl,$filename,$htmpack,$indexy);
		}
	}
}
 /*新建栏目生成文件*/
function Copyfile($address,$newfile){
	$oldcont  = "<?php\n# MetInfo Enterprise Content Management System \n# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. \nrequire_once '$address';\n# This program is an open source system, commercial use, please consciously to purchase commercial license.\n# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.\n?>";
	if(!file_exists($newfile)){
		$fp = fopen($newfile,w);
		fputs($fp, $oldcont);
		fclose($fp);
	}
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
/*复制首页*/
function Copyindx($newindx,$type){
	if(!file_exists($newindx)){
		$oldcont ="<?php\n# MetInfo Enterprise Content Management System \n# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. \n\$filpy = basename(dirname(__FILE__));\n\$fmodule=$type;\nrequire_once '../include/module.php'; \nrequire_once \$module; \n# This program is an open source system, commercial use, please consciously to purchase commercial license.\n# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.\n?>";
		$fp = fopen($newindx,w);
		fputs($fp, $oldcont);
		fclose($fp);
	}
}
/*生成反馈配置文件*/
function verbconfig($array,$id){
global $lang,$db,$met_config;
	$query="where columnid='$id' and lang='$lang'";
	$db->counter($met_config,$query,"*");
	if($db->counter($met_config,$query,"*")==0){
		foreach($array as $key=>$val){
			$query="insert into $met_config set name='$val[0]',value='$val[1]',columnid='$id',flashid='0',lang='$lang'";
			$db->query($query);
		}
	}
}
/*全站打包复制图片JS等*/
function xCopy($source, $destination, $child){
    if(!is_dir($source)){
    echo("Error:the $source is not a direction!");
    return 0;
    }
    if(!is_dir($destination)){
    mkdir($destination,0777);
    }
    $handle=dir($source);
    while($entry=$handle->read()){
        if(($entry!=".")&&($entry!="..")){
            if(is_dir($source.'/'.$entry)){
                if($child)xCopy($source."/".$entry,$destination."/".$entry,$child);
            }else{
                copy($source."/".$entry,$destination."/".$entry);
            }
        }
    }
    return true;
}
/*删除目录和其下所有文件*/
function deldir($dir,$dk=1) {
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }
  closedir($dh);
  if($dk==0 && $dir!='../../upload')$dk=1;
  if($dk==1){
	  if(rmdir($dir)){
		return true;
	  }else{
		return false;
	  }
  }
}
/*是否是系统模块*/
function unkmodule($filename){
	$modfile = array('about','news','product','download','img','job','cache','config','feedback','include','lang','link','member','message','public','search','sitemap','templates','upload','wap');
	$ok=0;
	foreach($modfile as $key=>$val){
		if($filename==$val)$ok = 1;
	}
	return $ok;
}
/*查看用户类型*/
function metidtype($metid){
	global $db,$met_admin_table,$lang_access1,$lang_access2,$lang_access3,$lang_feedbackAccess0;
	$feedacs=$db->get_one("select * from $met_admin_table where admin_id='$metid'");
	$feeda=$feedacs['usertype']==1?$lang_access1:($feedacs['usertype']==2?$lang_access2:($feedacs['usertype']==3?$lang_access3:$lang_feedbackAccess0));
	return $feeda;
}
/*语言权限*/
function admin_poplang($type,$lang){
	$admin_pop=explode(',',$type);
	$popnum=count($admin_pop);
	$poplang='';
	for($i=0;$i<$popnum;$i++){
		if(strstr($admin_pop[$i],$lang.'-'))$poplang=$admin_pop[$i];
	}
	return $poplang;
}
/*模块返回表名*/
function moduledb($module){
	global $met_column,$met_product,$met_img,$met_news,$met_download,$met_job;
	switch($module){
		case 1:
			$moduledb=$met_column;
			break;
		case 2:
			$moduledb=$met_news;
			break;
		case 3:
			$moduledb=$met_product;
		    break;
		case 4:
			$moduledb=$met_download;
		    break;
		case 5:
			$moduledb=$met_img;
		    break;
		case 6:
			$moduledb=$met_job;
		    break;
		case 100:
			$moduledb=$met_product;
		    break;
		case 101:
			$moduledb=$met_img;
		    break;
	}
	return $moduledb;
}
/*删除栏目*/
function delcolumn($column){
global $lang,$db,$met_deleteimg,$depth;
global $met_admin_table,$met_column,$met_cv,$met_download,$met_feedback,$met_flist,$met_img,$met_job,$met_link,$met_list,$met_message,$met_news,$met_parameter,$met_plist,$met_product,$met_config;
if($column['releclass']){
$classtype="class1";
}else{
$classtype="class".$column['classtype'];
}
switch ($column['module']){
	default:
	 $query = "delete from $met_column where id='$column[id]'";
     $db->query($query);
    break;
	case 2:
	 if($met_deleteimg){
	 	 $query = "select * from $met_news where $classtype='$column[id]'";
		 $del = $db->get_all($query);
		 delimg($del,2,2);
	 }
	 $query = "delete from $met_news where $classtype='$column[id]'";
	 $db->query($query);
	 $query = "delete from $met_column where id='$column[id]'";
     $db->query($query);
	break;
	case 3:
	 $query = "select * from $met_product where $classtype='$column[id]'";
     $del = $db->get_all($query);
	 delimg($del,2,3);
	 foreach($del as $key=>$val){
		$query = "delete from $met_plist where listid='$val[id]' and module='$column[module]'";
	    $db->query($query);
	 }
	 $query = "delete from $met_product where $classtype='$column[id]'";
	 $db->query($query);
	 $query = "delete from $met_column where id='$column[id]'";
     $db->query($query);
	break;
	case 4:
	 $query = "select * from $met_download where $classtype='$column[id]'";
	 $del = $db->get_all($query);
	 delimg($del,2,4);
	 foreach($del as $key=>$val){
		$query = "delete from $met_plist where listid='$val[id]' and module='$column[module]'";
	    $db->query($query);
	 }
	 $query = "delete from $met_download where $classtype='$column[id]'";
	 $db->query($query);
	 $query = "delete from $met_column where id='$column[id]'";
     $db->query($query);
	break;
	case 5:
	 $query = "select * from $met_img where $classtype='$column[id]'";
	 $del = $db->get_all($query);
	 delimg($del,2,5);
	 foreach($del as $key=>$val){
		$query = "delete from $met_plist where listid='$val[id]' and module='$column[module]'";
	    $db->query($query);
	 }
	 $query = "delete from $met_img where $classtype='$column[id]'";
	 $db->query($query);
	 $query = "delete from $met_column where id='$column[id]'";
     $db->query($query);
	break;
	case 6:
	if($met_deleteimg){
		$query = "select * from $met_cv where lang='$lang'";
		$del = $db->get_all($query);
		delimg($del,2,6);
	 }		
	 $query = "delete from $met_plist where lang='$lang' and module='$column[module]'";
	 $db->query($query);
	 $query = "delete from $met_cv where lang='$lang'";
	 $db->query($query);
	 $query = "delete from $met_job where lang='$lang'";
	 $db->query($query);
	 $query = "delete from $met_column where id='$column[id]'";
     $db->query($query);
	break;
	case 7:
	 $query = "delete from $met_message where lang='$lang'";
	 $db->query($query);
	 $query = "delete from $met_column where id='$column[id]'";
     $db->query($query);
	 $query="delete from $met_config where columnid='$column[id]' and lang='$lang'";
	 $db->query($query);
	break;
	case 8:
	 $query = "select * from $met_feedback where class1='$column[id]'";
	 $del = $db->get_all($query);
	 delimg($del,2,8);
	 foreach($del as $key=>$val){
		$query = "delete from $met_flist where listid='$list[id]'";
	    $db->query($query);
	 }
	 $query = "delete from $met_parameter where module='$column[module]' and class1='$column[id]' and lang='$lang'";
	 $db->query($query);
	 $query = "delete from $met_feedback where class1='$column[id]' and lang='$lang'";
	 $db->query($query);
	 $query = "delete from $met_column where id='$column[id]'";
     $db->query($query);
	 $query="delete from $met_config where columnid='$column[id]' and lang='$lang'";
	 $db->query($query);
	break;
	case 9:
	 $query = "delete from $met_link where lang='$lang'";
	 $db->query($query);
	 $query = "delete from $met_column where id='$column[id]'";
     $db->query($query);
	break;
	case 10:
	 $query = "delete from $met_admin_table where usertype!=3 and lang='$lang'";
	 $db->query($query);
	 $query = "delete from $met_column where id='$column[id]'";
     $db->query($query);
	break;
}
/*删除文件*/
$admin_lists = $db->get_one("SELECT * FROM $met_column WHERE foldername='$column[foldername]'");
if(!$admin_lists['id'] && ($column['classtype'] == 1 || $column['releclass'])){
	if($column['foldername']!='' && ($column['module']<6 || $column['module']==8) && $column['if_in']!=1){
		if(!unkmodule($column['foldername'])){
			$foldername=$depth."../../".$column['foldername'];
			deldir($foldername);
		}
	}
}
/*删除栏目图片*/
if($met_deleteimg){
file_unlink($depth."../".$column[indeximg]);
file_unlink($depth."../".$column[columnimg]);
}
}
/*删除图片*/
/*type1 删除1行，type2 为多行删除，$para_list为空时必须指定模块*/
function delimg($del,$type,$module=0,$para_list=NULL){
global $lang,$db,$met_deleteimg,$depth;
global $met_admin_table,$met_column,$met_cv,$met_download,$met_feedback,$met_flist,$met_img,$met_job,$met_link,$met_list,$met_message,$met_news,$met_parameter,$met_plist,$met_product;
if($met_deleteimg){
	$table=$module==8?$met_feedback:$met_plist;
	if($para_list==NULL&&$module!=2){
		$query = "select * from $met_parameter where lang='$lang' and module='$module' and (class1='$del[class1]' or class1=0) and type='5'";
		$para_list=$db->get_all($query);
	}
	if($type==1){
		$delnow[]=$del;
	}
	else if($type==2){
		$delnow=$del;
	}
	else{
		$table=moduledb($module);
		$query="select * from $table where id='$id'";
		echo $query;
		$del=$db->get_one($query);
		$delnow[]=$del;
	}	
	foreach($delnow as $key=>$val){
		if($val['recycle']!=2||$module!=2){
			foreach($para_list as $key1=>$val1){
				if(($module==$val1['module']||$val['recycle']==$val1['module'])&&($val1['class1']==0||$val1['class1']==$val['class1'])){
					$imagelist=$db->get_one("select * from $table where lang='$lang' and  paraid='$val1[id]' and listid='$val[id]'");
					file_unlink($depth."../".$imagelist['info']);
					$imagelist['info']=str_replace('watermark/','',$imagelist['info']);
					file_unlink($depth."../".$imagelist['info']);
				}
			}
		}
		if($module==6||$module==8)continue;
		if($val['displayimg']!=NULL){
			$displayimg=explode('|',$val['displayimg']);
			foreach($displayimg as $key2=>$val2){
				$display_val=explode('*',$val2);
				file_unlink($depth."../".$display_val[1]);
				$display_val[1]=str_replace('watermark/','',$display_val[1]);
				file_unlink($depth."../".$display_val[1]);
				$imgurl_diss=explode('/',$display_val[1]);
				file_unlink($depth."../".$imgurl_diss[0].'/'.$imgurl_diss[1].'/'.$imgurl_diss[2].'/thumb_dis/'.$imgurl_diss[count($imgurl_diss)-1]);
				
			}
		}
		if($val['downloadurl']==NULL){
			file_unlink($depth."../".$val['imgurl']);
			file_unlink($depth."../".$val['imgurls']);
			$val['imgurlbig']=str_replace('watermark/','',$val['imgurl']);
			file_unlink($depth."../".$val['imgurlbig']);
			$imgurl_diss=explode('/',$val['imgurlbig']);
			file_unlink($depth."../".$imgurl_diss[0].'/'.$imgurl_diss[1].'/'.$imgurl_diss[2].'/thumb_dis/'.$imgurl_diss[count($imgurl_diss)-1]);
		}
		else{
			file_unlink($depth."../".$val['downloadurl']);
		}
		
		$content[0]=$val[content];
		$content[1]=$val[content1];
		$content[2]=$val[content2];
		$content[3]=$val[content3];
		$content[4]=$val[content4];
		foreach($content as $contentkey=>$contentval){
			if($contentval){
				$tmp1 = explode("<",$contentval);
				foreach($tmp1 as $key=>$val){
					$tmp2=explode(">",$val);
					if(strcasecmp(substr(trim($tmp2[0]),0,3),'img')==0){
						preg_match('/http:\/\/([^\"]*)/i',$tmp2[0],$out);
						$imgs[]=$out[1];
					}
				}
			}
		}
		foreach($imgs as $key=>$val){
			$vals=explode('/',$val);		
			file_unlink($depth."../../upload/images/".$vals[count($vals)-1]);
			file_unlink($depth."../../upload/images/watermark/".$vals[count($vals)-1]);
		}
	}

}
}

/*文件权限检测*/
function filetest($dir){
	@clearstatcache();
	if(file_exists($dir)){
		//@chmod($dir,0777);
		$str=file_get_contents($dir);
		if(strlen($str)==0)return 0;
		$return=file_put_contents($dir,$str);
	}
	else{
		$filedir='';
		$filedir=explode('/',dirname($dir));
		$flag=0;
		foreach($filedir as $key=>$val){
			if($val=='..'){
				$fileexist.="../";
			}
			else{
				if($flag){
					$fileexist.='/'.$val;
				}
				else{
					$fileexist.=$val;
					$flag=1;
				}
				if(!file_exists($fileexist)){
						@mkdir ($fileexist, 0777);
				}	
			}
		}
		$filename=$fileexist.'/'.basename($dir);
		if(strstr(basename($dir),'.')){
			$fp=@fopen($filename, "w+");
			@fclose($fp);
			//@chmod($filename,0777);
		}
		else{
			@mkdir ($filename, 0777);
		}
		$return=file_put_contents($dir,'metinfo');
	}
	return $return;
}
/*上传图片缩略图尺寸*/
function imgstyle($module){
       global $met_img_x,$met_img_y,$met_productimg_x,$met_productimg_y,$met_imgs_x,$met_imgs_y,$met_newsimg_x,$met_newsimg_y,$met_img_style;
	   if($met_img_style==1){
			switch($module){
				case '3': 
					$met_img_x=$met_productimg_x; 
					$met_img_y=$met_productimg_y; 
				break;
				case '5': 
					$met_img_x=$met_imgs_x; 
					$met_img_y=$met_imgs_y; 
				break;
				case '2': 
					$met_img_x=$met_newsimg_x; 
					$met_img_y=$met_newsimg_y; 
				break;
			}
		}
}
/*版本比较*/
function metver($verold,$vernow,$sysver){
	$oldnum=strripos($sysver,'|'.$verold.'|');
	$nownum=strripos($sysver,'|'.$vernow.'|');
	if($oldnum<$nownum)return 1;
	if($oldnum==$nownum)return 2;
	if($oldnum>$nownum)return 3;	
}
/*替换admin文件*/
function readmin($dir,$adminfile,$type){
	if($adminfile!="admin"){
		$dirs=explode('/',$dir);
		if($type==1){
			if($dirs[0]==$adminfile){
				$dirs[0]='admin';
			}
		}
		else{
			if($dirs[0]=='admin'){
				$dirs[0]=$adminfile;
			}
		}

		$dir=implode('/',$dirs);	
	}
	return $dir;
}
/*管理员用户组*/
function admin_grouptp($type){
	global $lang_managertyp1,$lang_managertyp2,$lang_managertyp3,$lang_managertyp4,$lang_managertyp5;
	switch($type){
		case 10000:
			$metinfo=$lang_managertyp1;
		break;
		case 3:
			$metinfo=$lang_managertyp2;
		break;
		case 2:
			$metinfo=$lang_managertyp3;
		break;
		case 1:
			$metinfo=$lang_managertyp4;
		break;
		case 0:
			$metinfo=$lang_managertyp5;
		break;
	}
	return $metinfo;
}
function morenfod($foldername,$module){
	$metinfo=1;
	switch($foldername){
		case 'about':
			$metinfo = $module==1?0:1;
		break;
		case 'news':
			$metinfo = $module==2?0:1;
		break;
		case 'product':
			$metinfo = $module==3?0:1;
		break;
		case 'download':
			$metinfo = $module==4?0:1;
		break;
		case 'img':
			$metinfo = $module==5?0:1;
		break;
		case 'feedback':
			$metinfo = $module==8?0:1;
		break;
	}
	return $metinfo;
}
function met_scandir($directory, $sorting_order = 0) {   
 $dh  = opendir($directory);   
 while( false !== ($filename = readdir($dh)) ) {   
	 $files[] = $filename;   
 }   
 if( $sorting_order == 0 ) {   
	 sort($files);   
 } else {   
	 rsort($files);   
 }   
 return($files);   
}  
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>