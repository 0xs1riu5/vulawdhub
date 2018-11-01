<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2011-0501 koyshe <koyshe@gmail.com>
 */
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');
header('Content-Type: text/html; charset=utf-8');

//改写不安全的register_global和防sql注入处理
if (@ini_get('register_globals')) {
	foreach($_REQUEST as $name => $value){unset($$name);}
}

//url路由配置
$module = $mod = $act = 'index';
$mod = $_POST['mod'] ? $_POST['mod'] : ($_GET['mod'] ? $_GET['mod'] : $mod);
$act = $_POST['act'] ? $_POST['act'] : ($_GET['act'] ? $_GET['act'] : $act);
$id = $_POST['id'] ? $_POST['id'] : ($_GET['id'] ? $_GET['id'] : $id);

if ($_SERVER['PATH_INFO']) {
	$module = 'index';
	$_pathinfo = explode('/', str_ireplace('.html', '', trim($_SERVER['PATH_INFO'], '/')));

	$mod = $_pathinfo[0] ? $_pathinfo[0] : $mod;
	$act = $_pathinfo[1] ? $_pathinfo[1] : $act;

	if ($_pathinfo[1]) {
		$querystr = explode('-', $_pathinfo[1]);
		$querystr[0] && $act = $querystr[0];
		$querystr[1] && $id = $querystr[1];
	}
}
else {
	$module = basename($_SERVER['SCRIPT_NAME'], '.php');
}

include(dirname(__FILE__).'/config.php');
//#################=====定义全局路径=====#################//
$pe['host_root'] = 'http://'.str_ireplace(rtrim(str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']), '/'), $_SERVER['HTTP_HOST'], str_replace('\\', '/', dirname(__FILE__))).'/';
$pe['path_root'] = str_replace('\\','/',dirname(__FILE__)).'/';

//#################=====包含常用类-函数文件=====#################//
include($pe['path_root'].'/include/class/db.class.php');
include($pe['path_root'].'/include/class/page.class.php');
include($pe['path_root'].'/include/class/cache.class.php');
include($pe['path_root'].'/include/function/global.func.php');

$cache_setting = cache::get('setting');
if (!is_dir("{$pe['path_root']}template/{$cache_setting['web_tpl']['setting_value']}/{$module}/")) {
	$cache_setting['web_tpl']['setting_value'] = 'default';
}
$pe['host_tpl'] = "{$pe['host_root']}template/{$cache_setting['web_tpl']['setting_value']}/{$module}/";
$pe['path_tpl'] = "{$pe['path_root']}template/{$cache_setting['web_tpl']['setting_value']}/{$module}/";


if (get_magic_quotes_gpc()) {
	!empty($_GET) && extract(pe_trim(pe_stripslashes($_GET)), EXTR_PREFIX_ALL, '_g');
	!empty($_POST) && extract(pe_trim(pe_stripslashes($_POST)), EXTR_PREFIX_ALL, '_p');
}
else {
	!empty($_GET) && extract(pe_trim($_GET),EXTR_PREFIX_ALL,'_g');
	!empty($_POST) && extract(pe_trim($_POST),EXTR_PREFIX_ALL,'_p');
}
session_start();
!empty($_SESSION) && extract(pe_trim($_SESSION),EXTR_PREFIX_ALL,'_s');
!empty($_COOKIE) && extract(pe_trim(pe_stripslashes($_COOKIE)),EXTR_PREFIX_ALL,'_c');

//连接数据库开始吧
$db = new db($pe['db_host'], $pe['db_user'], $pe['db_pw'], $pe['db_name'], $pe['db_coding']);
?>