<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-14
 * Time: 上午10:11
 * @author 郑钟良<zzl@ourstu.com>
 */
$now_theme = cookie('TO_LOOK_THEME','',array('prefix'=>'OSV2'));
if(!$now_theme){
    $now_theme=modC('NOW_THEME','default','Theme');
}
if($now_theme!='default'){
    return array(
        /* 模板相关配置 */
        'TMPL_PARSE_STRING' => array(
            '__THEME__'=>__ROOT__.'/Theme/'.$now_theme,
            '__THEME_COMMON_STATIC__'=>__ROOT__.'/Theme/'.$now_theme.'/Common/Static',
            '__THEME_STATIC__'=>__ROOT__.'/Theme/'.$now_theme.'/'.MODULE_NAME.'/Static',
            '__THEME_CSS__'=>__ROOT__.'/Theme/'.$now_theme.'/'.MODULE_NAME.'/Static/css',
            '__THEME_JS__'=>__ROOT__.'/Theme/'.$now_theme.'/'.MODULE_NAME.'/Static/js',
            '__THEME_IMG__'=>__ROOT__.'/Theme/'.$now_theme.'/'.MODULE_NAME.'/Static/images',
            '__THEME_VIEW__'=>__ROOT__.'/Theme/'.$now_theme.'/'.MODULE_NAME.'/View',
            '__THEME_VIEW_PUBLIC__'=>__ROOT__.'/Theme/'.$now_theme.'/'.MODULE_NAME.'/View/Public',
            '__THEME_PUBLIC__'=>__ROOT__.'/Theme/'.$now_theme.'/Public',
        ),
    );
}
