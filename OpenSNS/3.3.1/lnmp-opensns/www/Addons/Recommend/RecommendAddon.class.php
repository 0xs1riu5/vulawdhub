<?php

namespace Addons\Recommend;

use Common\Controller\Addon;

require_once(ONETHINK_ADDON_PATH . 'Recommend/Common/function.php');

/**
 * 推荐关注插件
 * @author 嘉兴想天信息科技有限公司
 */
class RecommendAddon extends Addon
{
    public $info = array(
        'name' => 'Recommend',
        'title' => '推荐关注',
        'description' => '可选择多种方法推荐用户',
        'status' => 1,
        'author' => '嘉兴想天信息科技有限公司',
        'version' => '0.1'
    );
    public $admin_list = array(
        'model' => 'Example', //要查的表
        'fields' => '*', //要查的字段
        'map' => '', //查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
        'order' => 'id desc', //排序,
        'listKey' => array( //这里定义的是除了id序号外的表格里字段显示的表头名
            '字段名' => '表头显示名'
        ),
    );

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    /**
     * 实现推荐的钩子方法
     * 得到数据在前端显示
     * Date:2015/3/25
     * @author 徐敏威<zzl@ourstu.com>
     */

    //实现的Recommend钩子方法
    public function weiboSide()
    {
        //判断是否登录
        if (is_login()) {
            S('recommend_follow_id_' . is_login(), null);           //加载页面时先清空缓存
            $config = _getAddonsConfig();
            $number = $config['number'];                              //获取配置参数，得到要查找的数量
            $list = _getRecommendList($number);                       //查找
            $this->assign("recommend", $list);                      //显示
        }

        if (empty($list) == 1) {
            echo '';
            return;
        }
        $this->display(T('Addons://Recommend@Recommend/recommend'));
    }

}