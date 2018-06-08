<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../include/common.inc.php';

if($action=="add"){
$settings = parse_ini_file('../config/job_'.$lang.'.inc.php');
@extract($settings);
if($met_memberlogin_code==1){
 require_once 'captcha.class.php';
 $Captcha= new  Captcha();
 if(!$Captcha->CheckCode($code)){
 echo("<script type='text/javascript'> alert('$lang_membercode');window.history.back();</script>");
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
$timeok2=(float)($time2-$_COOKIE['submit']);
if($timeok<=$met_cv_time&&$timeok2<=$met_cv_time){
$fd_time="{$lang_Feedback1}".$met_cv_time."{$lang_Feedback2}";
okinfo('javascript:history.back();',$fd_time);
}

$query = "SELECT * FROM $met_parameter where lang='$lang' and module=6 order by no_order";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
 if($list[type]==4){
  $query1 = " where lang='$lang' and bigid='".$list[id]."'";
  $total_list[$list[id]] = $db->counter($met_list, "$query1", "*");
  } 
$list[para]="para".$list[id];
$cv_para[]=$list;
}
$cvstr = $met_cv_word; 
$cvarray=explode("|",$cvstr);
$cvarrayno=count($cvarray);
$cvok=false;
foreach($cv_para as $key=>$val){
$para="para".$val[id];
$content=$content."-".$$para;
}
for($i=0;$i<$cvarrayno;$i++){ 
if(strstr($content, $cvarray[$i])){
$cvok=true;
$cv_word=$cvarray[$i];
break;
}
}
$cvto="para".$met_cv_email;
$cvto=$$cvto;
$cv_word="工作简历中不能包含 [".$cv_word."] ";
if($cvok==true)okinfo('javascript:history.back();',$cv_word);
setcookie('submit',$time2);
require_once '../include/jmail.php';	 
require_once 'uploadfile_save.php';
$customerid=$metinfo_member_name!=''?$metinfo_member_name:0;
if($met_cv_type==1 or $met_cv_type==2){
$query = "INSERT INTO $met_cv SET addtime = '$m_now_date', customerid = '$customerid',jobid=$jobid,lang='$lang',ip='$ip' ";
$db->query($query);
$later_cv=$db->get_one("select * from $met_cv where lang='$lang' order by addtime desc");
$id=$later_cv[id];
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
	$para=strip_tags($para);
    $query = "INSERT INTO $met_plist SET
                      listid   ='$id',
					  paraid   ='$val[id]',
					  info     ='$para',
					  module   ='6',
					  lang     ='$lang'";
         $db->query($query);
 }
}
if($met_cv_type==0 or $met_cv_type==2){
    $job_list = $db->get_one("SELECT * FROM $met_job WHERE id='$jobid'");
    $from=$met_fd_usename;
    $fromname=$met_fd_fromname;
    $to=$met_cv_emtype?($job_list[email]!=''?$job_list[email]:$met_cv_to):$met_cv_to;
    $usename=$met_fd_usename;
    $usepassword=$met_fd_password;
    $smtp=$met_fd_smtp;
    $title=$lang_cv2.$job_list[position].'('.$met_weburl.')';
	$body = '<style type="text/css">'."\n";
	$body .= 'table.metinfo_cv{ width:500px; border:1px solid #999; margin:10px auto; color:#555; font-size:12px; line-height:1.8;}'."\n";
	$body .= 'table.metinfo_cv td.title{ background:#999; font-size:14px; text-align:center; padding:2px 5px; font-weight:bold; color:#fff;}'."\n";
	$body .= 'table.metinfo_cv td.l{ width:20%; background:#f4f4f4; text-align:right; padding:2px 5px; font-weight:bold;}'."\n";
	$body .= 'table.metinfo_cv td.r{ background:#fff; text-align:left; padding:2px 5px; }'."\n";
	$body .= 'table.metinfo_cv td.pc{ text-align:right; width:25%; padding:0px;}'."\n";
	$body .= 'table.metinfo_cv td.pc img{ border:1px solid #999; padding:1px; margin:3px;}'."\n";
	$body .= 'table.metinfo_cv td.footer{ text-align:center; padding:0px; font-size:11px; color:#666; background:#f4f4f4; border-top:1px dotted #999;}'."\n";
	$body .= 'table.metinfo_cv td.footer a{  color:#666; }'."\n";
	$body .= '</style>'."\n";
	$body .= '<table cellspacing="1" cellpadding="2" class="metinfo_cv">'."\n";
	$body_title=$cv_para[0][para];
	$body_title=$$body_title;
	$body .= '<tr><td class="title" colspan="3">'.$body_title.'的简历</td></tr>'."\n";
$j=0;
if($met_cv_image){
foreach($cv_para as $key=>$val){
    if($val[id]==$met_cv_image){
	    $imgurl = $$val[para];
		break;
	}
}
$imgurl=explode('../',$imgurl);
}
$bt=$imgurl[1]!=''?'<td class="pc" rowspan="5">'.'<img src="'.$met_weburl.$imgurl[1].'" width="140" height="160" /></td>':'';
foreach($cv_para as $key=>$val){
$j++;
if($j>1)$bt = '';
    if($val[type]!=4){
	  $para=$$val[para];
	}else{
	  $para="";
	  for($i=1;$i<=$$val[para];$i++){
	  $para1="para".$val[id]."_".$i;
	  $para2=$$para1;
	  $para=($para2<>"")?$para.$para2."-":$para;
	  }
	  $para=substr($para, 0, -1);
	}
	$para=strip_tags($para);

if($val[type]!=5){
$body=$body.'<tr><td class="l">'.$val[name].'</td><td class="r">'.$para.'</td>'.$bt.'</tr>'."\n";
}else{
if($met_cv_image!=$val[id]){
$para=explode('../',$para);
$para=$para[1]<>""?"<a href=".$met_weburl.$para[1]." trage='_blank' style='color:#f00;' >".$lang_Download."</a>":$lang_Emptyno;
$body=$body.'<tr><td class="l">'.$val[name].'</td><td class="r">'.$para.'</td>'.$bt.'</tr>'."\n";
}
}
}
$body.='<tr><td class="footer" colspan="3">Powered by <a target="_blank" href="http://www.metinfo.cn">MetInfo '.$metcms_v.'</a> &copy;2008-2011 &nbsp;<a target="_blank" href="http://www.metinfo.cn">MetInfo Inc.</a></td></tr>';
$body.='</table>';
if($met_cv_back==1){
jmailsend($from,$fromname,$cvto,$met_cv_title,$met_cv_content,$usename,$usepassword,$smtp);
}
jmailsend($from,$fromname,$to,$title,$body,$usename,$usepassword,$smtp); 
}
/*短信提醒*/
if($met_nurse_job){
require_once ROOTPATH.'include/export.func.php';
if(maxnurse()<$met_nurse_max){
$domain = strdomain($met_weburl);
$message="您网站[{$domain}]收到了新的简历[{$job_list[position]}]，请尽快登录网站后台查看";
sendsms($met_nurse_job_tel,$message,4);
}
}
/**/
$backurl=$metinfo_member_name==""?'../index.php?lang='.$lang:'../member/'.$member_index_url;					  
okinfo($backurl,$lang_js21);
}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
