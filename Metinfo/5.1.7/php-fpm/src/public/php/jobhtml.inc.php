<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

//job list
function methtml_job($titlenum,$person,$address,$deal,$time,$timelong,$cvonline,$newwindow=1,$news,$top,$listnav=0,$max,$topcolor){
 global $job_list,$lang_Detail,$lang_job,$lang_Position,$lang_PersonNumber,$lang_WorkPlace,$lang_Deal,$lang_AddDate,$lang_Validity,$lang_cvtitle;
 global $cv;
 
 $listtext.="<ul>\n";
   if($listnav==1){
    $listtext.="<li class='job_list_title'>";
	$listtext.="<span class='info_title'>".$lang_Position."</span>";
	if($person==1)$listtext.="<span class='info_person'>".$lang_PersonNumber."</span>";
	if($address==1)$listtext.="<span class='info_address'>".$lang_WorkPlace."</span>";
	if($deal==1)$listtext.="<span class='info_deal'>".$lang_Deal."</span>";
	if($time==1)$listtext.="<span class='info_updatetime'>".$lang_AddDate."</span>";
	if($timelong==1)$listtext.="<span class='info_validity'>".$lang_Validity."</span>";
	if($cvonline==1)$listtext.="<span class='info_cv'>".$lang_cvtitle."</span>";
	$listtext.="<span class='info_detail'>".$lang_Detail."</span>";
	$listtext.="</li>\n";
  }
 $i=0;
 foreach($job_list as $key=>$val){
 $i++;
 if(intval($titlenum)<>0)$val[position]=utf8substr($val[position], 0, $titlenum); 
 if(intval($desnum)<>0)$val[description]=utf8substr($val[description], 0, $desnum); 
 $listtext.="<li>";
 $listtext.="<span  class='info_title'>";
 if($listnav!=1)$listtext.="<b>".$lang_Position."</b>:";
  $listtext.="<a href=".$val[url];
 if($newwindow==1)$listtext.=" target='_blank' ";
 if($val[top_ok]==1)$listtext.=" style='color:".$topcolor.";'";
 $listtext.="  title='".$val[position]."' >".$val[position]."</a></span>";
 if($filesize==1)$listtext.="<span class='info_filesize'>";
 if($person==1){
   $listtext.="<span class='info_person'>";
  if($listnav!=1)$listtext.="<b>".$lang_PersonNumber."</b>:";
   $listtext.=$val[count]."</span>";
  }
  if($address==1){
    $listtext.="<span class='info_address'>";
  if($listnav!=1)$listtext.="<b>".$lang_WorkPlace."</b>:";
   $listtext.=$val[place]."</span>";
  }
  if($deal==1){
   $listtext.="<span class='info_deal'>";
  if($listnav!=1)$listtext.="<b>".$lang_Deal."</b>:";
   $listtext.=$val[deal]."</span>";
  }
  if($time==1){
   $listtext.="<span class='info_updatetime'>";
  if($listnav!=1)$listtext.="<b>".$lang_AddDate."</b>:";
   $listtext.=$val[addtime]."</span>";
  }
   if($timelong==1){
   $listtext.="<span class='info_validity''>";
  if($listnav!=1)$listtext.="<b>".$lang_Validity."</b>:";
   $listtext.=$val[useful_life]."</span>";
  }
   if($cvonline==1)$listtext.="<span class='info_cv''><a href=".$val[cv]." target='_blank'>".$lang_cvtitle."</a></span>";
   $listtext.="<span class='info_detail'><a href=".$val[url];
   if($newwindow==1)$listtext.=" target='_blank' ";
   $listtext.=" >".$lang_Detail."</a></span>";
 if($top==1)$listtext.=$val[top];
 if($news==1)$listtext.=$val[news];
 $listtext.="</li>\n";
 if($max&&$i>=$max)break;
 }
 $listtext.="</ul>";
 return $listtext;
 }

function methtml_showjob(){
 global $job_list,$lang_Detail,$lang_job,$lang_Position,$lang_PersonNumber,$lang_WorkPlace,$lang_Deal,$lang_AddDate,$lang_Validity,$lang_cvtitle;
 global $cv,$job;
  $listtext.="<ul>\n";
  $listtext.="<li class='info_person'><b>".$lang_PersonNumber."</b>:".$job[count]."</li>\n";
  $listtext.="<li class='info_address'><b>".$lang_WorkPlace."</b>:".$job[place]."</li>\n";
  $listtext.="<li class='info_deal'><b>".$lang_Deal."</b>:".$job[deal]."</li>\n";
  $listtext.="<li class='info_updatetime'><b>".$lang_AddDate."</b>:".$job[addtime]."</li>\n";
  $listtext.="<li class='info_validity'><b>".$lang_Validity."</b>:".$job[useful_life]."</li>\n";
  $listtext.="<span class='info_cv'><a href=".$job[cv]." target='_blank'>".$lang_cvtitle."</a></span>";
  $listtext.="</ul>\n"; 
  return $listtext;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>