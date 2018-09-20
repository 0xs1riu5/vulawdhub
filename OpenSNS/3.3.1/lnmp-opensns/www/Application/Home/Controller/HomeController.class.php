<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;


class HomeController extends AdminController
{


    public function config()
    {

        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();

        $data['OPEN_LOGIN_PANEL'] = $data['OPEN_LOGIN_PANEL'] ? $data['OPEN_LOGIN_PANEL'] : 1;
        $data['HOME_INDEX_TYPE'] = $data['HOME_INDEX_TYPE'] ? $data['HOME_INDEX_TYPE'] : 'static_home';

        $builder->title(L('_HOME_SETTING_'));
        $builder->keyRadio('HOME_INDEX_TYPE','系统首页类型','',array('static_home'=>'静态首页','index'=>'聚合首页','login'=>'登录页'));
        $modules = D('Common/Module')->getAll();
        foreach ($modules as $m) {
            if ($m['is_setup'] == 1 && $m['entry'] != '') {
                if (file_exists(APP_PATH . $m['name'] . '/Widget/HomeBlockWidget.class.php')) {
                    $module[] = array('data-id' => $m['name'], 'title' => $m['alias']);
                }
            }
        }
        $module[] = array('data-id' => 'slider', 'title' => L('_CAROUSEL_'));

        $default = array(array('data-id' => 'disable', 'title' => L('_DISABLED_'), 'items' => $module), array('data-id' => 'enable', 'title' =>L('_ENABLED_'), 'items' => array()));
        $builder->keyKanban('BLOCK', '展示模块','拖拽到右侧以展示这些模块，新的模块安装后会多出一些可操作的项目');
        $data['BLOCK'] = $builder->parseNestableArray($data['BLOCK'], $module, $default);

        foreach ($modules as $m) {
            if ($m['is_setup'] == 1 && $m['entry'] != '') {
                if (file_exists(APP_PATH . $m['name'] . '/Widget/SearchWidget.class.php')) {
                    $mod[] = array('data-id' => $m['name'], 'title' => $m['alias']);
                }
            }
        }

        $defaultSearch = array(array('data-id' => 'disable', 'title' => L('_DISABLED_'), 'items' => array()), array('data-id' => 'enable', 'title' =>L('_ENABLED_'), 'items' => $mod));
        $builder->keyKanban('SEARCH', '全站搜索模块', '拖拽到右侧以展示这些模块，新的模块安装后会多出一些可操作的项目');
        $data['SEARCH'] = $builder->parseNestableArray($data['SEARCH'], $mod, $defaultSearch);

        $builder->group('首页类型', 'HOME_INDEX_TYPE');
        $builder->group('聚合首页展示模块', 'BLOCK');
        $builder->group('可供全站搜索模块', 'SEARCH');

        $show_blocks = get_kanban_config('BLOCK_SORT', 'enable', array(), 'Home');
        $show_search = get_kanban_config('SEARCH', 'enable', array(), 'Home');


        $builder->buttonSubmit();


        $builder->data($data);


        $builder->display();
    }


}
