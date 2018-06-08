<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
header("Content-type: text/html;charset=utf-8");
error_reporting(E_ERROR | E_WARNING | E_PARSE);
@set_time_limit(0);
set_magic_quotes_runtime(0);
define('VERSION','5.1.7');
if(PHP_VERSION < '4.1.0') {
	$_GET         = &$HTTP_GET_VARS;
	$_POST        = &$HTTP_POST_VARS;
	$_COOKIE      = &$HTTP_COOKIE_VARS;
	$_SERVER      = &$HTTP_SERVER_VARS;
	$_ENV         = &$HTTP_ENV_VARS;
	$_FILES       = &$HTTP_POST_FILES;
}
function randStr($i){
  $str = "abcdefghijklmnopqrstuvwxyz";
  $finalStr = "";
  for($j=0;$j<$i;$j++)
  {
    $finalStr .= substr($str,mt_rand(0,25),1);
  }
  return $finalStr;
}
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
isset($_REQUEST['GLOBALS']) && exit('Access Error');
foreach(array('_COOKIE', '_POST', '_GET') as $_request) {
	foreach($$_request as $_key => $_value) {
		$_key{0} != '_' && $$_key = daddslashes($_value);
	}
}
$m_now_time     = time();
$m_now_date     = date('Y-m-d H:i:s',$m_now_time);
$nowyear    = date('Y',$m_now_time);
$localurl="http://";
$localurl.=$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
$install_url=$localurl;

if(file_exists('../config/install.lock')){
	exit('对不起，该程序已经安装过了。<br/>
	      如您要重新安装，请手动删除config/install.lock文件。');
}

switch ($action)
{
	case 'apitest':
	{
		$post=array('t'=>'t');
		echo curl_post($post,15);
		die();
	}
	case 'inspect':
	{
		$mysql_support = (function_exists( 'mysql_connect')) ? ON : OFF;
		if(function_exists( 'mysql_connect')){
			$mysql_support  = 'ON';
			$mysql_ver_class ='OK';
		}else {
			$mysql_support  = 'OFF';
			$mysql_ver_class ='WARN';
		}
		if(PHP_VERSION<'5.0.0'){
			$ver_class = 'WARN';
			$errormsg['version']='php 版本过低';
		}else {
			$ver_class = 'OK';
			$check=1;
		}
		$function='OK';
		if(!function_exists('file_put_contents')){
			$function='WARN';
			$fstr.="<li class='WARN'>空间不支持file_put_contents函数，系统无法写文件。</li>";
		}
		if(!function_exists('fsockopen')&&!function_exists('pfsockopen')&&!function_exists('stream_socket_client')){
			$function='WARN';
			$fstr.="<li class='WARN'>空间不支持fsockopen，pfsockopen,stream_socket_client函数，系统邮件功能不能使用。请至少开启其中一个。</li>";
		}
		if(!function_exists('copy')){
			$function='WARN';
			$fstr.="<li class='WARN'>空间不支持copy函数，无法上传文件。</li>";
		}
		if(!function_exists('fsockopen')&&!function_exists('pfsockopen')&&(!get_extension_funcs('curl')||!function_exists('curl_init')||!function_exists('curl_setopt')||!function_exists('curl_exec')||!function_exists('curl_close'))){
				$function='WARN';
				$fstr.="<li class='WARN'>空间不支持fsockopen，pfsockopen函数，curl模块(需同时开启curl_init,curl_setopt,curl_exec,curl_close)，系统在线更新，短信发送功能无法使用。请至少开启其中一个。</li>";
		}
		if(!get_extension_funcs('gd')){
			$function='WARN';
			$fstr.="<li class='WARN'>空间不支持gd模块，图片打水印和缩略生成功能无法使用。</li>";
		}
		if(!function_exists('gzinflate')){
			$function='WARN';
			$fstr.="<li class='WARN'>空间不支持gzinflate函数，无法在线解压ZIP文件。（无法通过后台上传模板和数据备份文件）</li>";
		}
		if(!function_exists('ini_set')){
			$function='WARN';
			$fstr.="<li class='WARN'>空间不支持ini_set函数，系统无法正常包含文件，导致后台会出现空白现象。</li>";
		}
		session_start();
		if($_SESSION['install']!='metinfo'){
			$function='WARN';
			$fstr.="<li class='WARN'>空间不支持session，无法登陆后台。</li>";
		}
		$w_check=array(
		'../',
		'../about/',
		'../download/',
		'../product/',
		'../news/',
		'../img/',
		'../job/',
		'../search/',
		'../sitemap/',
		'../link/',
		'../member/',
		'../wap/',
		'../upload/',
		'../config/',
		'../config/config_db.php',
		'../cache/',
		'../upload/file/',
		'../upload/image/',
		'../message/',
		'../feedback/',
		'../admin/databack/',
		'../admin/update/'
		);
		$class_chcek=array();
		$check_msg = array();
		$count=count($w_check);
		for($i=0; $i<$count; $i++){
			if(!file_exists($w_check[$i])){
				$check_msg[$i].= '文件或文件夹不存在请上传';$check=0;
				$class_chcek[$i] = 'WARN';
			} elseif(is_writable_met($w_check[$i])){
				$check_msg[$i].= '通 过';
				$class_chcek[$i] = 'OK';
				$check=1;
			} else{
				$check_msg[$i].='777属性检测不通过'; $check=0;
				$class_chcek[$i] = 'WARN';
			}
			if($check!=1 and $disabled!='disabled'){$disabled = 'disabled';}
		}
		include template('inspect');
		break;
	}
	case 'db_setup':
	{
		if($setup==1){
			$db_prefix      = trim($db_prefix);
			$db_host        = trim($db_host);
			$db_username    = trim($db_username);
			$db_pass        = trim($db_pass);
			$db_name        = trim($db_name);
			$config="<?php
                   /*
                   con_db_host = \"$db_host\"
                   con_db_id   = \"$db_username\"
                   con_db_pass	= \"$db_pass\"
                   con_db_name = \"$db_name\"
                   tablepre    =  \"$db_prefix\"
                   db_charset  =  \"utf8\";
                  */
                  ?>";

			$fp=fopen("../config/config_db.php",'w+');
			fputs($fp,$config);
			fclose($fp);
			$db = mysql_connect($db_host,$db_username,$db_pass) or die('连接数据库失败: ' . mysql_error());
			if(!@mysql_select_db($db_name)){
				mysql_query("CREATE DATABASE $db_name ") or die('创建数据库失败'.mysql_error());
			}
			mysql_select_db($db_name);
			//
			if(mysql_get_server_info()>='4.1'){
				mysql_query("set names utf8"); 
				$content=readover("sql.sql");
				$content=preg_replace("/{#(.+?)}/eis",'$lang[\\1]',$content);	
				$installinfo=creat_table($content);		
			}else {
				echo "<SCRIPT language=JavaScript>alert('您的mysql版本过低，请确保你的数据库编码为utf-8,官方建议您升级到mysql4.1.0以上');</SCRIPT>";
				die();
				$content=readover("sql.sql");
				$content=str_replace('ENGINE=MyISAM DEFAULT CHARSET=utf8','TYPE=MyISAM',$content);
			}
			if($cndata=="yes" or ($cndata<>"yes" and $endata<>"yes")){
				$content=readover("cn_config.sql");
				$content=preg_replace("/{#(.+?)}/eis",'$lang[\\1]',$content);	
				$installinfo.=creat_table($content);	
            }			
		    if($endata=="yes"){
				$content=readover("en_config.sql");
				$content=preg_replace("/{#(.+?)}/eis",'$lang[\\1]',$content);	
				$installinfo.=creat_table($content);	
            }	
			if($showdata=='yes'){
				if($cndata=="yes" or ($cndata<>"yes" and $endata<>"yes")){
					$content=readover("cn.sql");
					$content=preg_replace("/{#(.+?)}/eis",'$lang[\\1]',$content);	
					$installinfo.=creat_table($content);	
				}
				if($endata=="yes"){
					$content=readover("en.sql"); 
					$content=preg_replace("/{#(.+?)}/eis",'$lang[\\1]',$content);	
					$installinfo.=creat_table($content);	
				}
			}
			$content=readover("lang.sql"); 
			$content=preg_replace("/{#(.+?)}/eis",'$lang[\\1]',$content);	
			$installinfo.=creat_table($content);
			header("location:index.php?action=adminsetup&cndata={$cndata}&endata={$endata}");exit;
		}else {
			include template('databasesetup');
		}
		break;
	}
	case 'adminsetup':
	{
		if($setup==1){
			if($regname=='' || $regpwd=='' || $email==''){
				echo("<script type='text/javascript'> alert('请填写管理员信息！'); history.go(-1); </script>");
			}
			$regname = trim($regname);
			$regpwd  = md5(trim($regpwd));
			$email   = trim($email);
		    $m_now_time = time();
			$config = parse_ini_file('../config/config_db.php','ture');
			@extract($config);
			$link = mysql_connect($con_db_host,$con_db_id,$con_db_pass) or die('连接数据库失败: ' . mysql_error());
			mysql_select_db($con_db_name);
			if(mysql_get_server_info()>4.1){
			 mysql_query("set names utf8"); 
			}
			if(mysql_get_server_info()>'5.0.1'){
			 mysql_query("SET sql_mode=''",$link);
			}
			$met_admin_table = "{$tablepre}admin_table";
			$met_config      = "{$tablepre}config";
			$met_column      = "{$tablepre}column";
			 $query = " INSERT INTO $met_admin_table set
                      admin_id           = '$regname',
                      admin_pass         = '$regpwd',
					  admin_introduction = '创始人',
					  admin_group        = '10000',
				      admin_type         = 'metinfo',
					  admin_email        = '$email',
					  admin_mobile       = '$tel',
					  admin_register_date= '$m_now_date',
					  usertype        	 = '3',
					  admin_ok           = '1'";
			mysql_query($query) or die('写入数据库失败: ' . mysql_error());
			$query = " UPDATE $met_config set value='$webname_cn' where name='met_webname' and lang='cn'";
			mysql_query($query) or die('写入数据库失败: ' . mysql_error());
			$query = " UPDATE $met_config set value='$webkeywords_cn' where name='met_keywords' and lang='cn'";
			mysql_query($query) or die('写入数据库失败: ' . mysql_error());
			$query = " UPDATE $met_config set value='$webname_en' where name='met_webname' and lang='en'";
			mysql_query($query) or die('写入数据库失败: ' . mysql_error());
			$query = " UPDATE $met_config set value='$webkeywords_en' where name='met_keywords' and lang='en'";
			mysql_query($query) or die('写入数据库失败: ' . mysql_error());
			$force =randStr(7);
			$query = " UPDATE $met_config set value='$force' where name='met_member_force'";
			mysql_query($query) or die('写入数据库失败: ' . mysql_error());
			$install_url=str_replace("install/index.php","",$install_url);
			$query = " UPDATE $met_config set value='$install_url' where name='met_weburl'";
			mysql_query($query) or die('写入数据库失败: ' . mysql_error());
			$adminurl=$install_url.'admin/';
			$query = " UPDATE $met_column set out_url='$adminurl' where module='0'";
			mysql_query($query) or die('写入数据库失败: ' . mysql_error());
			if($cndata=="yes"&&$endata=="yes"){
				$query = "UPDATE $met_config set value='$lang_index_type' where name='met_index_type' and lang='metinfo'";
			}
			else{
				if($cndata=="yes" or ($cndata<>"yes" and $endata<>"yes")){
					$query = "UPDATE $met_config set value='cn' where name='met_index_type' and lang='metinfo'";
				}
				else{
					$query = "UPDATE $met_config set value='en' where name='met_index_type' and lang='metinfo'";
				}
			}
			mysql_query($query) or die('写入数据库失败: ' . mysql_error());
			@chmod('../config/config_db.php',0554);
			require_once '../include/mysql_class.php';
			$db = new dbmysql();
			$db->dbconn($con_db_host,$con_db_id,$con_db_pass,$con_db_name);
			$conlist = $db->get_one("SELECT * FROM $met_config WHERE name='met_weburl'");
			$met_weburl=$conlist[value];
			$indexcont = $db->get_one("SELECT * FROM $met_config WHERE name='met_index_content' and lang='cn'");
			if($indexcont){
				$index_content=str_replace("#metinfo#",$met_weburl,$indexcont[value]);
				$query = "update $met_config SET value = '$index_content' where name='met_index_content' and lang='cn'";
				$db->query($query);
			}
			$showlist = $db->get_all("SELECT * FROM $met_column WHERE module='1'");
			if($showlist){
				foreach($showlist as $key=>$val){
					$contentx=str_replace("#metinfo#",$met_weburl,$val[content]);
					$query = "update $met_column SET content = '$contentx' where id='$val[id]'";
					$db->query($query);
				}
			}
			$agents='';
			if(file_exists('./agents.php')){
				include './agents.php';
				unlink('./agents.php');
			}
			$webname=$webname_cn?$webname_cn:($webname_en?$webname_en:'');
			$webkeywords=$webkeywords_cn?$webkeywords_cn:($webkeywords_en?$webkeywords_en:'');
			$spt = '<script type="text/javascript" src="http://api.metinfo.cn/record_install.php?';
			$spt .= "url=" .$install_url;
			$spt .= "&email=".$email."&installtime=".$m_now_date."&softtype=1";
			$spt .= "&webname=".$webname."&webkeywords=".$webkeywords."&tel=".$tel;
			$spt .= "&version=".VERSION."&php_ver=" .PHP_VERSION. "&mysql_ver=" .mysql_get_server_info()."&browser=".$_SERVER['HTTP_USER_AGENT'].'|'.$se360;
			$spt .= "&agents=".$agents;
			$spt .= '"></script>';
			echo $spt;
			$fp  = @fopen('../config/install.lock', 'w');
			@fwrite($fp," ");
			@fclose($fp);
			$metHOST=$_SERVER['HTTP_HOST'];
			$m_now_year=date('Y');
			$metcms_v=VERSION;
$met404="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>Page Not Found!</title>
<meta http-equiv=\"refresh\" content=\"3; url='{$met_weburl}' \"> 
<style type=\"text/css\">
<!--
body, td, th {  font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; margin: 0; padding: 0;}
a:link,
a:visited {color: #0240a3;}
.top {height: 50px;	background-image:  url({$met_weburl}upload/image/top.gif); background-position: top right; background-repeat: no-repeat;	margin-bottom:40px;	padding-top: 5px;padding-left: 10px; color:#FFFFFF;}
.top a{color:#FFFFFF; text-decoration:none;}
.logo{ float:left; width:auto; height:auto; margin:5px 0px 0px 5px; overflow:hidden;}
.copyright{ float:right; width:auto; margin:5px 5px 0px 0px; text-align:right;}
.content {width: 652px;	margin: auto;	border: 1px solid #D1CBD0;	background: #F9F9F9 url({$met_weburl}upload/image/top1.gif) no-repeat right top;}
.content_TOP {width: 600px; margin: auto;}
.message {width: 98%; margin: 15px auto; padding-top:10px;}
.banner {height:100px; text-align:center; background: #F9F9F9 url({$met_weburl}upload/image/foot.gif) no-repeat center; overflow:auto;}
.bannertext{ width:95%; height:20px; margin-top:70px; line-height:20px; color:#FFFFFF; text-align:right;}
.bannertext a{ color:#FFFFFF; text-decoration:none;}
-->
</style>
</head>
<body>
<div class=\"top\">
<div class=\"logo\"></div>
<div class=\"copyright\">&copy;&nbsp;2008-{$m_now_year} {$webname}<br /> <a href=\"{$met_weburl}\" >{$metHOST}</a></div>
</div>

<div class=\"content_TOP\"></div>
<div class=\"content\">
  <div class=\"message\">
  <table width=\"586\" height=\"220\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
    <tr>
      <td width=\"134\" height=\"116\" valign=\"middle\"><img src=\"{$met_weburl}upload/image/notice.gif\" /></td>
      <td width=\"452\" valign=\"middle\" >
	  <br /><br />
<p><big><b>Page Not Found!</b></big></p>
<p>The requested URL was not found, please contact with your administrator. </p>
<p><big><b>3 seconds, automatically jump to the home page.</b></big></p>
<p>&raquo;&nbsp;<a href=\"{$met_weburl}\">Goto Home</a>
</td>
    </tr>
  </table>
<div class=\"banner\">
<div class=\"bannertext\">
<p style=\"font-family:arial;\">Powered by&nbsp;<a href=\"http://www.MetInfo.cn\" target=\"_blank\" ><b>MetInfo</b></a> {$metcms_v} &copy;&nbsp;2008-$m_now_year <a href=\"http://www.MetInfo.cn\" target=\"_blank\">www.MetInfo.cn</a></p></div></div>
  </div>
  
</div>
</body>
</html>

";


			$fp = @fopen("../404.html",w);
			@fputs($fp, $met404);
			@fclose($fp);
			@chmod('../config/install.lock',0554);				
			include template('finished');
		}else {
			$langnum=($cndata=="yes"&&$endata=="yes")?2:1;
			$lang=$langnum==2?'中文':($endata=="yes"&&$cndata<>"yes"?'英文':'中文');
			include template('adminsetup');
		}
		break;
	}
	case 'license':
		include template('license');
	break;
	default:
	{	
		session_start();
		$_SESSION['install']='metinfo';
		include template('index');
	}
}

function creat_table($content) {
	global $installinfo,$db_prefix,$db_setup;
	$sql=explode("\n",$content);
	$query='';
	$j=0;
	foreach($sql as $key => $value){
		$value=trim($value);
		if(!$value || $value[0]=='#') continue;
		if(eregi("\;$",$value)){
			$query.=$value;
			if(eregi("^CREATE",$query)){
				$name=substr($query,13,strpos($query,'(')-13);
				$c_name=str_replace('met_',$db_prefix,$name);
				$i++;
			}
			$query = str_replace('met_',$db_prefix,$query);
			$query = str_replace('metconfig_','met_',$query);
			if(!mysql_query($query)){
				$db_setup=0;
				if($j!='0'){
				echo '<li class="WARN">出错：'.mysql_error().'<br/>sql:'.$query.'</li>';
				}
			}else {
			     
				if(eregi("^CREATE",$query)){
					$installinfo=$installinfo.'<li class="OK"><font color="#0000EE">建立数据表'.$i.'</font>'.$c_name.' ... <font color="#0000EE">完成</font></li>';
				}
				$db_setup=1;
			}
			$query='';
		} else{
			$query.=$value;
		}
		$j++;
	}
	return $installinfo;
}
function readover($filename,$method="rb"){
	if($handle=@fopen($filename,$method)){
		flock($handle,LOCK_SH);
		$filedata=@fread($handle,filesize($filename));
		fclose($handle);
	}
	return $filedata;
}
function daddslashes($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}
function template($template,$EXT="htm"){
	global $met_skin_user,$skin;
	unset($GLOBALS[con_db_id],$GLOBALS[con_db_pass],$GLOBALS[con_db_name]);
	$path = "templates/$template.$EXT";
	return  $path;
}
function is_writable_met($dir){
	$str='';
	$is_dir=0;
	if(is_dir($dir)){
		$dir=$dir.'metinfo.txt';
		$is_dir=1;
		$info='metinfo';
	}
	else{
		$fp = @fopen($dir,'r+');
		$i=0;
		while($i<10){
			$info.=@fgets($fp);
			$i++;
		}
		@fclose($fp);
		if($info=='')return false;
	}
	$fp = @fopen($dir,'w+');
	@fputs($fp, $info);
	@fclose($fp);
	if(!file_exists($dir))return false;
	$fp = @fopen($dir,'r+');
	$i=0;
	while($i<10){
		$str.=@fgets($fp);
		$i++;
	}
	@fclose($fp);
	if($str!=$info)return false;
	if($is_dir==1){
		@unlink($dir);
	}
	return true;
}
function curl_post($post,$timeout){
global $met_weburl,$met_host,$met_file;
	$host='api.metinfo.cn';
	$file='/test/apilinktest.php';
	if(get_extension_funcs('curl')&&function_exists('curl_init')&&function_exists('curl_setopt')&&function_exists('curl_exec')&&function_exists('curl_close')){
		$curlHandle=curl_init(); 
		curl_setopt($curlHandle,CURLOPT_URL,'http://'.$host.$file); 
		curl_setopt($curlHandle,CURLOPT_REFERER,$met_weburl);
		curl_setopt($curlHandle,CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($curlHandle,CURLOPT_CONNECTTIMEOUT,$timeout);
		curl_setopt($curlHandle,CURLOPT_TIMEOUT,$timeout);
		curl_setopt($curlHandle,CURLOPT_POST, 1);	
		curl_setopt($curlHandle,CURLOPT_POSTFIELDS, $post);
		$result=curl_exec($curlHandle); 
		curl_close($curlHandle); 
	}
	else{
		if(function_exists('fsockopen')||function_exists('pfsockopen')){
			$post_data=$post;
			$post='';
			@ini_set("default_socket_timeout",$timeout);
			while (list($k,$v) = each($post_data)) {
				$post .= rawurlencode($k)."=".rawurlencode($v)."&";
			}
			$post = substr( $post , 0 , -1 );
			$len = strlen($post);
			if(function_exists(fsockopen)){
				$fp = @fsockopen($host,80,$errno,$errstr,$timeout);
			}
			else{
				$fp = @pfsockopen($host,80,$errno,$errstr,$timeout);
			}
			if (!$fp) {
				$result='';
			}
			else {
				$result = '';
				$out = "POST $file HTTP/1.0\r\n";
				$out .= "Host: $host\r\n";
				$out .= "Referer: $met_weburl\r\n";
				$out .= "Content-type: application/x-www-form-urlencoded\r\n";
				$out .= "Connection: Close\r\n";
				$out .= "Content-Length: $len\r\n";
				$out .="\r\n";
				$out .= $post."\r\n";
				fwrite($fp, $out);
				$inheader = 1; 	
				while(!feof($fp)){
					$line = fgets($fp,1024); 
						if ($inheader == 0) {    
							$result.=$line;
						}  
						if ($inheader && ($line == "\n" || $line == "\r\n")) {  
							$inheader = 0;  
					}    

				}
			
				while(!feof($fp)){
					$result.=fgets($fp,1024);
				}
				fclose($fp);
				str_replace($out,'',$result);
			}
		}
		else{
			$result='';
		}
	}
	$result=trim($result);
	if(substr($result,0,7)=='metinfo'){
		return substr($result,7);
	}
	else{
		return 'nohost';
	}
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>