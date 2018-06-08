<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
include $depth."../include/pclzip.lib.php";
$codeold='utf-8';
$codenew='GBK';
$class=$class3?$class3:($class2?$class2:$class1);
$classcsv=$db->get_one("select * from $met_column where id='$class'");
$class1title=$db->get_one("select * from $met_column where id='$class1'");
if($class2){$class2title=$db->get_one("select * from $met_column where id='$class2'");}
if($class3){$class3title=$db->get_one("select * from $met_column where id='$class3'");}
$title=$class1title[name];
$title.=$class2?"_$class2title[name]":"";
$title.=$class3?"_$class3title[name]":"";
$title.="_$class1title[id]";
$title.=$class2?"-$class2title[id]":"";
$title.=$class3?"-$class3title[id]":"";
$title=iconv($codeold,$codenew,$title);
$fp = fopen("$title.csv", 'w');
$classcsv=$class3?$class3title:($class2?$class2title:$class1title);
$csvarray[]=iconv($codeold,$codenew,$lang_title);
if($classcsv['module']!=2){
	$query = "select * from $met_parameter where lang='$lang' and module='$classcsv[module]' and (class1='$classcsv[id]' or class1=0) order by no_order";
	$csvpara=$db->get_all($query);
	foreach($csvpara as $key=>$val){
		if($val['type']!=5){$csvarray[]=iconv($codeold,$codenew,$val['name']);}
	}
}
$csvarray[]=iconv($codeold,$codenew,$lang_columnhtmlname);
$csvarray[]=iconv($codeold,$codenew,$lang_keywords);
$csvarray[]=iconv($codeold,$codenew,$lang_description);


$csvarray[]=iconv($codeold,$codenew,$lang_contentdetail);	
$metadminnum=0;
if($metadmin['productother']&&$classcsv['module']==3){
$metadminnum=$metadmin['productother'];
}
if($metadmin['imgother']&&$classcsv['module']==5){
$metadminnum=$metadmin['imgother'];
}
if($metadminnum!=0){
	$cvsother=1;
	while($cvsother<=$metadminnum){
		$contentinfo=$lang_contentinfo.$cvsother;
		$csvarray[]=iconv($codeold,$codenew,$contentinfo);
		$cvsother++;
	}
}


fputcsv($fp,$fristarray); 
fputcsv($fp,$csvarray); 
fclose($fp);


$sqlzip='csv.zip';
$archive = new PclZip($sqlzip);
$zip_list = $archive->create("./$title.csv");
$cont  = iconv($codeold,$codenew,"{$lang_csvexplain1}\r\n{$lang_csvexplain2}\r\n{$lang_csvexplain3}\r\n{$lang_csvexplain4}");
$fp = fopen(iconv($codeold,$codenew,"./{$lang_langshuom}.txt"),w);
fputs($fp, $cont);
fclose($fp);
$zip_list = $archive->add(iconv($codeold,$codenew,"./{$lang_langshuom}.txt"));
@file_unlink("./$title.csv");
@file_unlink(iconv($codenew,$codeold,"./$title.csv"));
@file_unlink(iconv($codeold,$codenew,"./{$lang_langshuom}.txt"));
header("Content-type:application/zip;");
$title=$title.'.zip';
$title=iconv($codenew,$codeold,$title);
$encoded_filename = urlencode($title);
$encoded_filename = str_replace("+", "%20", $encoded_filename);
$ua = $_SERVER["HTTP_USER_AGENT"];
if (preg_match("/MSIE/", $ua)) {
	header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
} else if (preg_match("/Firefox/", $ua)) {
	header('Content-Disposition: attachment; filename="' . $title . '"');
} else {
	header('Content-Disposition: attachment; filename="' . $title . '"');
}
readfile("csv.zip");
@file_unlink("csv.zip");
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>