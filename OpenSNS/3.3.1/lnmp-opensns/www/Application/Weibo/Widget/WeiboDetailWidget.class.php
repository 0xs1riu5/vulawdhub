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

/**
 * 分类widget
 * 用于动态调用分类信息
 */
class WeiboDetailWidget extends Controller
{

    /* 显示指定分类的同级分类或子分类列表 */
    public function detail($weibo_id, $can_hide = 0)
    {
        $weiboCacheModel = D('Weibo/WeiboCache');
        $html = $weiboCacheModel->getCacheHtml($weibo_id);//获取weibo html缓存
        if ($html === false) {
            $weibo = D('Weibo/Weibo')->getWeiboDetail($weibo_id);
            $this->assign('weibo', $weibo);
            $this->_initAssign($can_hide);
            $this->assign('un_prase_comment', 1);
            //新增点赞者列表获取
            $supportModel=D('Support');
            $supportedUserList=$supportModel->getSupported('Weibo','weibo',$weibo_id,array('uid','space_url'),5);
            $this->assign('supportedUserList',$supportedUserList);
            $support_count=$supportModel->getSupportCount('Weibo','weibo',$weibo_id);
            $this->assign('support_count',$support_count);
            $html = $this->fetch(T('Weibo@Widget/detail'));
            $weiboCacheModel->setCacheHtml($weibo_id,$html);//设置weibo html缓存
        }
        $html = replace_weibo_html($html, $weibo_id);
        if ($can_hide) {
            //替换置顶微博独有按钮start

            //置顶动态隐藏显示
            $hide_ids = cookie('Weibo_index_top_hide_ids');
            $hide_ids = explode(',', $hide_ids);
            $top_hide = in_array($weibo_id, $hide_ids);
            if ($top_hide) {
                $html = str_replace('[top_hide]', 'display:none;', $html);
            }
        }
        $this->show($html);
    }

    public function weibo_html($weibo_id)
    {
        $weibo = D('Weibo/Weibo')->getWeiboDetail($weibo_id);
        $this->assign('weibo', $weibo);
        $this->_initAssign(false);
        $html = $this->fetch(T('Application://Weibo@Widget/detail'));
        $weiboCacheModel = D('Weibo/WeiboCache');
        $weiboCacheModel->setCacheHtml($weibo_id, $html);//设置weibo html缓存
        $html = replace_weibo_html($html, $weibo_id);
        return $html;
    }

    /**
     * 覆盖必要数据，防止出错
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    private function _initAssign($can_hide)
    {
        $this->assign('can_hide', $can_hide);
    }
}
