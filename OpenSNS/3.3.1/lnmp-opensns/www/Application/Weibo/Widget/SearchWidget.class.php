<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/9
 * Time: 11:45
 * @author 路飞<lf@ourstu.com>
 */

namespace Weibo\Widget;

use Think\Controller;
use Weibo\Model\WeiboModel;

class SearchWidget extends Controller
{
    public function render()
    {
        $this->assignWeibo('create_time');
        $this->display(T('Application://Weibo@Widget/search'));
    }

    public function assignWeibo($field = 'create_time')
    {
        $keywords = I('post.keywords','','text');

        if($keywords) {
            $field = modC('WEIBO_SHOW_ORDER_FIELD', $field, 'Weibo');
            $order = modC('WEIBO_SHOW_ORDER_TYPE', 'desc', 'Weibo');

            $param['order'] = $field . ' ' . $order;
            $param['where'] = array('status' => 1, 'content' => array('like', '%' . $keywords . '%'));
            require_once(APP_PATH . 'Weibo/Common/function.php');
            $weiboModel = D('Weibo/Weibo');
            $data = $weiboModel->getWeiboList($param);
            foreach ($data as $key => $v) {
                $data[$key] = $weiboModel->getWeiboDetail($v);
            }

            unset($v);
        }

        $this->assign('keywords', $keywords);
        $this->assign('weibo', $data);
    }
}