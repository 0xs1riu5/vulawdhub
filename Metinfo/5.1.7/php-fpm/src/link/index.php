<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../include/common.inc.php';
if(!$metid)$metid='index';
if($metid!='index'){
require_once 'addlink.php';
}else{
    $link_list=$db->get_one("select * from $met_column where module='9' and lang='$lang'");
    $metaccess=$link_list[access];
    $class1=$link_list[id];
require_once '../include/head.php';
	$class1_info=$class_list[$class1][releclass]?$class_list[$class_list[$class1][releclass]]:$class_list[$class1];
	$class2_info=$class_list[$class1][releclass]?$class_list[$class1]:$class_list[$class2];
    $navtitle=$link_list[name];
	$addlink_url=$met_pseudo?'addlink-'.$lang.'.html':($met_webhtm?"addlink".$met_htmtype:"addlink.php?lang=".$lang);
    $class2=$class_list[$class1][releclass]?$class1:$class2;
    $class1=$class_list[$class1][releclass]?$class_list[$class1][releclass]:$class1;
    $class_info=$class2?$class2_info:$class1_info;
    if($class2!=""){
        $class_info[name]=$class2_info[name]."--".$class1_info[name];
    }
    $show[description]=$class_info[description]?$class_info[description]:$met_keywords;
    $show[keywords]=$class_info[keywords]?$class_info[keywords]:$met_keywords;
	$met_title=$met_title?$navtitle.'-'.$met_title:$navtitle;
	if($link_list['ctitle']!='')$met_title=$link_list['ctitle'];
    if(count($nav_list2[$link_list[id]])){ 
        $k=count($nav_list2[$class1]); 
        $nav_list2[$class1][$k]=$class1_info;
        $k++;
        $nav_list2[$class1][$k]=array('url'=>$addlink_url,'name'=>$lang_ApplyLink);
    }else{
        $k=count($nav_list2[$class1]);
        if(!$k){
            $addlt=$met_addlinkopen?1:0;
            if($addlt)$nav_list2[$class1][0]=array('url'=>$addlink_url,'name'=>$lang_ApplyLink);
            $nav_list2[$class1][$addlt]=$class1_info;
        }
    }
require_once '../public/php/methtml.inc.php';
include template('link_index');
footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>