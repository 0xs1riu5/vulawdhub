<?php
/*
 * Created on 2009-8-29
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 if(!defined('IN_BLUE'))
 {
 	die('Access Denied!');
 }
 //error_reporting(E_ERROR);
 define('BLUE_ROOT',str_replace("\\","/",substr(dirname(__FILE__),0,-13)));
 define('UPLOAD',"upload/");
 define('DATA', "data/");

 require_once(BLUE_ROOT."data/config.php");
 require_once(BLUE_ROOT."include/cache.fun.php");
 require_once(BLUE_ROOT."include/common.fun.php");
 require_once(BLUE_ROOT."include/cat.fun.php");
 require_once(BLUE_ROOT."include/user.fun.php");
 require_once(BLUE_ROOT."include/page.class.php");
 require_once(dirname(__FILE__)."/common.fun.php");

 if(!get_magic_quotes_gpc())
 {
 	$_POST = deep_addslashes($_POST);
 	$_GET = deep_addslashes($_GET);
 	$_COOKIES = deep_addslashes($_COOKIES);
 	$_REQUEST = deep_addslashes($_REQUEST);
 }

 $php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];

 $timezone = "PRC";
 if(PHP_VERSION > '5.1')
 {
 	date_default_timezone_set($timezone);
 }

 if (strpos(PHP_SELF, '.php/') !== false){
    header("Location:" . substr($php_self, 0, strpos($php_self, '.php/') + 4) . "\n");
    exit();
 }

 $timestamp=time();


 ini_set("session.gc_divisor", 1);
 ini_set("session.gc_divisor", 1);

 ini_set('session.gc_maxlifetime', 1800);
 session_start();

 header("Content-Type:text/html;charset=".BLUE_CHARSET);
 header('Cache-Control: no-cache, must-revalidate');
 header('Pragma: no-cache');

 require_once(BLUE_ROOT.'include/mysql.class.php');

 $db = new mysql($dbhost,$dbuser,$dbpass,$dbname);

 unset($db_host,$db_user,$db_pass,$db_name);

 require(BLUE_ROOT.'include/smarty/Smarty.class.php');

 $smarty = new Smarty();

 $smarty->caching = false;

 $smarty->template_dir = BLUE_ROOT.'admin/templates/default/';

 $smarty->compile_dir = BLUE_ROOT.'data/compile/admin/';

 $smarty->cache_dir = BLUE_ROOT.'data/cache/temp_cache/';

 $smarty->left_delimiter = "{#";

 $smarty->right_delimiter = "#}";
 $_CFG = get_config();

 if(empty($_SESSION['admin_id']) && $_REQUEST['act'] != 'login' && $_REQUEST['act'] != 'do_login' && $_REQUEST['act'] != 'logout'){
 	if($_COOKIE['Blue']['admin_id'] && $_COOKIE['Blue']['admin_name'] && $_COOKIE['Blue']['admin_pwd']){
 		if(check_cookie($_COOKIE['Blue']['admin_name'], $_COOKIE['Blue']['admin_pwd'])){
 			update_admin_info($_COOKIE['Blue']['admin_name']);
 		}
 	}else{
 		setcookie("Blue[admin_id]", '', 1, $cookiepath, $cookiedomain);
 		setcookie("Blue[admin_name]", '', 1, $cookiepath, $cookiedomain);
 		setcookie("Blue[admin_pwd]", '', 1, $cookiepath, $cookiedomain);
 		echo '<script type="text/javascript">top.location="login.php?act=login";</script>';
 		exit();
 	}
 }elseif($_SESSION['admin_id']){
	 update_admin_info($_SESSION['admin_name']);
 }

?>