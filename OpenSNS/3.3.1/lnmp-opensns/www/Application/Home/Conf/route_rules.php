<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/26
 * Time: 14:40
 * @author :  xjw129xjt（駿濤） xjt@ourstu.com
 */

return array(
    'route_rules' => array(
        'home/search' => is_mobile() ? 'mob/weibo/index' : 'home/index/search',
        'home/addons' => 'home/addons/execute',
        'home$' => is_mobile() ? 'mob/weibo/index' : 'home/index/index',
    ),
    'router' => array(
        'home/addons/execute' => 'home/addons_',  // 在最后加了个_  我也不知道为什么  U函数里面这么写的。 --駿濤
        'home/index/index' => 'home',
        'home/index/search' => 'home/search',
    )
);