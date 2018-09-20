<?php
/**
 * Created by PhpStorm.
 * User: Yixiao Chen
 * Date: 2015/6/29 0029
 * Time: 下午 2:17
 */

namespace Common\Widget;


use Think\Controller;

class UserRankWidget extends Controller{
    public function render($uid){
        $user=query_user(array('rank_link'),$uid);
        $this->assign('rank_link',$user['rank_link']);
        $this->display(T('Application://Common@Widget/userrank'));
    }
}