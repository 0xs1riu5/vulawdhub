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
$ipok=$db->get_one("select * from $met_feedback where ip='$ip' order by addtime desc");
if($ipok)
$time1 = strtotime($ipok[addtime]);
else
$time1 = 0;
$time2 = strtotime($m_now_date);
$timeok= (float)($time2-$time1);

if($timeok<=$met_fd_time){
$fd_time="{$lang_Feedback1}".$met_fd_time."{$lang_Feedback2}";
okinfo('javascript:history.back();',$fd_time);
}
$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=8 order by no_order";
if($met_member_use)$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=8  and access<=$metinfo_member_type order by no_order";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
$list[para]="para".$list[id];
$fd_para[]=$list;
}
$fdstr = $met_fd_word; 
$fdarray=explode("|",$fdstr);
$fdarrayno=count($fdarray);
$fdok=false;
foreach($fd_para as $key=>$val){
$para="para".$val[id];
$content=$content."-".$$para;
}
for($i=0;$i<$fdarrayno;$i++){ 
if(strstr($content, $fdarray[$i])){
$fdok=true;
$fd_word=$fdarray[$i];
break;
}
}

$fd_word="[".$fd_word."] {$lang_Feedback3}";
if($fdok==true)okinfo('javascript:history.back();',$fd_word);
$query = "SELECT * FROM $met_parameter where lang='$lang' and module=8 order by no_order";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
 if($list[type]==4){
  $query1 = " where lang='$lang' and bigid='".$list[id]."'";
  $total_list[$list[id]] = $db->counter($met_list, "$query1", "*");
  } 
$list[para]="para".$list[id];
$feedback_para[]=$list;
}
require_once '../feedback/uploadfile_save.php';
$customerid=$metinfo_member_name!=''?$metinfo_member_name:0;
$query = "update $met_feedback SET 
                             addtime    = '$m_now_date'			  
                             where id='$id' ";
$db->query($query);
foreach($feedback_para as $key=>$val){
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
    $query = "update $met_flist SET
					  paraid   ='$val[id]',
					  info     ='$para'
					  where listid=$id and paraid=$val[id]";
	if($val[type]==5 and $para=='')$query='';
    $db->query($query);
 }
okinfo('feedback.php?lang='.$lang,$lang_js21);
}else{
$feedback_list=$db->get_one("select * from $met_feedback where id='$id'");
if(!$feedback_list){
okinfo('feedback.php?lang='.$lang,$lang_NoidJS);
}
if($feedback_list[readok]==1 || $feedback_list[useinfo]!='') okinfo('feedback.php?lang='.$lang,$lang_js24);
$query = "SELECT * FROM $met_parameter where lang='$lang' and module=8  order by no_order";
if($met_member_use)$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=8  and access<=$metinfo_member_type order by no_order";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
 if($list[type]==2 or $list[type]==4 or $list[type]==6){
  $query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
  $result1 = $db->query($query1);
  while($list1 = $db->fetch_array($result1)){
  $paravalue[$list[id]][]=$list1;
  }}
$value_list=$db->get_one("select * from $met_flist where paraid=$list[id] and listid=$id "); 
$list[content]=$value_list[info];
$list[mark]=$list[name];
$list[para]="para".$list[id];
if($list[wr_ok]=='1')
{
	$list[wr_must]="*";
	$fdwr_list[]=$list;
}
$feedback_para[]=$list;
}

$fdjs="<script language='javascript'>";
$fdjs=$fdjs."function Checkfeedback(){ ";
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

$mfname='feedback_editor';
include template('member');
footermember();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>