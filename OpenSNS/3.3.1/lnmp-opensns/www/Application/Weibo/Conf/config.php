<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.thinkphp.cn>
// +----------------------------------------------------------------------

/**
 * 前台配置文件
 * 所有除开系统级别的前台配置
 */

return array(

    // 预先加载的标签库
    'TAGLIB_PRE_LOAD' => 'OT\\TagLib\\Article,OT\\TagLib\\Think',

    /* 主题设置 */
    'DEFAULT_THEME' => 'default', // 默认模板主题名称

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
        '__IMG__' => __ROOT__ . '/Application/'.MODULE_NAME   . '/Static/images',
        '__CSS__' => __ROOT__ . '/Application/'.MODULE_NAME .'/Static/css',
        '__JS__' => __ROOT__ . '/Application/'.MODULE_NAME. '/Static/js',
        '__ZUI__' => __ROOT__ . '/Public/zui',
        '__CORE_IMAGE__'=>__ROOT__.'/Application/Core/Static/images',
        '__CORE_CSS__'=>__ROOT__.'/Application/Core/Static/css',
        '__CORE_JS__'=>__ROOT__.'/Application/Core/Static/js',
    ),

);

