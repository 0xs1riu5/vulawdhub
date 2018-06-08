<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../include/common.inc.php';
if(!is_numeric($abt_type)&&$abt_type!='')die();
if($p){
   $array = explode('.',base64_decode($p));
   $array[0]=daddslashes($array[0]);
   $sql="SELECT * FROM $met_admin_table WHERE admin_id='".$array[0]."'";
   $sqlarray = $db->get_one($sql);
   $passwords=$sqlarray[admin_pass];
   $checkCode = md5($array[0].'+'.$passwords);
   if($array[1]!=$checkCode){
        okinfo('../admin/getpassword.php',$lang_dataerror);
   }
   if(!$action){
	   $action='next3';
	   $abt_type=2;
	   $nbers[1]=$sqlarray[admin_id];
   }
}
function generate_password($length) {
    $chars = "0123456789";
    $password = '';
    for ( $i = 0; $i < $length; $i++ ) {
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $password;
}
$description=$lang_password1;
switch($action){
	case 'next1':
		if($abt_type==1){
			$description=$lang_password2;
			$title=$lang_password3;
		}else{
			$description=$lang_password4;
			$title=$lang_password5;
		}
	break;
	case 'next2':
		if($abt_type==1){
			if($met_smspass){
				$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$admin_mobile' and usertype='3'");
				if($admin_list && $admin_list['admin_mobile']=='')okinfo('../admin/getpassword.php',$lang_password6);
				if(!$admin_list){
					if(!preg_match("/^((\(\d{2,3}\))|(\d{3}\-))?1(3|5|8|9)\d{9}$/",$admin_mobile))okinfo('../admin/getpassword.php',$lang_password7);
					$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_mobile='$admin_mobile' and usertype='3'");
					if(!$admin_list)okinfo('../admin/getpassword.php',$lang_password8);
				}
				$code=generate_password(6);
				$nber=generate_password(2);
				$cnde=$code.'-'.$nber.'-'.$admin_list['admin_id'];
				/*发送短信*/
				require_once ROOTPATH.'include/export.func.php';
				$domain = strdomain($met_weburl);
				$message="$lang_password9{$code}$lang_password10{$nber}[{$domain}]";
				$smsok=sendsms($admin_list['admin_mobile'],$message,5);
				if($smsok=='SUCCESS'){
					$mobile = substr($admin_list['admin_mobile'],0,3).'****'.substr($admin_list['admin_mobile'],7,10);
					$description=$lang_password11.'<br/><span class="color999">'.$lang_password12.'</span>';
					$query = "delete from $met_otherinfo where lang = 'met_cnde'";				  
					$db->query($query);
					/*写入数据库*/
					$query = "INSERT INTO $met_otherinfo SET 
						authpass = '$cnde',
						lang     = 'met_cnde'";				  
					$db->query($query);
				}else{
					okinfo('getpassword.php',sedsmserrtype($smsok));
				}
			}else{
				okinfo('getpassword.php',$lang_password13);
			}
		}else{
			$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$admin_mobile' and usertype='3'");
			if($admin_list && $admin_list['admin_email']=='')okinfo('../admin/getpassword.php',$lang_password14);
			if(!$admin_list){
				if(!is_email($admin_mobile))okinfo('../admin/getpassword.php',$lang_password7);
				$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_email='$admin_mobile' and usertype='3'");
				if(!$admin_list)okinfo('../admin/getpassword.php',$lang_password14);
			}
			if($admin_list){
				$met_fd_usename=$met_fd_usename;
				$met_fd_fromname=$met_fd_fromname;
				$met_fd_password=$met_fd_password;
				$met_fd_smtp=$met_fd_smtp;
				$met_webname=$met_webname;
				$met_weburl=$met_weburl;
				$adminfile=$url_array[count($url_array)-2];
				$from=$met_fd_usename;
				$fromname=$met_fd_fromname;
				$to=$admin_list['admin_email'];
				$usename=$met_fd_usename;
				$usepassword=$met_fd_password;
				$smtp=$met_fd_smtp;
				$title=$met_webname.$lang_getNotice;
				$x = md5($admin_list[admin_id].'+'.$admin_list[admin_pass]);
				$String = base64_encode($admin_list[admin_id].".".$x);
				$mailurl= $met_weburl.$adminfile.'/admin/getpassword.php?p='.$String;
				$body ="<style type='text/css'>\n";
				$body .="#metinfo{ padding:10px; color:#555; font-size:12px; line-height:1.8;}\n";
				$body .="#metinfo .logo{ border-bottom:1px dotted #333; padding-bottom:5px;}\n";
				$body .="#metinfo .logo img{ border:none;}\n";
				$body .="#metinfo .logo a{ display:block;}\n";
				$body .="#metinfo .text{ border-bottom:1px dotted #333; padding:5px 0px;}\n";
				$body .="#metinfo .text p{ margin-bottom:5px;}\n";
				$body .="#metinfo .text a{ color:#70940E;}\n";
				$body .="#metinfo .copy{ color:#BBB; padding:5px 0px;}\n";
				$body .="#metinfo .copy a{ color:#BBB; text-decoration:none; }\n";
				$body .="#metinfo .copy a:hover{ text-decoration:underline; }\n";
				$body .="#metinfo .copy b{ font-weight:normal; }\n";
				$body .="</style>\n";
				$body .="<div id='metinfo'>\n";
				$body .="<div class='logo'><a href='$met_weburl' title='$met_webname'><img src='http://www.metinfo.cn/upload/200911/1259148297.gif' /></a></div>";
				$body .="<div class='text'><p>".$lang_hello.$admin_name."</p><p>$lang_getTip1</p>";
				$body .="<p><a href='$mailurl'>$mailurl</a></p>\n";
				$body .="<p>$lang_getTip2</p></div><div class='copy'>$foot</a></div>";
				require_once ROOTPATH.'include/export.func.php';
				$post=array('to'=>$to,'title'=>$title,'body'=>$body);
				$met_file='/passwordmail.php';
				$sendMail=curl_post($post,30);
				if($sendMail=='nohost')$sendMail=0;	
				$text=$sendMail?$lang_getTip3.$lang_memberEmail.'：'.$admin_list['admin_email']:$lang_getTip4;
				okinfo('../index.php',$text);
			}
		}
	break;
	case 'next3':
		if($abt_type==1){
			if(!$checkcode)okinfo('javascript:history.back();',$lang_password15);
			$cnde=$checkcode.'-'.$nber;
			$codeok = $db->get_one("SELECT * FROM $met_otherinfo WHERE authpass='$cnde' and lang='met_cnde'");
			$nbers=explode('-',$nber);
			if($codeok){
				$description=$lang_password16;
			}else{
				$adminer = $db->get_one("SELECT * FROM $met_otherinfo WHERE authpass like '%$nbers[1]' and lang='met_cnde'");
				$authcode=$adminer[authcode]==''?1:$adminer[authcode]+1;
				if($authcode>5){
					$query = "delete from $met_otherinfo where id='$adminer[id]' and lang='met_cnde'";
					$db->query($query);
					okinfo('../admin/getpassword.php',$lang_password17);
					die;
				}else{
					$query="update $met_otherinfo set
					   authcode='$authcode'
					   where id='$adminer[id]'";
					$db->query($query);
					okinfo('javascript:history.back();',$lang_password18);
				}
			}
		}else{
			$description=$lang_password16;
		}
	break;
	case 'next4':
		if($abt_type==1){
			$codeok = $db->get_one("SELECT * FROM $met_otherinfo WHERE authpass='$cnde' and lang='met_cnde'");
			$cndes=explode('-',$cnde);
			if($codeok){
				if($password=='')okinfo('javascript:history.back();',$lang_dataerror);
				if($passwordsr!=$password)okinfo('javascript:history.back();',$lang_js6);
				$password = md5($password);
				$query="update $met_admin_table set
				   admin_pass='$password'
				   where admin_id='$cndes[2]'";
				$db->query($query);
				$query = "delete from $met_otherinfo where authpass='$cnde' and lang='met_cnde'";
				$db->query($query);
				okinfo('../index.php',$lang_jsok);
			}else{
				okinfo('../admin/getpassword.php',$lang_password19);
			}	
		}else{
			if($password=='')okinfo('javascript:history.back();',$lang_dataerror);
			if($passwordsr!=$password)okinfo('javascript:history.back();',$lang_js6);
			$password = md5($password);
			$array = explode('.',base64_decode($p));
			$array[0]=daddslashes($array[0]);
			$query="update $met_admin_table set
			   admin_pass='$password'
			   where admin_id='$array[0]'";
			$db->query($query);
			okinfo('../index.php',$lang_jsok);
		}
	break;
	default :
		if($action!=''){
			die();
		}
	break;	
}
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('getpassword');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>