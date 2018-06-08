<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../include/common.inc.php';
$classaccess= $db->get_one("SELECT * FROM $met_column WHERE module='6' and lang='$lang'");
$metaccess=$classaccess[access];
$class1=$classaccess[id];

require_once '../include/head.php';
	$guanlian=$class_list[$class1][releclass];
	$class1_info=$class_list[$class1][releclass]?$class_list[$class_list[$class1][releclass]]:$class_list[$class1];
	$class2_info=$class_list[$class1][releclass]?$class_list[$class1]:$class_list[$class2];
	if(!class1_info){
	okinfo('../',$lang_error);
	}
    $serch_sql=" where lang='$lang' and ((TO_DAYS(NOW())-TO_DAYS(`addtime`)< useful_life) OR useful_life=0) ";
	if($met_member_use==2)$serch_sql .= " and access<=$metinfo_member_type";
	$order_sql="order by no_order desc,addtime desc";
    $total_count = $db->counter($met_job, "$serch_sql", "*");
	$totaltop_count = $db->counter($met_job, "$serch_sql and top_ok='1'", "*");
    require_once '../include/pager.class.php';
    $page = (int)$page;
	if($page_input){$page=$page_input;}
    $list_num=$met_job_list;
    $rowset = new Pager($total_count,$list_num,$page);
    $from_record = $rowset->_offset();
	$page = $page?$page:1;
	 $query = "SELECT * FROM $met_job $serch_sql and top_ok='1' $order_sql LIMIT $from_record, $list_num";
	 $result = $db->query($query);
	 while($list= $db->fetch_array($result)){
	 $job_listnow[]=$list;
	 }
	if(count($job_listnow)<intval($list_num)){
	 if($totaltop_count>=$list_num){
	  $from_record=$from_record-$totaltop_count;
	  if($from_record<0)$from_record=0;
	 }else{
	 $from_record=$from_record?($from_record-$totaltop_count):$from_record;
	 }
	 $list_num=intval($list_num)-count($job_listnow);
	 $query = "SELECT * FROM $met_job $serch_sql and top_ok='0' $order_sql LIMIT $from_record, $list_num";
	 $result = $db->query($query);
	 while($list= $db->fetch_array($result)){
	 $job_listnow[]=$list;
	 }
	}
	foreach($job_listnow as $key=>$list){
	if(intval($list[useful_life])==0)$list[useful_life]=$lang_Nolimit;
	$list[top]=$list[top_ok]?"<img class='listtop' src='".$img_url."top.gif"."' />":"";
	$list[news]=$list[top_ok]?"":((((strtotime($m_now_date)-strtotime($list[addtime]))/86400)<$met_newsdays)?"<img class='listnews' src='".$img_url."news.gif"."' />":"");
	$pagename1=$list['addtime'];
	$list[addtime] = date($met_listtime,strtotime($list[addtime]));
	if($met_webhtm){
	switch($met_htmpagename){
    case 0:
	$htmname="showjob".$list[id];	
	break;
	case 1:
	$list[updatetime1] = date('Ymd',strtotime($pagename1));
	$htmname=$list[updatetime1].$list[id];	
	break;
	case 2:
	$htmname="job".$list[id];	
	break;
	}
   $htmname=($list[filename]<>"" and $metadmin[pagename])?$list[filename]:$htmname;
   }	
	$phpname="showjob.php?".$langmark."&id=".$list[id];
	$panyid = $list['filename']!=''?$list['filename']:$list['id'];
	$list[url]=$met_pseudo?$panyid.'-'.$lang.'.html':($met_webhtm?$htmname.$met_htmtype:$phpname);
	$list[cv]=$met_pseudo?'jobcv-'.$panyid.'-'.$lang.'.html':$cv['url'].$list['id'];
	$job_list[]=$list;
}
if($met_webhtm==2){
	$met_pagelist=(($metadmin[pagename] and $class_list[$class1][filename]<>"")?$class_list[$class1][filename]:"job")."_".$class1."_";
	$met_pagelist=$class_list[$class1]['filename']<>''?$class_list[$class1]['filename'].'_':$met_pagelist;
	$met_ahtmtype = $class_list[$class1]['filename']<>''?$met_chtmtype:$met_htmtype;
	$page_list = $rowset->link($met_pagelist,$met_ahtmtype);
}else{			
	$pagemor = ($metadmin['pagename'] and $class_list[$class1]['filename']<>"")?'list-'.$class_list[$class1]['filename'].'-':'list-'.$class1.'-';
	$hz = '-'.$lang.'.html';
	$page_list = $met_pseudo?$rowset->link($pagemor,$hz):$rowset->link("job.php?lang=$lang&page=");		
}

$class2=$class_list[$class1][releclass]?$class1:$class2;
$class1=$class_list[$class1][releclass]?$class_list[$class1][releclass]:$class1;
$class_info=$class2?$class2_info:$class1_info;
if($class2!=""){
$class_info[name]=$class2_info[name]."-".$class1_info[name];
}

     $show[description]=$class_info[description]?$class_info[description]:$met_keywords;
     $show[keywords]=$class_info[keywords]?$class_info[keywords]:$met_keywords;
	 $met_title=$met_title?$class_info['name'].'-'.$met_title:$class_info['name'];
	 if($class_info['ctitle']!='')$met_title=$class_info['ctitle'];
	 if($page>1)$met_title.='-'.$lang_Pagenum1.$page.$lang_Pagenum2;
if(!$guanlian){
	if(count($nav_list2[$class1])){
	$k=count($nav_list2[$class1]);
	$nav_list2[$class1][$k]=array('id'=>10004,'url'=>$cv[url],'name'=>$lang_cvtitle);
	}else{
	$nav_list2[$class1][0]=$class1_info;
	$nav_list2[$class1][1]=array('id'=>10004,'url'=>$cv[url],'name'=>$lang_cvtitle);
	}
}
$csnow=$cvidnow?$cvidnow:$classnow;
require_once '../public/php/methtml.inc.php';
require_once '../public/php/jobhtml.inc.php';
include template('job');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>