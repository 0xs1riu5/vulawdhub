<?php
/**
 * 后台公用配置文件
 * @author root@test.com
 */

//判断登陆状态
if (!isset($_COOKIE['userid'])){
	header("Location: login.php");
}

require_once '../include/config.inc.php';	//系统初始化文件
require_once 'admin.function.php';			//后台公用函数库
?>