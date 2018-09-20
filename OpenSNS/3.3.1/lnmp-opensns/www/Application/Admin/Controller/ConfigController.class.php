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

/**
 * 后台配置控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class ConfigController extends AdminController
{

    /**
     * 配置管理
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index()
    {
        /* 查询条件初始化 */
        $map = array();
        $map = array('status' => 1, 'title' => array('neq', ''));
        if (isset($_GET['group'])) {
            $map['group'] = I('group', 0);
        }
        if (isset($_GET['name'])) {
            $map['name'] = array('like', '%' . (string)I('name') . '%');
        }
        //   $map=
        $list = $this->lists('Config', $map, 'sort,id');
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);

        $this->assign('group', C('CONFIG_GROUP_LIST'));
        $this->assign('group_id', I('get.group', 0));
        $this->assign('list', $list);
        $this->meta_title = L('_CONFIG_MANAGER_');
        $this->display();
    }

    /**
     * 新增配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add()
    {
        if (IS_POST) {
            $Config = D('Config');
            $data = $Config->create();
            if ($data) {
                if ($Config->add()) {
                    S('DB_CONFIG_DATA', null);
                    $this->success(L('_SUCCESS_ADD_'), U('index'));
                } else {
                    $this->error(L('_FAIL_ADD_'));
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $this->meta_title = L('_CONFIG_ADD_');
            $this->assign('info', null);
            $this->display('edit');
        }
    }

    /**
     * 编辑配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function edit($id = 0)
    {
        if (IS_POST) {
            $Config = D('Config');
            $data = $Config->create();
            if ($data) {
                if ($Config->save()) {
                    S('DB_CONFIG_DATA', null);
                    //记录行为
                    action_log('update_config', 'config', $data['id'], UID);
                    $this->success(L('_SUCCESS_UPDATE_'), Cookie('__forward__'));
                } else {
                    $this->error(L('_FAIL_UPDATE_'));
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Config')->field(true)->find($id);

            if (false === $info) {
                $this->error(L('_ERROR_CONFIG_INFO_GET_'));
            }
            $this->assign('info', $info);
            $this->meta_title = L('_CONFIG_EDIT_');
            $this->display();
        }
    }

    /**
     * 批量保存配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function save($config)
    {
        if ($config && is_array($config)) {
            $Config = M('Config');
            foreach ($config as $name => $value) {
                $map = array('name' => $name);
                $Config->where($map)->setField('value', $value);
            }
        }
        S('DB_CONFIG_DATA', null);
        $this->success(L('_SUCCESS_SAVE_').L('_EXCLAMATION_'));
    }

    /**
     * 删除配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function del()
    {
        $id = array_unique((array)I('id', 0));

        if (empty($id)) {
            $this->error(L('_DATA_OPERATE_SELECT_'));
        }

        $map = array('id' => array('in', $id));
        if (M('Config')->where($map)->delete()) {
            S('DB_CONFIG_DATA', null);
            //记录行为
            action_log('update_config', 'config', $id, UID);
            $this->success(L('_SUCCESS_DELETE_').L('_EXCLAMATION_'));
        } else {
            $this->error(L('_FAIL_DELETE_').L('_EXCLAMATION_'));
        }
    }

    // 获取某个标签的配置参数
    public function group()
    {
        $id = I('get.id', 1);
        $type = C('CONFIG_GROUP_LIST');
        $list = M("Config")->where(array('status' => 1, 'group' => $id))->field('id,name,title,extra,value,remark,type')->order('sort')->select();

        if ($list) {
            $this->assign('list', $list);
        }
        $this->assign('id', $id);
        $this->meta_title = $type[$id] . L('_SETTINGS_');
        $this->display();
    }

    /**
     * 配置排序
     * @author huajie <banhuajie@163.com>
     */
    public function sort()
    {
        if (IS_GET) {
            $ids = I('get.ids');

            //获取排序的数据
            $map = array('status' => array('gt', -1), 'title' => array('neq', ''));
            if (!empty($ids)) {
                $map['id'] = array('in', $ids);
            } elseif (I('group')) {
                $map['group'] = I('group');
            }
            $list = M('Config')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->meta_title = L('_CONFIG_SORT_');
            $this->display();
        } elseif (IS_POST) {
            $ids = I('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key => $value) {
                $res = M('Config')->where(array('id' => $value))->setField('sort', $key + 1);
            }
            if ($res !== false) {
                $this->success(L('_SUCCESS_SORT_').L('_EXCLAMATION_'), Cookie('__forward__'));
            } else {
                $this->eorror(L('_FAIL_SORT_').L('_EXCLAMATION_'));
            }
        } else {
            $this->error(L('_BAD_REQUEST_').L('_EXCLAMATION_'));
        }
    }

    /**网站信息设置
     * @auth 陈一枭
     */
    public function website()
    {
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();
        $builder->title(L('_SITE_INFO_'))->suggest(L('_SITE_INFO_VICE_'));
        /*        $builder->keySelect('LANG', L('_WEBSITE_LANGUAGE_'), L('_SELECT_THE_DEFAULT_LANGUAGE_'), array('zh-cn' => L('_SIMPLIFIED_CHINESE_'), 'en-us' => L('_ENGLISH_')));*/
        $builder->keyText('WEB_SITE_NAME', L('_SITE_NAME_'), L('_SITE_NAME_VICE_'));
        $builder->keyText('ICP', L('_LICENSE_NO_'), L('_LICENSE_NO_VICE_'));

        $builder->keySingleImage('LOGO', L('_SITE_LOGO_'), L('_SITE_LOGO_VICE_'));
        $builder->keySingleImage('QRCODE', L('_QR_WEIXIN_'), L('_QR_WEIXIN_VICE_'));


        $builder->keySingleImage('JUMP_BACKGROUND', L('_IMG_BG_REDIRECTED_'), L('_IMG_BG_REDIRECTED_'));
        $builder->keyText('SUCCESS_WAIT_TIME', L('_TIME_SUCCESS_WAIT_'), L('_TIME_SUCCESS_WAIT_VICE_'));
        $builder->keyText('ERROR_WAIT_TIME', L('_TIME_FAIL_WAIT_'), L('_TIME_FAIL_WAIT_VICE_'));
        $builder->KeyBool('OPEN_IM','是否开启即时聊天','关闭后将不再显示在顶部导航栏');


        $builder->keyEditor('ABOUT_US', L('_CONTENT_ABOUT_US_'), L('_CONTENT_ABOUT_US_VICE_'));
        $builder->keyEditor('COMPANY', '公司', '页脚公司内容');
        $builder->keyEditor('SUBSCRIB_US', L('_CONTENT_FOLLOW_US_'), L('_CONTENT_FOLLOW_US_VICE_'));
        $builder->keyEditor('COPY_RIGHT', L('_INFO_COPYRIGHT_'), L('_INFO_COPYRIGHT_VICE_'));

        $addons = \Think\Hook::get('uploadDriver');
        $opt = array('local' => L('_LOCAL_'));
        foreach ($addons as $name) {
            if (class_exists($name)) {
                $class = new $name();
                $config = $class->getConfig();
                if ($config['switch']) {
                    $opt[$class->info['name']] = $class->info['title'];
                }

            }
        }

        $builder->keySelect('PICTURE_UPLOAD_DRIVER', L('_PICTURE_UPLOAD_DRIVER_'), L('_PICTURE_UPLOAD_DRIVER_'), $opt);
        $builder->keySelect('DOWNLOAD_UPLOAD_DRIVER', L('_ATTACHMENT_UPLOAD_DRIVER_'), L('_ATTACHMENT_UPLOAD_DRIVER_'), $opt);

        $builder->group(L('_BASIC_INFORMATION_'), array('WEB_SITE_NAME', 'ICP', 'LOGO', 'QRCODE', 'LANG'));

        $builder->group(L('_THE_FOOTER_INFORMATION_'), array('ABOUT_US', 'COMPANY', 'SUBSCRIB_US', 'COPY_RIGHT'));

        $builder->group(L('_JUMP_PAGE_'), array('JUMP_BACKGROUND', 'SUCCESS_WAIT_TIME', 'ERROR_WAIT_TIME'));
        $builder->keyBool('GET_INFORMATION', L('_OPEN_INSTANT_ACCESS_TO_THE_MESSAGE_'),L('_OPEN_INSTANT_ACCESS_TO_THE_MESSAGE_VICE_'));
        $builder->keyText('GET_INFORMATION_INTERNAL', L('_MESSAGE_POLLING_INTERVAL_'), L('_MESSAGE_POLLING_INTERVAL_VICE_'));
        $builder->group(L('_PERFORMANCE_SETTINGS_'), array('OPEN_IM','GET_INFORMATION','GET_INFORMATION_INTERNAL'));
        $builder->group(L('_UPLOAD_CONFIGURATION_'), array('PICTURE_UPLOAD_DRIVER', 'DOWNLOAD_UPLOAD_DRIVER'));








        $builder->keyText('WEBSOCKET_ADDRESS','WebSocket地址', 'IP地址，默认为当前服务器IP');
        $builder->keyText('WEBSOCKET_PORT','WebSocket端口', '默认为8000');
        $builder->group('推送配置', array('WEBSOCKET_ADDRESS', 'WEBSOCKET_PORT'));
        $builder->data($data);
        $builder->keyDefault('WEBSOCKET_PORT', 8000);
        $builder->keyDefault('WEBSOCKET_ADDRESS', gethostbyname($_SERVER['SERVER_NAME']));


        $builder->keyDefault('SUCCESS_WAIT_TIME', 2);
        $builder->keyDefault('ERROR_WAIT_TIME', 5);
        $builder->keyDefault('LANG', 'zh-cn');
        $builder->keyDefault('GET_INFORMATION',1);
        $builder->keyDefault('GET_INFORMATION_INTERNAL',10);
        $builder->keyDefault('OPEN_IM',1);

        $builder->buttonSubmit();
        $builder->display();
    }
}
