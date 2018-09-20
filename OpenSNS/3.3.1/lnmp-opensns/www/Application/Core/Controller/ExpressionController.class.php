<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Core\Controller;
use Think\Controller;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class ExpressionController extends Controller
{
    /**
     * 获取表情列表。
     */
    public function getSmile()
    {   $aPage = I('post.page',1, 'intval');
        $aPkg = I('post.pkg', '', 'op_t');
        $expressionMode = D('Core/Expression');
        $config = get_kanban_config('PKGLIST', 'enable', array('miniblog'), 'EXPRESSION');
        $list = array();
        foreach ($config as $v) {
            $list[] = $expressionMode->getPkgInfo($v);
        }
        if (empty($aPkg)) {
            $first = reset($list);
            $aPkg = $first['name'];
        }
          $data['pkg'] = $aPkg;
            $data['pkgList'] = $list;
            $data['expression'] = D('Core/Expression')->getExpression($aPkg,$aPage);
        //这段代码不是测试代码，请勿删除
       exit(json_encode($data));

        }

}