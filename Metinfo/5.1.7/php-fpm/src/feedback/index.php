<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.  
require_once '../include/common.inc.php';
if(!$id){
	$filpy = basename(dirname(__FILE__));
	$nwid=$db->get_one("SELECT * FROM $met_column WHERE module='8' and foldername='$filpy' and lang='$lang'");
	$id=$nwid['id'];
}
$classaccess= $db->get_one("SELECT * FROM $met_column WHERE module='8' and lang='$lang' and id='$id'");
$metaccess=$classaccess[access];
$class1=$classaccess[id];
foreach($settings_arr as $key=>$val){
	if($val['columnid']==$class1){
		$tingname    =$val['name'].'_'.$val['columnid'];
		$$val['name']=$$tingname;
	}
}
require_once ROOTPATH.'include/head.php';
	$class1_info=$class_list[$class1][releclass]?$class_list[$class_list[$class1][releclass]]:$class_list[$class1];
	$class2_info=$class_list[$class1][releclass]?$class_list[$class1]:$class_list[$class2];
$fromurl=$_SERVER['HTTP_REFERER'];
$fromurl=daddslashes($fromurl);
$ip=$m_user_ip;
if($title==""){
$navtitle=$met_fdtable;
$title=$navtitle;
}
else{
$navtitle="[".$title."]".$met_fdtable;
}
if($action=="add"){
if(!$met_fd_ok)okinfo('javascript:history.back();',"{$lang_Feedback5}");
if($met_memberlogin_code==1){
	require_once ROOTPATH."{$met_adminfile}/include/captcha.class.php";
	$Captcha= new  Captcha();
	if(!$Captcha->CheckCode($code)){
	echo("<script type='text/javascript'> alert('$lang_membercode');window.history.back();</script>");
	   exit;
	}
}
$sid = $id;
$addtime=$m_now_date;
$ipok=$db->get_one("select * from $met_feedback where ip='$ip' order by addtime desc");
if($ipok)
$time1 = strtotime($ipok['addtime']);
else
$time1 = 0;
$time2 = strtotime($m_now_date);
$timeok= (float)($time2-$time1);
$timeok2=(float)($time2-$_COOKIE['submit']);
if($timeok<=$met_fd_time||$timeok2<=$met_fd_time){
$fd_time="{$lang_Feedback1}".$met_fd_time."{$lang_Feedback2}";
okinfo('javascript:history.back();',$fd_time);
}
$query = "SELECT * FROM $met_parameter where lang='$lang' and module=8 and class1='$id' order by no_order";
if($met_member_use)$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=8 and class1='$id' and access<=$metinfo_member_type order by no_order";
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
$fd_word="{$lang_Feedback3} [".$fd_word."]";
if($fdok==true)okinfo('javascript:history.back();',$fd_word);
setcookie('submit',$time2);
require_once '../include/jmail.php';
require_once 'uploadfile_save.php';
$fdto="para".$met_fd_email;
$fdto=$$fdto;
$fdclass2="para".$met_fd_class;
$fdclass=$$fdclass2;
$title=$fdclass." - ".$fdtitle;
$from=$met_fd_usename;
$fromname=$met_fd_fromname;
$to=$met_fd_to;
$usename=$met_fd_usename;
$usepassword=$met_fd_password;
$smtp=$met_fd_smtp;
if($met_fd_type!=0){
if(!isset($metinfo_member_name) || $metinfo_member_name=='') $metinfo_member_name=0;
$query = "INSERT INTO $met_feedback SET
                      class1             = '$id',
                      fdtitle            = '$title',
					  fromurl            = '$fromurl',
					  ip                 = '$ip',
					  addtime            = '$addtime',
					  customerid         = '$metinfo_member_name',
					  lang               = '$lang'";					  
$db->query($query);
$id=mysql_insert_id();
foreach($fd_para  as $key=>$val){
    if($val[type]!=4){
	  $para=$$val[para];
	}else{
	  $para="";
	  for($i=1;$i<=$$val[para];$i++){
	  $para1p="para".$val[id]."_".$i;
	  $para2p=$$para1p;
	  $para=($para2p<>"")?$para.$para2p."-":$para;
	  }
	  $para=substr($para, 0, -1);
	}
	
	if($val[type]==5){$para="../upload/file/$para";}
	$para=strip_tags($para);
    $query = "INSERT INTO $met_flist SET
                      listid   ='$id',
					  paraid   ='$val[id]',
					  info     ='$para',
					  module   ='8',
					  lang     ='$lang'";
    $db->query($query);
 }
}
/**/
$fname= $db->get_one("SELECT * FROM $met_column WHERE module='8' and lang='$lang' and id='$sid'");
$fedfilename=$fname['filename']!=''?$fname['filename']:'index';
$met_ahtmtype = $fname['filename']<>''?$met_chtmtype:$met_htmtype;
$returnurl=$met_pseudo?'index-'.$lang.'.html':($met_webhtm?$fedfilename.$met_ahtmtype:'index.php?lang='.$lang.'&id='.$sid);
if($fid_url)$returnurl=$_SERVER[HTTP_REFERER];

/*短信提醒*/
if($met_nurse_feed){
	require_once ROOTPATH.'include/export.func.php';
	if(maxnurse()<$met_nurse_max){
		$domain = strdomain($met_weburl);
		$message="您网站[{$domain}]收到了新的反馈信息[{$title}]，请尽快登录网站后台查看";
		sendsms($met_nurse_feed_tel,$message,4);
	}
}
/*邮件提醒*/
if($met_fd_type==0 or $met_fd_type==2){
foreach($fd_para as $key=>$val){
    if($val[type]!=4){
	  $para=$$val[para];
	}else{
	  $para="";
	  for($i=1;$i<=$$val[para];$i++){
	  $para1p="para".$val[id]."_".$i;
	  $para2p=$$para1p;
	  $para=($para2p<>"")?$para.$para2p."-":$para;
	  }
	  $para=substr($para, 0, -1);
	}
	$para=strip_tags($para);
if($val[type]!=5){
$body=$body."<b>".$val[name]."</b>:".$para."<br>";
}else{
$para=$para<>""?"<a href=".$met_weburl."upload/file/".$para." >".$met_weburl."upload/file/".$para."</a>":$para;
$body=$body."<b>".$val[name]."</b>:".$para."<br>";
}
}

$body=$body."<b>{$lang_FeedbackProduct}</b>:".$fdtitle."<br>";
$body=$body."<b>{$lang_IP}</b>:".$ip."<br>";
$body=$body."<b>{$lang_AddTime}</b>:".$addtime."<br>";
$body=$body."<b>{$lang_SourcePage}</b>:".$fromurl;
jmailsend($from,$fromname,$to,$title,$body,$usename,$usepassword,$smtp,$fdto);
}
if($met_fd_back==1){
jmailsend($from,$fromname,$fdto,$met_fd_title,$met_fd_content,$usename,$usepassword,$smtp);
}

okinfo($returnurl,"{$lang_Feedback4}");
}
else{


$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=8 and class1='$id' order by no_order";
if($met_member_use)$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=8 and class1='$id'  and access<=$metinfo_member_type order by no_order";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
 if($list[type]==2 or $list[type]==4 or $list[type]==6){
    $listinfo=$db->get_one("select * from $met_list where bigid='$list[id]' and no_order=99999");
	$listinfoid=intval(trim($listinfo[info]));
	if($listinfo){
	$listmarknow='metinfo';
	$classtype=($listinfo[info]=='metinfoall')?$listinfoid:($met_class[$listinfoid][releclass]?'class1':'class'.$class_list[$listinfoid][classtype]);
    $query1 = "select * from $met_product where lang='$lang' and $classtype='$listinfoid' order by updatetime desc";
   $result1 = $db->query($query1);
   $i=0;
   while($list1 = $db->fetch_array($result1)){
   	 $list1[info]=$list1[title];
	 $i++;
	 $list1[no_order]=$i;
   $paravalue[$list[id]][]=$list1;
   }
    }else{
   $query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
   $result1 = $db->query($query1);
   while($list1 = $db->fetch_array($result1)){
   $paravalue[$list[id]][]=$list1;
   }
   }}
if($list[wr_ok]=='1')$list[wr_must]="*";
switch($list[type]){
case 1:
$list[input]="<input name='para$list[id]' type='text' size='30' class='input-text' />";
break;
case 2:
$list[input]="<select name='para$list[id]'><option selected='selected' value=''>{$lang_Choice}</option>";
foreach($paravalue[$list[id]] as $key=>$val){
$list[input]=$list[input]."<option value='$val[info]'>$val[info]</option>";
}
$list[input]=$list[input]."</select>";
break;
case 3:
$list[input]="<textarea name='para$list[id]' class='textarea-text' cols='50' rows='5'></textarea>";
break;
case 4:
$i=0;
foreach($paravalue[$list[id]] as $key=>$val){
$i++;
$list[input]=$list[input]."<input name='para$list[id]_$i' class='checboxcss' id='para$i$list[id]' type='checkbox' value='$val[info]' /><label for='para$i$list[id]'>$val[info]</label>&nbsp;&nbsp;";
}
$list[input]=$list[input]."<input name='para$list[id]' type='hidden' value='$i' />";
$lagernum[$list[id]]=$i;
break;
case 5:
$list[input]="<input name='para$list[id]' type='file' class='input' size='20' >";
break;
case 6:
$i=0;
foreach($paravalue[$list[id]] as $key=>$val){
$checked='';
$i++;
if($i==1)$checked="checked='checked'";
$list[input]=$list[input]."<input name='para$list[id]' type='radio' id='para$i$list[id]' value='$val[info]' $checked /><label for='para$i$list[id]'>$val[info]</label>  ";
 }
break;
}
$fd_para[]=$list;
if($list[wr_ok])$fdwr_list[]=$list;
}
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

$class2=$class_list[$class1][releclass]?$class1:$class2;
$class1=$class_list[$class1][releclass]?$class_list[$class1][releclass]:$class1;
$class_info=$class2?$class2_info:$class1_info;
if($class2!=""){
$class_info[name]=$class2_info[name]."--".$class1_info[name];
}

     $show[description]=$class_info[description]?$class_info[description]:$met_keywords;
     $show[keywords]=$class_info[keywords]?$class_info[keywords]:$met_keywords;
	 $met_title=$met_title?$navtitle.'-'.$met_title:$navtitle;
	 if($class_info['ctitle']!='')$met_title=$class_info['ctitle'];
if(count($nav_list2[$classaccess[id]])){
$k=count($nav_list2[$class1]);
$nav_list2[$class1][$k]=$class1_info;
}
require_once '../public/php/methtml.inc.php';

     $methtml_feedback.=$fdjs;
     $methtml_feedback.="<form enctype='multipart/form-data' method='POST' name='myform' onSubmit='return Checkfeedback();' action='index.php?action=add&lang=".$lang."' target='_self'>\n";
     $methtml_feedback.="<table cellpadding='2' cellspacing='1'  bgcolor='#F2F2F2' align='center' class='feedback_table' >\n";
    foreach($fd_para as $key=>$val){
     $methtml_feedback.="<tr class=feedback_tr bgcolor='#FFFFFF'    height='25'  >\n";
     $methtml_feedback.="<td class=feedback_td1 align='right' width='20%'>".$val[name]."&nbsp;</td>\n";
     $methtml_feedback.="<td class=feedback_input width='70%'>".$val[input]."</td>\n";
     $methtml_feedback.="<td class=feedback_info style='color:#990000'>".$val[wr_must]."</td>\n";
     $methtml_feedback.="</tr>\n";
    }
if($met_memberlogin_code==1){  
     $methtml_feedback.="<tr><td class='text'>".$lang_memberImgCode."</td>\n";
     $methtml_feedback.="<td class='input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
     $methtml_feedback.="<img align='absbottom' src='../member/ajax.php?action=code'  onclick=this.src='../member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $methtml_feedback.="</td>\n";
     $methtml_feedback.="</tr>\n";
}
     $methtml_feedback.="<tr><td colspan='3' bgcolor='#FFFFFF' class=feedback_submit align='center'>\n";
     $methtml_feedback.="<input type='hidden' name='fdtitle' value='".$title."' />\n";
     $methtml_feedback.="<input type='hidden' name='fromurl' value='".$fromurl."' />\n";
     $methtml_feedback.="<input type='hidden' name='lang' value='".$lang."' />\n";
     $methtml_feedback.="<input type='hidden' name='ip' value='".$ip."' />\n";
	 $methtml_feedback.="<input type='hidden' name='totnum' value='".count($fd_para)."' />\n";
	 $methtml_feedback.="<input type='hidden' name='id' value='".$id."' />\n";
     $methtml_feedback.="<input type='submit' name='Submit' value='".$lang_Submit."' class='tj'>\n";
     $methtml_feedback.="<input type='reset' name='Submit' value='".$lang_Reset."' class='tj'></td></tr>\n";
     $methtml_feedback.="</table>\n";
     $methtml_feedback.="</form>\n";

include template('feedback');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>