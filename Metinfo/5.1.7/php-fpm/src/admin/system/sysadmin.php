<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../login/login_check.php';
if($action=='list'){
require_once ROOTPATH.'include/export.func.php';
$met_host='www.metinfo.cn';
$met_file ='/metv5news.php';
$post=array('fromurl'=>$met_weburl);
$metinfo=curl_post($post,30);
if($metinfo=='nohost'){
	echo '无法连接上服务器';
}
else{
	echo $metinfo;
}
die;
}
function getOS($strAgent){
$os = false;
if(eregi('win',$strAgent) && strpos($strAgent,'95')){
$os = 'Windows 95';
} else if(eregi('win 9x',$strAgent) && strpos($strAgent,'4.90')){
$os = 'Windows ME';
} else if(eregi('win',$strAgent) && eregi('98',$strAgent)){
$os = 'Windows 98';
} else if(eregi('win',$strAgent) && eregi('nt 6.0',$strAgent)){
$os = 'Windows Vista';
} else if(eregi('win',$strAgent) && eregi('nt 5.2',$strAgent)){
$os = 'Windows 2003 Server';
} else if(eregi('win',$strAgent) && eregi('nt 5.1',$strAgent)){
$os = 'Windows XP';
} else if(eregi('win',$strAgent) && eregi('nt 5',$strAgent)){
$os = 'Windows 2000';
} else if(eregi('win',$strAgent) && eregi('nt',$strAgent)){
$os = 'Windows NT';
} else if(eregi('win',$strAgent) && eregi('32',$strAgent)){
$os = 'Windows 32';
} else if(eregi('linux',$strAgent)){
$os = 'Linux';
} else if(eregi('unix',$strAgent)){
$os = 'Unix';
} else if(eregi('sun',$strAgent) && eregi('os',$strAgent)){
$os = 'SunOS';
} else if(eregi('ibm',$strAgent) && eregi('os',$strAgent)){
$os = 'IBM OS/2';
} else if(eregi('mac',$strAgent) && eregi('pc',$strAgent)){
$os = 'Macintosh';
} else if(eregi('powerpc',$strAgent)){
$os = 'PowerPC';
} else if(eregi('aix',$strAgent)){
$os = 'AIX';
} else if(eregi('HPUX',$strAgent)){
$os = 'HPUX';
} else if(eregi('netbsd',$strAgent)){
$os = 'NetBSD';
} else if(eregi('bsd',$strAgent)){
$os = 'BSD';
} else if(eregi('OSF1',$strAgent)){
$os = 'OSF1';
} else if(eregi('IRIX',$strAgent)){
$os = 'IRIX';
} else if(eregi('FreeBSD',$strAgent)){
$os = 'FreeBSD';
} else if(eregi('teleport',$strAgent)){
$os = 'teleport';
} else if(eregi('flashget',$strAgent)){
$os = 'flashget';
} else if(eregi('webzip',$strAgent)){
$os = 'webzip';
} else if(eregi('offline',$strAgent)){
$os = 'offline';
} else{
$os = 'Unknown OS';
}
return $os;
}
$Agent = $_SERVER['HTTP_USER_AGENT'];
$met_sever1 = $_SERVER['SERVER_SOFTWARE'];
$xitong = getOS($Agent);
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$metinfo_admin_name'");
foreach($admin_list as $_key => $_value) {
	if($_key!='lang')$$_key = daddslashes($_value);
}
$st=statime("Y-m-d");
$et=statime("Y-m-d");
$query="select * from {$met_visit_summary} WHERE stattime ='{$st}' and stattime ='{$et}'";
$visit= $db->get_one($query);
$visit[pv]=$visit[pv]?$visit[pv]:0;
$visit[alone]=$visit[alone]?$visit[alone]:0;
$visit[ip]=$visit[ip]?$visit[ip]:0;
$SERVER_SIGNATURE1=$_SERVER['SERVER_SIGNATURE'];
$mysql1=mysql_get_server_info();
$feedback = $db->counter($met_feedback, " where readok=0 and lang='$lang' ", "*");
$message = $db->counter($met_message, " where readok=0 and lang='$lang' ", "*"); 
$link = $db->counter($met_link, " where show_ok=0 and lang='$lang' ", "*");
$member = $db->counter($met_admin_table, " where admin_approval_date is null and lang='$lang' and usertype<3 ", "*");
$new_metcms_v=!$met_newcmsv?$lang_metcmsnew1:$lang_metcmsnew2;
$new_metcms_v='<span style="color:#FF0000;">'.$new_metcms_v.'</span>';
include template('system/sysadmin');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>