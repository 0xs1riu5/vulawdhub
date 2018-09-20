<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
//<http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
* 系统配文件
* 所有系统级别的配置
*/
return array(
/* 模块相关配置 */
'AUTOLOAD_NAMESPACE' => array('Addons' => ONETHINK_ADDON_PATH), //扩展模块列表
'DEFAULT_MODULE'     => 'Home',
'MODULE_DENY_LIST'   => array('Common', 'User'),
//'MODULE_ALLOW_LIST'  => array('Home','Admin'),

/* 系统数据加密设置 */
'DATA_AUTH_KEY' => '[AUTH_KEY]', //默认数据加密KEY

/* 调试配置 */
'SHOW_PAGE_TRACE' => false,

/* 用户相关设置 */
'USER_MAX_CACHE'     => 1000, //最大缓存用户数
'USER_ADMINISTRATOR' => 1, //管理员用户ID

/* URL配置 */
'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
'URL_MODEL'            => 3, //URL模式  默认关闭伪静态
'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符
'URL_HTML_SUFFIX'       => 'html',  // URL伪静态后缀设置

/* 全局过滤配置 */
'DEFAULT_FILTER' => '', //全局过滤函数

/* 数据库配置 */
'DB_TYPE'   => '[DB_TYPE]', // 数据库类型
'DB_HOST'   => '[DB_HOST]', // 服务器地址
'DB_NAME'   => '[DB_NAME]', // 数据库名
'DB_USER'   => '[DB_USER]', // 用户名
'DB_PWD'    => '[DB_PWD]',  // 密码
'DB_PORT'   => '[DB_PORT]', // 端口
'DB_PREFIX' => '[DB_PREFIX]', // 数据库表前缀

/* 文档模型配置 (文档模型核心配置，请勿更改) */
'DOCUMENT_MODEL_TYPE' => array(2 => '主题', 1 => '目录', 3 => '段落'),
'LOAD_EXT_CONFIG' => 'router',
/* 文件上传相关配置 */
'DOWNLOAD_UPLOAD' => array(
'mimes'    => '', //允许上传的文件MiMe类型
'maxSize'  => 5*1024*1024, //上传的文件大小限制 (0-不做限制)
'exts'     => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml', //允许上传的文件后缀
'autoSub'  => true, //自动子目录保存文件
'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
'rootPath' => './Uploads/Download/', //保存根路径
'savePath' => '', //保存路径
'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
'saveExt'  => '', //文件保存后缀，空则使用原后缀
'replace'  => false, //存在同名是否覆盖
'hash'     => true, //是否生成hash编码
'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
), //下载模型上传配置（文件上传类配置）


/* 图片上传相关配置 */
'PICTURE_UPLOAD' => array(
'mimes'    => '', //允许上传的文件MiMe类型
'maxSize'  => 2*1024*1024, //上传的文件大小限制 (0-不做限制)
'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
'autoSub'  => true, //自动子目录保存文件
'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
'rootPath' => './Uploads/Picture/', //保存根路径
'savePath' => '', //保存路径
'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
'saveExt'  => '', //文件保存后缀，空则使用原后缀
'replace'  => true, //存在同名是否覆盖
'hash'     => true, //是否生成hash编码
'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
), //图片上传相关配置（文件上传类配置）

'PICTURE_UPLOAD_DRIVER'=>'local',
'DOWNLOAD_UPLOAD_DRIVER'=>'local',
//本地上传文件驱动配置
'UPLOAD_LOCAL_CONFIG'=>array(),

/* 编辑器图片上传相关配置 */
'EDITOR_UPLOAD' => array(
'mimes'    => '', //允许上传的文件MiMe类型
'maxSize'  => 2*1024*1024, //上传的文件大小限制 (0-不做限制)
'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
'autoSub'  => true, //自动子目录保存文件
'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
'rootPath' => './Uploads/Editor/', //保存根路径
'savePath' => '', //保存路径
'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
'saveExt'  => '', //文件保存后缀，空则使用原后缀
'replace'  => false, //存在同名是否覆盖
'hash'     => true, //是否生成hash编码
'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
),
'DEFAULT_THEME' => 'default', // 默认模板主题名称
/* 模板相关配置 */
'TMPL_PARSE_STRING' => array(
'__STATIC__' => __ROOT__ . '/Public/static',
'__ZUI__'=>__ROOT__.'/Public/zui'

),
//Session配置——存入数据库（实现用户在线统计）
'SESSION_TYPE'          => 'db',            //数据库存储session
'SESSION_EXPIRE'        => 600,                //session过期时间
//'SESSION_TABLE'         => 'ocenter_session',    //存session的表，系统默认使用DB_PREFIX.'session'。该条应被注释掉
);
