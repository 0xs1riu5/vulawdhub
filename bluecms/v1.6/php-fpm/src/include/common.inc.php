<?php
/**
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

error_reporting(E_ERROR);
define('BLUE_ROOT',str_replace("\\","/",substr(dirname(__FILE__),0,-7)));
define('UPLOAD',"upload/");
define('DATA', "data/");

session_cache_limiter('private, must-revalidate');
session_start();
require_once(BLUE_ROOT.'data/config.php');
define('BLUE_PRE',$pre);

require_once (BLUE_ROOT.'include/common.fun.php');
require_once(BLUE_ROOT.'include/cat.fun.php');
require_once(BLUE_ROOT.'include/cache.fun.php');
require_once(BLUE_ROOT.'include/user.fun.php');
require_once(BLUE_ROOT.'include/index.fun.php');

if(!get_magic_quotes_gpc())
{
	$_POST = deep_addslashes($_POST);
	$_GET = deep_addslashes($_GET);
	$_COOKIES = deep_addslashes($_COOKIES);
	$_REQUEST = deep_addslashes($_REQUEST);
}
 
$timezone = "PRC";
if(PHP_VERSION > '5.1')
{
	date_default_timezone_set($timezone);
}

$timestamp = time();
$online_ip = getip();
header("Content-Type:text/html;charset=".BLUE_CHARSET);

$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
 
if(isset($_SERVER['REQUEST_URI'])) 
{
	$url = $_SERVER['REQUEST_URI'];
} 
else 
{
	$url = $php_self . "?" . $_SERVER['QUERY_STRING'];
}

require_once(BLUE_ROOT.'include/mysql.class.php');

$db = new mysql($dbhost,$dbuser,$dbpass,$dbname);

unset($db_host,$db_user,$db_pass,$db_name);

require(BLUE_ROOT.'include/smarty/Smarty.class.php');

$smarty = new Smarty();

$smarty->caching = false;

$smarty->cache_lifetime = 86400;

$smarty->template_dir = BLUE_ROOT.'templates/default/';

$smarty->compile_dir = BLUE_ROOT.'data/compile/';

$smarty->cache_dir = BLUE_ROOT.'data/cache/temp_cache/';

$smarty->left_delimiter = "{#";

$smarty->right_delimiter = "#}";

$cache_set = read_static_cache('cache_set');

$_CFG = get_config();

if($_CFG['isclose'])
{
	if($_CFG['reason'])
	{
		showmsg($_CFG['reason']);
	}
	else
	{
		showmsg('站点暂时关闭...');
	}
}

$banned_ip = get_bannedip();

if (@in_array($online_ip, $banned_ip))
{
	showmsg('对不起，您的IP已被禁止，有问题请联系管理员!');
}

if(!$_SESSION['user_id'])
{
	if($_COOKIE['BLUE']['user_id'] && $_COOKIE['BLUE']['user_name'] && $_COOKIE['BLUE']['user_pwd'])
	{
 		if(check_cookie($_COOKIE['BLUE']['user_name'], $_COOKIE['BLUE']['user_pwd']))
		{
 			update_user_info($_COOKIE['BLUE']['user_name']);
 		}
 	}
	else if($_COOKIE['BLUE']['user_name'])
	{
		$user_name = $_COOKIE['BLUE']['user_name'];
		$user = $db->query("SELECT COUNT(*) AS num FROM ".table('user')." WHERE user_name='$user_name'");
		if($user['num'] == 1)
		{
			$active = 0;
		}
		else
		{
			$active = 1;
		}
	}
	else
	{
 		setcookie("BLUE[user_id]", '', -86400, $cookiepath, $cookiedomain);
 		setcookie("BLUE[user_name]", '', -86400, $cookiepath, $cookiedomain);
 		setcookie("BLUE[user_pwd]", '', -86400, $cookiepath, $cookiedomain);
 	}
}
$smarty->assign('user_name', $_SESSION['user_name']);

if ($_CFG['gzip'] == 1 && function_exists('ob_gzhandler'))
{
	ob_start('ob_gzhandler');
}
else
{
	ob_start();
}

?>