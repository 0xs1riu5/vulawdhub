<?php
/**
 * 所属项目 OnePlus.
 * 开发者: 陈一枭
 * 创建日期: 6/20/14
 * 创建时间: 2:57 PM
 * 版权所有 嘉兴想天信息科技有限公司(www.ourstu.com)
 */
//兼容SAE上的数据连接
define('UC_DB_DSN', 'mysql://'.SAE_MYSQL_USER.':'.SAE_MYSQL_PASS.'@'.SAE_MYSQL_HOST_S.':'.SAE_MYSQL_PORT.'/'.SAE_MYSQL_DB); // SAE数据库连接