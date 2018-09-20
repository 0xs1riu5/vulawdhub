<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 1/14/14
 * Time: 8:12 PM
 */


/**
 * 调试开关
 * 项目正式部署后请设置为false
 */
define ( 'APP_DEBUG', true );

//从URL获取SESSION编号
ini_set("session.use_cookies",0);
ini_set("session.use_trans_sid",1);
if($_REQUEST['session_id']) {
    session_id($_REQUEST['session_id']);
    session_start();
}

//调用Application/Api应用
// 绑定访问Admin模块
define('BIND_MODULE','App');
define ( 'APP_PATH', './Application/' );
/**
 *  主题目录 OpenSNS模板地址 （与ThinkPHP中的THEME_PATH不同）
 *  @author 郑钟良<zzl@ourstu.com>
 */
define ('OS_THEME_PATH', './Theme/');

define ( 'RUNTIME_PATH', './Runtime/' );
require './ThinkPHP/ThinkPHP.php';