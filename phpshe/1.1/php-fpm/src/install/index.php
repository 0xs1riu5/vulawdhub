<?php
/**
 * @copyright     2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate     2012-1111 koyshe <koyshe@gmail.com>
 */
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');
header('Content-Type: text/html; charset=utf-8');

//改写不安全的register_global和防sql注入处理
if (@ini_get('register_globals')) {
	foreach($_REQUEST as $name => $value){unset($$name);}
}

$pe['host_root'] = 'http://'.str_ireplace(rtrim(str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']), '/'), $_SERVER['HTTP_HOST'], str_replace('\\', '/', dirname(__FILE__))).'/../';
$pe['path_root'] = str_replace('\\','/',dirname(__FILE__)).'/../';
include("{$pe['path_root']}/include/class/cache.class.php");
include("{$pe['path_root']}/include/function/global.func.php");

if (get_magic_quotes_gpc()) {
	!empty($_GET) && extract(pe_trim(pe_stripslashes($_GET)), EXTR_PREFIX_ALL, '_g');
	!empty($_POST) && extract(pe_trim(pe_stripslashes($_POST)), EXTR_PREFIX_ALL, '_p');
}
else {
	!empty($_GET) && extract(pe_trim($_GET),EXTR_PREFIX_ALL,'_g');
	!empty($_POST) && extract(pe_trim($_POST),EXTR_PREFIX_ALL,'_p');
}

switch ($_g_step) {
	//#####################@ 配置信息 @#####################//
	case 'setting':
		if (isset($_p_pesubmit)) {
			$dbconn = mysql_connect("{$_p_db_host}:{$_p_db_port}", $_p_db_user, $_p_db_pw);
			if (!$dbconn) pe_error('数据库连接失败...数据库ip，用户名，密码对吗？');
			if (!mysql_select_db($_p_db_name, $dbconn)) {
				mysql_query("CREATE DATABASE `{$_p_db_name}` DEFAULT CHARACTER SET utf8", $dbconn);
				!mysql_select_db($_p_db_name, $dbconn) && pe_error('数据库选择失败...数据库名对吗？');
			}
			mysql_query("SET NAMES utf8", $dbconn);
			mysql_query("SET sql_mode = ''", $dbconn);

			$sql_arr = explode('/*#####################@ pe_cutsql @#####################*/', file_get_contents("{$pe['path_root']}install/phpshe.sql"));
			foreach ($sql_arr as $v) {
				$result = mysql_query(trim(str_ireplace('{dbpre}', $_p_dbpre, $v)));
			}
			if ($result) {
				mysql_query("update `{$_p_dbpre}admin` set `admin_name` = '{$_p_admin_name}', `admin_pw` = '".md5($_p_admin_pw)."' where `admin_id`=1", $dbconn);
				$config = "<?php\n\$pe['db_host'] = '{$_p_db_host}'; //数据库主机地址\n\$pe['db_name'] = '{$_p_db_name}'; //数据库名称\n\$pe['db_user'] = '{$_p_db_user}'; //数据库用户名\n\$pe['db_pw'] = '{$_p_db_pw}'; //数据库密码\n\$pe['db_coding'] = 'utf8';\n\$pe['url_model'] = 'pathinfo'; //url模式,可选项(pathinfo/pathinfo_safe/php)\ndefine('dbpre','{$_p_dbpre}'); //数据库表前缀\n?>";
				file_put_contents("{$pe['path_root']}config.php", $config);
				pe_goto("{$pe['host_root']}install/index.php?step=success");
			}
			else {
				pe_error('数据库安装失败！');
			}
		}
		if (is_writeable("{$pe['path_root']}data/")) {
			$mod_data = '<strong class="cgreen num">Yes</strong>';
			$mod_data_result = true;				
		}
		else {
			$mod_data = '<strong class="cred num">No</strong>';
			$mod_data_result = false;
		}
		if (is_writeable("{$pe['path_root']}config.php")) {
			$mod_config = '<strong class="cgreen num">Yes</strong>';
			$mod_config_result = true;				
		}
		else {
			$mod_config = '<strong class="cred num">No</strong>';
			$mod_config_result = false;
		}
		$menucss_2 = "sel";
		$seo = pe_seo($menutitle='配置信息 -> PHPSHE商城系统安装向导', '', '', 'admin');
	break;
	//#####################@ 安装成功 @#####################//
	case 'success':
		$menucss_3 = "sel";
		$seo = pe_seo($menutitle='安装成功 -> PHPSHE商城系统安装向导');
	break;
	//#####################@ 安装协议 @#####################//
	default :
		$menucss_1 = "sel";
		$seo = pe_seo($menutitle='安装协议 -> PHPSHE商城系统安装向导');
	break;
}
include('install.html');
pe_result();
?>