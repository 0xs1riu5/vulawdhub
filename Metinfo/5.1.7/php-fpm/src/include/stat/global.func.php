<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
function checkadd($p,$ipaddres){
	$preg="/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";
	if($p==2){
		$preg="/^http:\/\/[A-Za-z0-9\-]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/";
		if(substr($ipaddres,0,16)=='http://localhost' || substr($ipaddres,0,11)=='http://xn--')return true;
	}
	return preg_match($preg,$ipaddres);
}
function parttime($g,$value,$a,$b,$c){
	$value = explode('|',$value);
	$now = $a.'-'.$b.'-'.$c;
	for($i=0;$i<24;$i++){
		if($i==$g){
			if(!$value[$i]){
				$met.=$now;
			}else{
				$k=explode('-',$value[$i]);
				$a = $k[0]+$a;
				$b = $k[1]+$b;
				$c = $k[2]+$c;
				$met.= $a.'-'.$b.'-'.$c;
			}
		}else{
			$met.=$value[$i];
		}
		$met.= '|';
	}
	return $met;
}
function get_keyword($url,$kw_start){
	$start = stripos($url,$kw_start); 
	if($start){
		$url = substr($url,$start+strlen($kw_start)); 
		$start = stripos($url,'&'); 
		if($start>0){
			if ($start>0){ 
				$start=stripos($url,'&'); 
				$s_s_keyword=substr($url,0,$start); 
			}else{ 
				$s_s_keyword=substr($url,0); 
			} 
		}else{
			$s_s_keyword='';
		}
	}else{
		$s_s_keyword='';
	}
	return $s_s_keyword; 
}
function is_utf8($word){
	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true)
	{
	return true;
	}
	else
	{
	return false;
	}
}
function keytype($search_url){
	$config = array(
		'谷歌'=>array( "domain" => "www.google.", "kw" => "q", "charset" => "utf-8",'type' => 0 ), 
		'百度'=>array( "domain" => "www.baidu.", "kw" => "wd", "charset" => "gbk",'type' => 1 ), 
		'搜搜'=>array( "domain" => "soso.", "kw" => "w", "charset" => "gbk",'type' => 2 ), 
		'雅虎'=>array( "domain" => "yahoo.", "kw" => "q", "charset" => "utf-8",'type' => 3 ), 
		'必应'=>array( "domain" => "bing.", "kw" => "q", "charset" => "utf-8",'type' => 4  ), 
		'搜狗'=>array( "domain" => "sogou.", "kw" => "query", "charset" => "gbk",'type' => 5 ), 
		'有道'=>array( "domain" => "youdao.", "kw" => "q", "charset" => "utf-8",'type' => 6 ), 
		'360搜索'=>array( "domain" => "so.360.", "kw" => "q", "charset" => "utf-8",'type' => 7 )
	);
	$arr_key = array(); 
	foreach($config as $key=>$item){
		$sh = preg_match("/\b{$item['domain']}\b/",$search_url); 
		if($sh){
			$query = $item['kw']."="; 
			$s_s_keyword = get_keyword($search_url,$query);
			$F_Skey=urldecode($s_s_keyword);
			$agwe=0;
			if($key=='百度'){
				$agwe=get_keyword($search_url,'ie=');
				$item['charset']=$agwe==''?$item['charset']:$agwe;
			}
			if($item['charset']!="utf-8" && (!is_utf8($F_Skey) || $agwe)){ 
				$F_Skey=iconv( "gb2312//IGNORE","UTF-8",$F_Skey);
			} 
			$arr_key[0] = $F_Skey; 
			$arr_key[1] = $item['type']; 
		}
	}
	return  $arr_key;
}
function statime($ymd,$day=''){
	$day=$day==''?time():strtotime($day);
	$time=strtotime(date($ymd,$day));
	return $time;
}
function delet_estat_cr($type,$value){
	global $db,$met_visit_summary,$met_visit_detail,$met_visit_day,$met_adminfile;
	$time=date('Y-m');
	$string='';
	switch($value){
		case 1:
			$st=statime("Y-m-d");
		break;
		case 2:
			$st=statime("Y-m-d","-6 day");
		break;
		case 3:
			$st=statime("Y-m-d","last month");
		break;
		case 4:
			$st=statime("Y-m-d","-1 year");
		break;
	}
	if($st){
		switch($type){
			case 1:
				$query = "select * from {$met_visit_summary} where stattime<'{$st}'";
				$ststdata=$db->get_all($query);
				foreach($ststdata as $key=>$val){
					$string.="INSERT INTO {$met_visit_summary} VALUES('','$val[pv]','$val[ip]','$val[alone]','$val[parttime]','$val[stattime]');\n";
				}
				$query = "delete from {$met_visit_summary} where stattime<'{$st}'";
			break;
			case 2:
				$query = "select * from {$met_visit_detail} where stattime<'{$st}' and type='1'";
				$ststdata=$db->get_all($query);
				foreach($ststdata as $key=>$val){
					$string.="INSERT INTO {$met_visit_detail} VALUES('','$val[name]','$val[pv]','$val[ip]','$val[alone]','$val[remark]','$val[type]','$val[columnid]','$val[listid]','$val[stattime]','$val[lang]');\n";
				}
				$query = "select * from {$met_visit_detail} where stattime<'{$st}' and type='1'";
				$db->query($query);
			break;
			case 3:
				$query = "select * from {$met_visit_detail} where stattime<'{$st}' and type='2'";
				$ststdata=$db->get_all($query);
				foreach($ststdata as $key=>$val){
					$string.="INSERT INTO {$met_visit_detail} VALUES('','$val[name]','$val[pv]','$val[ip]','$val[alone]','$val[remark]','$val[type]','$val[columnid]','$val[listid]','$val[stattime]','$val[lang]');\n";
				}
				$query = "delete from {$met_visit_detail} where stattime<'{$st}' and type='2'";
				$db->query($query);
			break;
			case 4:
				$query = "select * from {$met_visit_detail} where stattime<'{$st}' and type='3'";
				$ststdata=$db->get_all($query);
				foreach($ststdata as $key=>$val){
					$string.="INSERT INTO {$met_visit_detail} VALUES('','$val[name]','$val[pv]','$val[ip]','$val[alone]','$val[remark]','$val[type]','$val[columnid]','$val[listid]','$val[stattime]','$val[lang]');\n";
				}
				$query = "delete from {$met_visit_detail} where stattime<'{$st}' and type='3'";
				$db->query($query);
			break;
			case 5:
				$query = "select * from {$met_visit_day} where acctime<'{$st}'";
				$ststdata=$db->get_all($query);
				foreach($ststdata as $key=>$val){
					$string.="INSERT INTO {$met_visit_day} VALUES('','$val[ip]','$val[acctime]','$val[visitpage]','$val[antepage]','$val[columnid]','$val[listid]','$val[browser]','$val[dizhi]','$val[network]','$val[lang]');\n";
				}
				$query = "delete from {$met_visit_day} where acctime<'{$st}'";
				$db->query($query);
			break;
		}
		if(!file_exists("../../{$met_adminfile}/databack/stat/"))mkdir("../../{$met_adminfile}/databack/stat/",0777);
		if(!file_exists("../../{$met_adminfile}/databack/"))mkdir("../../{$met_adminfile}/databack/",0777);
		if($string)file_put_contents("../../{$met_adminfile}/databack/stat/$time.sql",$string,FILE_APPEND);
	}
}
function delete($str) {
    $str = trim($str);
    $str = ereg_replace("\t","",$str);
    $str = ereg_replace("\r\n","",$str);
    $str = ereg_replace("\r","",$str);
    $str = ereg_replace("\n","",$str);
    $str = ereg_replace(" ","",$str);
    return trim($str);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
