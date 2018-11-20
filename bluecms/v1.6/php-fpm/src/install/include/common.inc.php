<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：common.inc.php
 * $author：lucks
 */
 if(!defined('IN_BLUE'))
 {
 	die('Access Denied!');
 }
 error_reporting(E_ERROR );
 define('BLUE_ROOT', str_replace('install/include/common.inc.php', '', str_replace('\\', '/', __FILE__)));
 session_cache_limiter('private, must-revalidate');
 session_start();

 define('BLUE_PRE','blue_');
 define('BLUE_CHARSET', 'gb2312');
 define('BLUE_VERSION', 'v1.6');
 define('BLUE_UPDATE_NO', '20100210');

 require_once (BLUE_ROOT.'install/include/common.fun.php');

 if(!get_magic_quotes_gpc())
 {
 	$_POST = install_deep_addslashes($_POST);
 	$_GET = install_deep_addslashes($_GET);
 	$_COOKIES = install_deep_addslashes($_COOKIES);
 	$_REQUEST = install_deep_addslashes($_REQUEST);
 }

 if(PHP_VERSION > '5.1')
 {
 	date_default_timezone_set($timezone);
 }

 $timestamp = time();


 header("Content-Type:text/html;charset=".BLUE_CHARSET);

 $php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
 $url = $php_self."?".$_SERVER['QUERY_STRING'];

 require_once(BLUE_ROOT.'include/mysql.class.php');

 require(BLUE_ROOT.'include/smarty/Smarty.class.php');

 $install_smarty = new Smarty();

 $install_smarty->caching = false;

 $install_smarty->template_dir = BLUE_ROOT.'install/templates/';

 $install_smarty->compile_dir = BLUE_ROOT.'install/compile/';

 $install_smarty->left_delimiter = "{#";

 $install_smarty->right_delimiter = "#}";

 $need_check_dirs = array(
                    'data',
                    'data/cache',
                    'data/upload',
                    'data/compile',
                    'data/backup',
                    'include',
					'install'
                    );

?>
