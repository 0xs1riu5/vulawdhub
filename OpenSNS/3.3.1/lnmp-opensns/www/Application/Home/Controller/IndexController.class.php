<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use Think\Controller;


/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends Controller
{

    //系统首页
    public function index()
    {
        $indexType=modC('HOME_INDEX_TYPE','static_home','Home');
        if($indexType=='static_home'){
            $this->display('static_home');
            exit;
        }
        if($indexType=='login'){
            if(!is_login()){
                redirect(U('Ucenter/Member/login'));
                exit;
            }
        }
        hook('homeIndex');
        $default_url = C('DEFUALT_HOME_URL');//获得配置，如果为空则显示聚合，否则跳转
        if ($default_url != ''&&strtolower($default_url)!='home/index/index') {
            redirect(get_nav_url($default_url));
            exit;
        }

        $show_blocks = get_kanban_config('BLOCK', 'enable', array(), 'Home');

        $this->assign('showBlocks', $show_blocks);


        $enter = modC('ENTER_URL', '', 'Home');
        $this->assign('enter', get_nav_url($enter));




            $sub_menu['left']= array(array('tab' => 'home', 'title' => L('_SQUARE_'), 'href' =>  U('index'))//,array('tab'=>'rank','title'=>'排行','href'=>U('rank'))

            );


        $this->assign('sub_menu', $sub_menu);
        $this->assign('current', 'home');



        $this->display('index');
    }

    protected function _initialize()
    {

        /*读取站点配置*/
        $config = api('Config/lists');
        C($config); //添加配置

        if (!C('WEB_SITE_CLOSE')) {
            $this->error(L('_ERROR_WEBSITE_CLOSED_'));
        }
    }

    public function search()
    {
        $keywords=I('post.keywords','','text');
        $modules = D('Common/Module')->getAll();
        foreach ($modules as $m) {
            if ($m['is_setup'] == 1 && $m['entry'] != '') {
                if (file_exists(APP_PATH . $m['name'] . '/Widget/SearchWidget.class.php')) {
                    $mod[] = $m['name'];
                }
            }
        }
        $show_search = get_kanban_config('SEARCH', 'enable', $mod, 'Home');

        $this->assign($keywords);
        $this->assign('showBlocks', $show_search);
        $this->display();
    }

}