<?php
/*
 * 游客访问的黑/白名单，不需要开放的，可以注释掉
 */
return array(
    'access' => array(
            'w3g/Index/detail' => true, //详情页面
            'w3g/Index/index' => true,
            'w3g/Index/weibo' => true,
            'w3g/Index/following' => true,
            'w3g/Index/ajax_image_upload' => true,
            'w3g/Index/ajax_iframe' => true,
            'w3g/Index/follower' => true,
            'w3g/Index/all' => true,
            'w3g/Public/home' => true,
            'w3g/Channel/*' => true, //广场页面
            'w3g/People/*' => true, //找伙伴

    ),

);
