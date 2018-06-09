<?php


/**
 * 系统配置文件
 */

return array(

	'SYS_LOG'                       => 1, //后台操作日志开关
	'SYS_KEY'                       => '24b16fede9a67c9251d3e7c7161c83ac', //安全密钥
	'SYS_DEBUG'                     => 0, //调试器开关
	'SYS_EMAIL'                     => '', //系统收件邮箱，用于接收系统信息
	'SYS_AUTO_CACHE'                => 1, //自动缓存
	'SITE_ADMIN_CODE'               => 0, //后台登录验证码开关
	'SITE_ADMIN_PAGESIZE'           => 8, //后台数据分页显示数量
	'SYS_CACHE_INDEX'               => 1110, //站点首页静态化
	'SYS_CACHE_MSHOW'               => 1110, //模型内容缓存期
	'SYS_CACHE_MSEARCH'             => 1110, //模型搜索缓存期
	'SYS_CACHE_LIST'                => 1110, //List标签查询缓存
	'SYS_CACHE_MEMBER'              => 1110, //会员信息缓存期
	'SYS_CACHE_ATTACH'              => 1110, //附件信息缓存期
	'SYS_CACHE_FORM'                => 1110, //表单内容缓存期
	'SYS_CACHE_TAG'                 => 1110, //Tag内容缓存期

);