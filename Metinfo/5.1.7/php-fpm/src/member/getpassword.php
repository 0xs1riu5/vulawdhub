<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../include/common.inc.php';
require_once ROOTPATH.'member/index_member.php';
if($p){
   $array = explode('.',base64_decode($p));
   $array[0]=daddslashes($array[0]);
   $sql="SELECT * FROM $met_admin_table WHERE admin_id='".$array[0]."'";
   $sqlarray = $db->get_one($sql);
   $passwords=$sqlarray[admin_pass];
   $checkCode = md5($array[0].'+'.$passwords);
   if($array[1]!=$checkCode){
        okinfo('basic.php?lang='.$lang,$lang_getTip8);
		die();
   }
   if($action == "MembersAction"){
        if($password=='' || $passworda!=$password)okinfo('javascript:history.back();',$lang_NewPassJS2);
        $password = md5($password);
		$array[0]=daddslashes($array[0]);
        $query="update $met_admin_table set
		   admin_pass='$password'
		   where admin_id='$array[0]'";
        $db->query($query);
		okinfo('basic.php?lang='.$lang,$lang_js21);
   }
	$mfname='getpassword';
	include template('member');
	footermember();
}else{
	if($action=="getpassword"){
		$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$admin_name' and admin_email='$admin_email'");
		if(!$admin_list)okinfo('getpassword.php?lang='.$lang,$lang_NoidJS1);
		$from=$met_fd_usename;
		$fromname=$met_fd_fromname;
		$to=$admin_list[admin_email];
		$usename=$met_fd_usename;
		$usepassword=$met_fd_password;
		$smtp=$met_fd_smtp;
		$met_webname1=$met_webname;
		$adminfile=$url_array[count($url_array)-2];
		$title=$met_webname1.$lang_getNotice;
		$x = md5($admin_name.'+'.$admin_list[admin_pass]);
		$String = base64_encode($admin_name.".".$x);
		$mailurl= $met_weburl.$adminfile.'member/getpassword.php?lang='.$lang.'&p='.$String;
		$body  ="<style type='text/css'>\n";
		$body .="#metinfo{ padding:10px; color:#555; font-size:12px; line-height:1.8;}\n";
		$body .="#metinfo .text{ border-top:1px dotted #333; border-bottom:1px dotted #333; padding:5px 0px;}\n";
		$body .="#metinfo .text p{ margin-bottom:5px;}\n";
		$body .="#metinfo .text a{ color:#70940E; }\n";
		$body .="#metinfo .copy{ color:#BBB; padding:5px 0px;}\n";
		$body .="#metinfo .copy a{ color:#BBB; text-decoration:none; }\n";
		$body .="#metinfo .copy a:hover{ text-decoration:underline; }\n";
		$body .="#metinfo .copy b{ font-weight:normal; }\n";
		$body .="</style>\n";
		$body .="<div id='metinfo'>\n";
		$body .="<p>".$lang_hello.$admin_name."</p>\n";
		$body .="<div class='text'><p>$lang_getTip1</p>";
		$body .="<p><a href='$mailurl'>$mailurl</a></p>\n";
		$body .="<div class='copy'>$met_webname1</a></div>";
		require_once '../include/jmail.php';
		$sendMail=jmailsend($from,$fromname,$to,$title,$body,$usename,$usepassword,$smtp);
		$text=$sendMail?$lang_getTip2:$lang_getTip3;
		okinfo('login.php?lang='.$lang,$text);
	}else{
		$mfname='getpassword';
		include template('member');
		footermember();
	}
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>