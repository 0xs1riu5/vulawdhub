<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($action=='email'){
	if(!get_extension_funcs('openssl')&&stripos($smtp,'.gmail.com')!==false){$metinfo='<span style="color:#f00;">'.'gmail邮箱需要空间支持SSL，请开启SSL，或换成其他邮箱！！！'.'</span>';echo $metinfo;die();}
	if(!function_exists('fsockopen')&&!function_exists('pfsockopen')&&!function_exists('stream_socket_client')){
		$metinfo='<span style="color:#f00;">'.$lang_basictips1.'</span>';
		$metinfo.='<span style="color:#090;">'.$lang_basictips2.'</span>';
	}else{
		ini_set("max_execution_time", "30000");
		require_once ROOTPATH.'include/jmail.php';
		/*jmailsend('发件人账号','发件人姓名','收件人帐号','邮件标题','内容','邮箱账号','邮箱密码','smtp服务器');*/
		if($usename&&$fromname&&$password&&$smtp){
			$emailok=jmailsend($usename,$fromname,$usename,$lang_basictips3,$lang_basictips4,$usename,$password,$smtp);
		}
		//die();
		if(!$emailok){
			$metinfo='<span style="color:#f00;">'.$lang_basictips5.'</span>';
			$metinfo.='<span style="color:#090;">'.$lang_basictips6.'</span>';
		}
		else{
			$metinfo='<span style="color:#090">'.$lang_basictips7.'</span>';
		}		
	}
	echo $metinfo;
	die();
}
if($action=='modify'){
	if($cs==1){
		$met_weburl = ereg_replace(" ","",$met_weburl);
		if(substr($met_weburl,-1,1)!="/")$met_weburl.="/";
		if(!strstr($met_weburl,"http://"))$met_weburl="http://".$met_weburl;
		require_once ROOTPATH_ADMIN.'seo/404.php';
	}
	require_once $depth.'../include/config.php';
	if($cs==1){
		$query = "update $met_lang SET met_weburl = '$met_weburl' where lang='$lang'";
		$db->query($query);
	}
	$db->query("update $met_otherinfo set info1='',info2='' where id=1");
	metsave('../system/basic.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs.'&linkapi=1');
}else{
	$localurl="http://";
	$localurl.=$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
	$localurl_a=explode("/",$localurl);
	$localurl_count=count($localurl_a);
	$localurl_admin=$localurl_a[$localurl_count-3];
	$localurl_admin=$localurl_admin."/system/basic";
	$localurl_real=explode($localurl_admin,$localurl);
	$localurl=$localurl_real[0];
	if($met_weburl=="")$met_weburl=$localurl;
	$cs=isset($cs)?$cs:1;
	$listclass[$cs]='class="now"';
	$css_url="../templates/".$met_skin."/css";
	$img_url="../templates/".$met_skin."/images";
	if($linkapi==1){
	$email=$admin_list['admin_group']==10000?$admin_list['admin_email']:'';
	$tel=$admin_list['admin_group']==10000?$admin_list['admin_mobile']:'';
	$linkapijs="<script type=\"text/javascript\">
	$.ajax({
		url: 'http://api.metinfo.cn/record_install.php?url={$met_weburl}&email={$email}&webname={$met_webname}&webkeywords={$met_keywords}&tel={$tel}&version={$metcms_v}&softtype=1',
		type: \"POST\"
	});
	</script>
	";
	}
	include template('system/set_basic');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>