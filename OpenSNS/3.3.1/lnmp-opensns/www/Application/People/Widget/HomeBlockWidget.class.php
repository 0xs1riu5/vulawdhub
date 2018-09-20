<?php
/**
 * 所属项目 OpenSNS开源免费版.
 * 开发者: 陈一枭
 * 创建日期: 2015-03-27
 * 创建时间: 15:48
 * 版权所有 想天软件工作室(www.ourstu.com)
 */
namespace People\Widget;

use Think\Controller;

class HomeBlockWidget extends Controller
{
    public function render()
    {
        $this->assignUser(1);
        $this->assignUser(2,'reg_time');
        $this->display(T('Application://People@Widget/homeblock'));

    }

    public function assignUser($pos = '1', $field = 'score1')
    {
        $num = modC('USER_SHOW_COUNT' . $pos, 5, 'People');
        $field = modC('USER_SHOW_ORDER_FIELD' . $pos, $field, 'People');
        $order = modC('USER_SHOW_ORDER_TYPE' . $pos, 'desc', 'People');
        $cache = modC('USER_SHOW_CACHE_TIME' . $pos, 600, 'People');
        $data = S('people_home_data'. $pos);
        if (empty($data)) {
            $map = array('status' => 1);
            $content = D('Member')->field('uid')->where($map)->order($field . ' ' . $order)->limit($num)->select();
            foreach ($content as &$v) {
                $v['user'] = query_user(array('uid', 'nickname', 'space_url', 'space_link', 'avatar64', 'rank_html'), $v['uid']);
            }
            $data = $content;
            S('people_home_data' . $pos, $data, $cache);
        }
        unset($v);
        $this->assign('people'.$pos, $data);
    }
} 