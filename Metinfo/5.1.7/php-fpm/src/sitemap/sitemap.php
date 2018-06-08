<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../include/common.inc.php';
$sitemap_column=$db->get_one("select * from $met_column where module='12' and lang='$lang'");
$metaccess=$sitemap_column[access];
$class1=$sitemap_column[id];
require_once '../include/head.php';
$class1_info=$class_list[$class1][releclass]?$class_list[$class_list[$class1][releclass]]:$class_list[$class1];
$class2_info=$class_list[$class1][releclass]?$class_list[$class1]:$class_list[$class2];
$navtitle=$sitemap_column[name];
$class2=$class_list[$class1][releclass]?$class1:$class2;
$class1=$class_list[$class1][releclass]?$class_list[$class1][releclass]:$class1;
$class_info=$class2?$class2_info:$class1_info;
if($class2!="")$class_info[name]=$class2_info[name]."--".$class1_info[name];
$show[description]=$class_info[description]?$class_info[description]:$met_keywords;
$show[keywords]=$class_info[keywords]?$class_info[keywords]:$met_keywords;
$met_title=$met_title?$class_info['name'].'-'.$met_title:$class_info['name'];
if($class_info['ctitle']!='')$met_title=$class_info['ctitle'];
if(count($nav_list2[$classaccess[id]])){
	$k=count($nav_list2[$class1]);
	$nav_list2[$class1][$k]=$class1_info;
}
foreach($nav_list_1 as $key=>$val){
	if($val[nav]){
		$methtml_sitemap.="<dl class='sitemapclass'>\n";
		$methtml_sitemap.="<dd class='sitemapclass1' ><h2 style='font-size:13px;'><a href='".$val[url]."' title='".$val[name]."' >".$val[name]."</a></h2></dd>\n";
		foreach($nav_list2[$val[id]] as $key=>$val2){
			$methtml_sitemap.="<dd class='sitemapclass2' >
									<h3 style='font-weight:normal; font-size:12px;'>
										<a href='".$val2[url]."'  title='".$val2[name]."' >".$val2[name]."</a>
									</h3>\n";
			$methtml_sitemap.="<div>";
			foreach($nav_list3[$val2[id]] as $key=>$val3){
				$methtml_sitemap.="<h4 class='sitemapclass3' style='font-weight:normal; font-size:12px;'>
									<a href='".$val3[url]."' title='".$val3[name]."' >".$val3[name]."</a>
									</h4>\n";
			}
			$methtml_sitemap.="</div></dd>\n";
		}
		$methtml_sitemap.="</dl>\n";
	}
}
require_once ROOTPATH.'public/php/methtml.inc.php';
require_once ROOTPATH.'sitemap/generator.php';
include template('sitemap');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>