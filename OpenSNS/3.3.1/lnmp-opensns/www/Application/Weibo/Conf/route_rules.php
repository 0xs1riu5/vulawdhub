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
        'weibo/detail/[:id\d]' => is_mobile() ? 'mob/weibo/weibodetail' : 'weibo/index/weibodetail',
        'weibo/search/[:page\d]' => is_mobile() ? 'mob/weibo/index' : 'weibo/index/search',
        'weibo/all/[:page\d]' => is_mobile() ? 'mob/weibo/index' : 'weibo/index/index?type=all',
        'weibo/hot/[:page\d]' => is_mobile() ? 'mob/weibo/hotweibo' : 'weibo/index/index?type=hot',
        'weibo/concerned/[:page\d]' => is_mobile() ? 'mob/weibo/myfocus' : 'weibo/index/index?type=concerned',
        'weibo/myweibo/[:page\d]' => is_mobile() ? 'mob/weibo/myweibo' : 'weibo/index/index?type=all',
        'weibo/fav/[:page\d]' => is_mobile() ? 'mob/weibo/index' : 'weibo/index/index?type=fav',
        'weibo/add' => is_mobile() ? 'mob/weibo/addweibo' : 'weibo/index/index',

        'weibo/[:page\d]' => is_mobile() ? 'mob/weibo/index' : 'weibo/index/index',
        'topic/hot/[:type\d]' => is_mobile() ? 'mob/weibo/index' : 'weibo/topic/topic',
        'topic/[:topk\d]' => is_mobile() ? 'mob/weibo/index' : 'weibo/topic/index',
    ),
    'router' => array(
        /*动弹*/
        'weibo/index/index' => 'weibo/[type]/[page]/[uid]',
        'weibo/index/weibodetail' => 'weibo/detail/[id]',
        'weibo/index/search' => 'weibo/search',
        'weibo/topic/topic' => 'topic/hot/[type]',
        'weibo/topic/index' => 'topic/[topk]',
    )

);