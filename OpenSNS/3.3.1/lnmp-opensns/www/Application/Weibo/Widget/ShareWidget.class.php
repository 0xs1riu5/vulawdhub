<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Weibo\Widget;

use Think\Controller;


class ShareWidget extends Controller
{
    public function shareBtn($param, $text = '分享', $css = array())
    {
        if(strpos($param['img'],'nopic.png') !==false){
            unset($param['img']);
        }
        $this->assign('param', $param);
        $this->assign('query', urlencode(http_build_query($param)));
        $this->assign('text', $text);
        $this->assign('css', $css);
        $this->display(T('Weibo@default/Widget/share/sharebtn'));
    }

    public function fetchShare($param, $weibo = null)
    {
        $this->assginFetch($param, $weibo = null);
        $this->display(T('Weibo@default/Widget/share/fetchshare'));
    }

    private function assginFetch($param, $weibo = null)
    {
        if ($weibo) {
            $this->assign('weibo', $weibo);
        }
        $show = D('Weibo/Share')->getInfo($param);
        $show=array_merge($show, $param);
        $this->assign('show', $show);
    }

    public function getFetchHtml($param, $weibo = null)
    {
        $html = '';
        if ((!empty($param['app']) && !empty($param['model']))) {
            if ($class = A($param['app'] . '/Share', 'Widget')) {
                if (method_exists($class, $param['model'])) {
                    $html = R($param['app'] . '/Share/' . $param['model'], array('param' => $param, 'weibo' => $weibo), 'Widget');
                    return $html;
                }
            }
        }
        $this->assginFetch($param, $weibo);
        $html = $this->fetch(T('Weibo@default/Widget/share/fetchshare'));
        return $html;
    }
}
