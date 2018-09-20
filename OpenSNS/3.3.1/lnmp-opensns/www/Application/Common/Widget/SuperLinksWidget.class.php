<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/23
 * Time: 15:26
 */

namespace Common\Widget;

use Think\Controller;

class SuperLinksWidget extends Controller
{
    public function superLinks($param)
    {
        $list = D('Common/SuperLinks')->linkList();
        $this->assign('list', $list);
        $this->assign('link', $param);
        $this->display(T('Application://Common@Widget/superlinks'));

    }
}