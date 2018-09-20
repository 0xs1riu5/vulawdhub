<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/21
 * Time: 11:42
 * @author 路飞<lf@ourstu.com>
 */

namespace Weibo\Widget;

use Think\Controller;

class CommentSupportWidget extends Controller
{
    public function support($param)
    {
        $param['jump']=isset($param['jump'])?$param['jump']:'';
        $this->assign($param);
        $map_support['appname'] = $param['app'];
        $map_support['table'] = $param['table'];
        $map_support['row'] = $param['row'];
//        $count = $this->getSupportCountCache($map_support);
        $count = D('Support')->where($map_support)->count();

        $map_supported = array_merge($map_support, array('uid' => is_login()));
        $supported = D('Support')->where($map_supported)->count();


        $this->assign('count', $count);
        $this->assign('supported', $supported);
        $this->display(T('Weibo@default/Widget/support'));

    }

    private function getSupportCountCache($map_support)
    {
        $cache_key = "comment_support_count_" . implode('_', $map_support);
        $count = S($cache_key);
        if (empty($count)) {
            $count = D('Support')->where($map_support)->count();
            S($cache_key, $count);
            return $count;
        }
        return $count;
    }
}