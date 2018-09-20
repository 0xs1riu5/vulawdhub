<?php
/**
 * 所属项目 OpenSNS开源免费版.
 * 开发者: 陈一枭
 * 创建日期: 2015-03-27
 * 创建时间: 15:48
 * 版权所有 想天软件工作室(www.ourstu.com)
 */
namespace Weibo\Widget;

use Think\Controller;
use Weibo\Model\WeiboModel;

class HomeBlockWidget extends Controller
{
    public function render()
    {
        $this->assignWeibo(1, 'create_time');
        $this->assignWeibo(2, 'comment_count');

        $this->display(T('Application://Weibo@Widget/homeblock'));
    }

    /**
     * @param string $pos
     * @param string $field
     */
    public function assignWeibo($pos = '1', $field = 'create_time')
    {
        $num = modC('WEIBO_SHOW_COUNT' . $pos, 5, 'Weibo');
        $field = modC('WEIBO_SHOW_ORDER_FIELD' . $pos, $field, 'Weibo');
        $order = modC('WEIBO_SHOW_ORDER_TYPE' . $pos, 'desc', 'Weibo');
        $cache = modC('WEIBO_SHOW_CACHE_TIME' . $pos, 600, 'Weibo');
        $data = S('weibo_home_data' . $pos);
        if (empty($data)) {
            $param['limit'] = $num;
            $param['order'] = $field . ' ' . $order;
            $param['where'] = array('status' => 1);
            require_once(APP_PATH . 'Weibo/Common/function.php');
            $weiboModel = D('Weibo/Weibo');
            $data = $weiboModel->getWeiboList($param);
            foreach ($data as $key => $v) {
                $data[$key] = $weiboModel->getWeiboDetail($v);
            }
            S('weibo_home_data' . $pos, $data, $cache);
        }

        unset($v);
        $this->assign('weibo' . $pos, $data);
    }
} 