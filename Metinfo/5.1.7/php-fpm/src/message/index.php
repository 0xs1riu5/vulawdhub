<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../include/common.inc.php';
$fmodule=7;
if($list==1){
require_once '../include/module.php';
$metid='';
}
if(!$metid)$metid='index';
if($metid!='index'){
require_once 'message.php';
}else{
$message_column=$db->get_one("select * from $met_column where module='7' and lang='$lang'");
$metaccess=$message_column[access];
$class1=$message_column[id];
foreach($settings_arr as $key=>$val){
	if($val['columnid']==$class1){
		$tingname    =$val['name'].'_'.$val['columnid'];
		$$val['name']=$$tingname;
	}
}
require_once '../include/head.php';
	$class1_info=$class_list[$class1][releclass]?$class_list[$class_list[$class1][releclass]]:$class_list[$class1];
	$class2_info=$class_list[$class1][releclass]?$class_list[$class1]:$class_list[$class2];
	$navtitle=$message_column[name];
    $serch_sql=" where lang='$lang' ";
	if($met_fd_type==1) $serch_sql.=" and readok='1' ";
	if($met_member_use==2)$serch_sql .= " and access<=$metinfo_member_type";
	$order_sql=" order by id desc ";
    $total_count = $db->counter($met_message, "$serch_sql", "*");
    require_once '../include/pager.class.php';
    $page = (int)$page;
	if($page_input){$page=$page_input;}
    $list_num = $met_message_list;
    $rowset = new Pager($total_count,$list_num,$page);
    $from_record = $rowset->_offset();
    $query = "SELECT * FROM $met_message $serch_sql $order_sql LIMIT $from_record, $list_num";

    $result = $db->query($query);
	while($list= $db->fetch_array($result)){
	if($met_member_use){
	if(intval($list[access])>0){ 
	$list[info]="<script language='javascript' src='access.php?metaccess=".$list[access]."&lang=".$lang."&listinfo=info&id=".$list[id]."'></script>";
	$list[useinfo]="<script language='javascript' src='access.php?metaccess=".$list[access]."&lang=".$lang."&listinfo=useinfo&id=".$list[id]."'></script>";
	  }}
    $message_list[]=$list;
    }

if($met_webhtm==2){
$met_pagelist=$met_htmlistname?"message_list_":"index_list_";
$met_pagelist=$message_column['filename']<>''?$message_column['filename'].'_':$met_pagelist;
$met_ahtmtype = $message_column['filename']<>''?$met_chtmtype:$met_htmtype;
$page_list = $rowset->link($met_pagelist,$met_ahtmtype);
}else{			
$page_list = $rowset->link("index.php?lang=".$lang."&page=");	
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
if(count($nav_list2[$message_column[id]])){
$k=count($nav_list2[$class1]);
$nav_list2[$class1][$k]=$class1_info;
$nav_list2[$class1][$k][name]=$lang_messageview;
$k++;
$nav_list2[$class1][$k]=array('url'=>$addmessage_url,'name'=>$lang_messageadd);
}else{
$k=count($nav_list2[$class1]);
  if(!$k){
   $nav_list2[$class1][0]=array('url'=>$addmessage_url,'name'=>$lang_messageadd);
   $nav_list2[$class1][1]=$class1_info;
   $nav_list2[$class1][1][name]=$lang_messageview;
   }
}
require_once '../public/php/methtml.inc.php';

$methtml_messagelist.="<ul>\n";
foreach($message_list as $key=>$val){
$methtml_messagelist.="<li class='message_list_line'><span >[NO".$val[id]."]£º<b>".$val[name]."</b> ".$lang_Publish." ".$val[addtime]."</span></li>\n";
$methtml_messagelist.="<li class='message_list_info'><span ><b>".$lang_SubmitContent."</b>:".$val[info]."</span></li>\n";
$methtml_messagelist.="<li class='message_list_reinfo'><span ><b>".$lang_Reply."</b>:".$val[useinfo]."</span></li>\n";
}
$methtml_messagelist.="</ul>\n";
include template('message_index');
footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>