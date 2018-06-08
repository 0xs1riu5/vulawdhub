<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once 'login_check.php';
require_once ROOTPATH.'member/index_member.php';
if($action=="edit"){
	//code
     if($met_memberlogin_code==1){
         require_once 'captcha.class.php';
         $Captcha= new  Captcha();
         if(!$Captcha->CheckCode($code)){
         echo("<script type='text/javascript'> alert('$lang_membercode'); window.history.back();</script>");
		       exit;
         }
     }
$ip=$m_user_ip;
$addtime=$m_now_date;
$ipok=$db->get_one("select * from $met_cv where ip='$ip' order by addtime desc");
if($ipok)
$time1 = strtotime($ipok[addtime]);
else
$time1 = 0;
$time2 = strtotime($m_now_date);
$timeok= (float)($time2-$time1);
if($timeok<=20){
$fd_time="{$lang_Feedback1} 20 {$lang_Feedback2}";
okinfo('javascript:history.back();',$fd_time);
}
$query = "SELECT * FROM $met_parameter where lang='$lang' and module=6 order by no_order";
if($met_member_use)$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=6  and access<=$metinfo_member_type order by no_order";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
 if($list[type]==4){
  $query1 = " where lang='$lang' and bigid='".$list[id]."'";
  $total_list[$list[id]] = $db->counter($met_list, "$query1", "*");
  } 
$list[para]="para".$list[id];
$cv_para[]=$list;
}
require_once '../job/uploadfile_save.php';
if(!is_numeric($jobid))okinfo('cv.php?lang='.$lang,$lang_js1);
$customerid=$metinfo_member_name!=''?$metinfo_member_name:0;
$query = "update $met_cv SET ";
$query = $query." addtime = '$m_now_date',jobid=$jobid ";			  
$query = $query." where id='$id' ";
$db->query($query);
foreach($cv_para as $key=>$val){
    if($val[type]!=4){
	  $para=$$val[para];
	}else{
	  $para="";
	  for($i=1;$i<=$total_list[$val[id]];$i++){
	  $para1="para".$val[id]."_".$i;
	  $para2=$$para1;
	  $para=($para2<>"")?$para.$para2."-":$para;
	  }
	  $para=substr($para, 0, -1);
	}
	$para=htmlspecialchars($para);
    $query = "update $met_plist SET
					  paraid   ='$val[id]',
					  info     ='$para'
					  where listid=$id and paraid=$val[id]";
	if($val[type]==5 and $para=='')$query='';
    $db->query($query);
 }
okinfo('cv.php?lang='.$lang,$lang_js21);
}else{
$cv_list=$db->get_one("select * from $met_cv where id='$id'");
if(!$cv_list){
okinfo('cv.php?lang='.$lang,$lang_NoidJS);
}
if($cv_list[readok]==1) okinfo('cv.php?lang='.$lang,$lang_js24);
$query = "SELECT * FROM $met_parameter where lang='$lang' and module=6  order by no_order";
if($met_member_use)$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=6  and access<=$metinfo_member_type order by no_order";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
 if($list[type]==2 or $list[type]==4 or $list[type]==6){
  $query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
  $result1 = $db->query($query1);
  while($list1 = $db->fetch_array($result1)){
  $paravalue[$list[id]][]=$list1;
  }}
$value_list=$db->get_one("select * from $met_plist where paraid=$list[id] and listid=$id "); 
$list[content]=$value_list[info];
$list[mark]=$list[name];
$list[para]="para".$list[id];
if($list[wr_ok]=='1')
{
	$list[wr_must]="*";
	$fdwr_list[]=$list;
}
$cv_para[]=$list;
}

$fdjs="<script language='javascript'>";
$fdjs=$fdjs."function Checkcv(){ ";
foreach($fdwr_list as $key=>$val){
if($val[type]==1 or $val[type]==2 or $val[type]==3 or $val[type]==5){
$fdjs=$fdjs."if (document.myform.para$val[id].value.length == 0) {\n";
$fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
$fdjs=$fdjs."document.myform.para$val[id].focus();\n";
$fdjs=$fdjs."return false;}\n";
}elseif($val[type]==4){
 $lagerinput="";
 for($j=1;$j<=count($paravalue[$val[id]]);$j++){
 $lagerinput=$lagerinput."document.myform.para$val[id]_$j.checked ||";
 }
 $lagerinput=$lagerinput."false\n";
 $fdjs=$fdjs."if(!($lagerinput)){\n";
 $fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
 $fdjs=$fdjs."document.myform.para$val[id]_1.focus();\n";
 $fdjs=$fdjs."return false;}\n";
}
}
$fdjs=$fdjs."}</script>";
	 
$selectjob = "";
	$serch_sql=" where lang='$lang' ";
	$metinfo_member_type=intval($metinfo_member_type);
	$query = "SELECT id,position FROM $met_job $serch_sql and  access <= $metinfo_member_type and ((TO_DAYS(NOW())-TO_DAYS(`addtime`)< useful_life) OR useful_life=0)";
    
	$result = $db->query($query);
	 while($list= $db->fetch_array($result)){
	 $selectok=$selectedjob==$list[id]?"selected='selected'":"";	 
	 $selectjob.="<option value='$list[id]' $selectok>{$list[position]}</option>";
	 }


$mfname='cv_editor';
include template('member');
footermember();}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>