<?php
header('Content-Type: text/html; charset=utf-8');

define('ROOT_PATH',dirname(dirname(__FILE__)).'/');	//网站所在根目录(绝对路径)
					
require_once ROOT_PATH.'include/database.inc.php';		//数据库配置文件
require_once ROOT_PATH.'include/db_mysql.php';		//数据库操作类

/*------------------------------------------------
 * 数据库连接
 *-----------------------------------------------*/
$db = new db_mysql();
$db->connect(DB_HOST,DB_USER,DB_PWD,DB_NAME,DB_CHARSET);

/*防止 PHP 5.1.x 使用时间函数报错*/
if(function_exists('date_default_timezone_set')) date_default_timezone_set('PRC');

?>