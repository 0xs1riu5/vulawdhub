<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once 'login_check.php';
require_once ROOTPATH.'member/index_member.php';
if($action=="editor"){
	//code
     if($met_memberlogin_code==1){
         require_once 'captcha.class.php';
         $Captcha= new  Captcha();
         if(!$Captcha->CheckCode($code)){
         echo("<script type='text/javascript'> alert('$lang_membercode');window.history.back();</script>");
		       exit;
         }
     }
$query = "update $met_message SET
                      name               = '{$messagename}',
					  tel            	 = '$tel',
					  email              = '$email',
					  contact			 = '$contact',
					  info  			 = '$info'
					  where id='$id'";

$db->query($query);
okinfo('message.php?lang='.$lang,$lang_js21);
}else{
$message_list=$db->get_one("select * from $met_message where id='$id'");
if(!$message_list){
okinfo('message.php?lang='.$lang, $lang_js1);
}
if($message_list[readok]==1 || $message_list[useinfo]!='') okinfo('message.php?lang='.$lang,$lang_js24);

if(!$message_list){
okinfo('message.php?lang='.$lang, $lang_js1);
}
$mfname='message_editor';
include template('member');
footermember();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>