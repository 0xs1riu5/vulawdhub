<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-8-27
 * Time: 下午4:54
 * @author 嘉兴想天信息科技有限公司-郑钟良<zzl@ourstu.com>
 *
 *******************************************************************************
 * 钟良的时代过去了让我来。。。駿濤
 * 16-10-24
 * *****************************************************************************
 */

$type = 'router';
return array(
    /**
     * 路由的key必须写全称,且必须全小写. 比如: 使用'wap/index/index', 而非'wap'.
     */
    'router' => array_merge(array(), merge_route_rule('Weibo',$type)
        , merge_route_rule('Home',$type)
        , merge_route_rule('People',$type)
        , merge_route_rule('Mob',$type)
        , merge_route_rule('Event',$type)
        , merge_route_rule('Group',$type)
        , merge_route_rule('Issue',$type)
        , merge_route_rule('Forum',$type)
        , merge_route_rule('Ucenter',$type)
    )
);

