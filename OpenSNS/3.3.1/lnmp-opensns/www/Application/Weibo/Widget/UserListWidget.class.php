<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Weibo\Widget;

use Think\Action;

/**
 * 分类widget
 * 用于动态调用分类信息
 */
class UserListWidget extends Action
{

    /* 显示指定分类的同级分类或子分类列表 */
    public function lists($map='', $order = 'id desc', $title = '最新加入', $tag = '', $limit = 6)
    {
        $users = S('weibo_latest_user_' . $tag);
        if (empty($users)) {
            $fields = 'id';
            $map['status'] = 1;
            $users = D('ucenter_member')->where($map)->field($fields)->order($order)->limit($limit)->select();
            S('weibo_latest_user_' . $tag, $users, 300);
        }
        foreach ($users as &$v) {
            $v['user'] = query_user(array('avatar64', 'nickname', 'space_url', 'space_link'), $v['id']);
        }
        unset($v);
        $this->assign('user', $users);
        $this->assign('title', $title);
        $this->display('Widget/userList');
    }

}
