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

        'people/area' => is_mobile() ? 'mob/people/index' : 'people/index/area',
        'people$' => is_mobile() ? 'mob/people/index' : 'people/index/index',
    ),
    'router' => array(
        /*会员*/
        'people/index/index' => 'people',
        'people/index/area' => 'people/area_',//后面的下划线什么鬼嘛——路飞
    )
);