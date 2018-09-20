<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/10/25
 * Time: 16:12
 * @author:zzl(郑钟良) zzl@ourstu.com
 */
return array(
    'route_rules' => array(
        'u/[:user_short_url]' => is_mobile() ? 'mob/ucenter/index' : 'Ucenter/index/index',
    ),
    'router' => array(

    )

);