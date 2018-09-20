<?php
/**
 * 所属项目 110.
 * 开发者: 陈一枭
 * 创建日期: 2014-11-18
 * 创建时间: 10:09
 * 版权所有 想天软件工作室(www.ourstu.com)
 */

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Common\Model\ModuleModel;
use Think\Controller;

class ModuleController extends AdminController
{
    protected $moduleModel;
    protected $cloudModel;

    function _initialize()
    {
        parent::_initialize();
        $this->moduleModel = D('Module');
        $this->cloudModel = D('Cloud');
    }


    public function lists()
    {

        $this->meta_title = L('_MODULE_MANAGEMENT_');
        $aType = I('type', 'installed', 'text');
        $this->assign('type', $aType);

        $listBuilder = new AdminListBuilder();

        /*刷新模块列表时清空缓存*/
        $aRefresh = I('get.refresh', 0, 'intval');
        if ($aRefresh == 1) {
            S('admin_modules', null);
            D('Module')->reload();
            S('admin_modules', null);
        } else if ($aRefresh == 2) {
            S('admin_modules', null);
            D('Module')->cleanModulesCache();

        }
        /*刷新模块列表时清空缓存 end*/
        $modules = S('admin_modules');
        if ($modules === false) {
            $modules = $this->moduleModel->getAll();
            $modules = $this->cloudModel->getVersionInfoList($modules);
            S('admin_modules', $modules);
        }

        foreach ($modules as $key => $m) {
            switch ($aType) {
                case 'all':
                    break;
                case 'installed':
                    if ($m['can_uninstall'] && $m['is_setup']) {
                    } else unset($modules[$key]);
                    break;
                case 'uninstalled':
                    if ($m['can_uninstall'] && $m['is_setup'] == 0) {
                    } else unset($modules[$key]);
                    break;
                case 'core':
                    if ($m['can_uninstall'] == 0) {
                    } else unset($modules[$key]);
                    break;
            }
        }
        unset($m);
        // dump($modules);exit;
        $this->assign('modules', $modules);
        $this->display();
    }

    /**
     * 编辑模块
     */
    public function edit()
    {
        if (IS_POST) {
            $aName = I('name', '', 'text');
            $module['id'] = I('id', 0, 'intval');
            $module['name'] = empty($aName) ? $this->error(L('_MODULE_NAME_CAN_NOT_BE_EMPTY_')) : $aName;
            $aAlias = I('alias', '', 'text');
            $module['alias'] = empty($aAlias) ? $this->error(L('_MODULE_CHINESE_NAME_CAN_NOT_BE_EMPTY_')) : $aAlias;
            $aIcon = I('icon', '', 'text');
            $module['icon'] = empty($aIcon) ? $this->error(L('_ICONS_CANT_BE_EMPTY_')) : $aIcon;
            $aSummary = I('summary', '', 'text');
            $module['summary'] = empty($aSummary) ? $this->error(L('_THE_INTRODUCTION_CAN_NOT_BE_EMPTY_')) : $aSummary;
            $module['title'] = I('name', '', 'text');
            $module['menu_hide'] = I('menu_hide', 0, 'intval');
            $aToken = I('token', '', 'text');
            $module['auth_role']=I('auth_role','','text');
            $aToken = trim($aToken);
            if ($aToken != '') {
                if (D('Common/Module')->setToken($module['name'], $aToken)) {
                    $tokenStr = L('_TOKEN_WRITE_SUCCESS_');
                } else {
                    $tokenStr = L('_TOKEN_WRITE_FAILURE_');
                }

            }


            if ($this->moduleModel->save($module) === false) {
                $this->error(L('_EDIT_MODULE_FAILED_') . $tokenStr);
            } else {
                $this->moduleModel->cleanModuleCache($aName);
                $this->moduleModel->cleanModulesCache();
                $this->success(L('_EDIT_MODULE_') . $tokenStr);
            }
        } else {
            $aName = I('name', '', 'text');
            $module = $this->moduleModel->getModule($aName);
            $module['token'] = D('Common/Module')->getToken($module['name']);
            !isset($module['menu_hide']) && $module['menu_hide'] = 0;

            $role_list = D("Admin/Role")->selectByMap(array('status' => 1));
            $auth_role_array=array_combine(array_column($role_list,'id'),array_column($role_list,'title'));
            $this->assign('role_list', $role_list);

            $builder = new AdminConfigBuilder();
            $builder->title(L('_MODULE_EDIT_') . $module['alias']);
            $builder->keyId()->keyReadOnly('name', L('_MODULE_NAME_'))->keyText('alias', L('_MODULE_CHINESE_NAME_'))->keyReadOnly('version', L('_VERSION_'))
                ->keyText('icon', L('_ICON_'))
                ->keyTextArea('summary', L('_MODULE_INTRODUCTION_'))
                ->keyReadOnly('developer', L('_DEVELOPER_'))
                ->keyText('entry', L('_FRONT_ENTRANCE_'))
                ->keyText('admin_entry', L('_BACKGROUND_ENTRY_'))
                ->keyRadio('menu_hide', '管理入口是否隐藏', '默认隐藏', array(0 => '不隐藏', 1 => '隐藏'))
                ->keyText('token', L('_MODULE_KEY_TOKEN_'), L('_MODULE_KEY_TOKEN_VICE_'))
                ->keyCheckBox('auth_role', '允许身份前台访问', '都不选表示非登录状态也可访问', $auth_role_array);

            $builder->data($module);
            $builder->buttonSubmit()->buttonBack()->display();
        }

    }

    public function uninstall()
    {
        $aId = I('id', 0, 'intval');
        $aNav = I('remove_nav', 0, 'intval');
        $moduleModel = new ModuleModel();

        $module = $moduleModel->getModuleById($aId);

        if (IS_POST) {
            $aWithoutData = I('withoutData', 1, 'intval');//是否保留数据
            $res = $this->moduleModel->uninstall($aId, $aWithoutData);
            if ($res == true) {
                if (file_exists(APP_PATH . '/' . $module['name'] . '/Info/uninstall.php')) {
                    require_once(APP_PATH . '/' . $module['name'] . '/Info/uninstall.php');
                }
                if ($aNav) {
                    M('Channel')->where(array('url' => $module['entry']))->delete();
                    S('common_nav', null);
                }
                S('admin_modules', null);
                $this->success(L('_THE_SUCCESS_OF_THE_UNLOADING_MODULE_'), U('lists'));
            } else {
                $this->error(L('_FAILURE_OF_THE_UNLOADING_MODULE_') . $this->moduleModel->error);
            }

        }


        $builder = new AdminConfigBuilder();
        $builder->title($module['alias'] . L('_DASH_') . L('_UNLOADING_MODULE_'));
        $module['remove_nav'] = 1;
        $builder->keyReadOnly('id', L('_MODULE_NUMBER_'));
        $builder->suggest('<span class="text-danger">' . L('_OPERATE_CAUTION_') . '</span>');
        $builder->keyReadOnly('alias', L('_UNINSTALL_MODULE_'));
        $builder->keyBool('withoutData', L('_KEEP_DATA_MODULE_') . '?', L('_DEFAULT_RESERVATION_MODULE_DATA_'))->keyBool('remove_nav', L('_REMOVE_NAVIGATION_'), L('_UNINSTALL_AUTO_UNINSTALL_MENU_', array('link' => U('channel/index'))));

        $module['withoutData'] = 1;
        $builder->data($module);
        $builder->buttonSubmit();
        $builder->buttonBack();
        $builder->display();


    }


    public function install()
    {
        $aName = I('get.name', '', 'text');
        $aNav = I('add_nav', 0, 'intval');
        $module = $this->moduleModel->getModule($aName);

        if (IS_POST) {
            //执行guide中的内容
            $res = $this->moduleModel->install($module['id']);

            if ($res === true) {
                if ($aNav) {
                    $channel['title'] = $module['alias'];
                    $channel['url'] = $module['entry'];
                    $channel['sort'] = 100;
                    $channel['status'] = 1;
                    $channel['icon'] = $module['icon'];
                    M('Channel')->add($channel);
                    S('common_nav', null);
                }
                S('ADMIN_MODULES_' . is_login(), null);
                $this->success(L('_INSTALLATION_MODULE_SUCCESS_'), U('lists'));
            } else {
                $this->error(L('_SETUP_MODULE_FAILED_') . $this->moduleModel->getError());
            }


        } else {

            $role_list = D("Admin/Role")->selectByMap(array('status' => 1));
            $auth_role_array=array_combine(array_column($role_list,'id'),array_column($role_list,'title'));
            $this->assign('role_list', $role_list);

            $builder = new AdminConfigBuilder();


            $builder->title($module['alias'] . L('_DASH_') . L('_GUIDE_MODULE_INSTALL_'));

            $builder->keyId()->keyReadOnly('name', L('_MODULE_NAME_'))->keyText('alias', L('_MODULE_CHINESE_NAME_'))->keyReadOnly('version', L('_VERSION_'))
                ->keyText('icon', L('_ICON_'))
                ->keyTextArea('summary', L('_MODULE_INTRODUCTION_'))
                ->keyReadOnly('developer', L('_DEVELOPER_'))
                ->keyText('entry', L('_FRONT_ENTRANCE_'))
                ->keyText('admin_entry', L('_BACKGROUND_ENTRY_'))
                ->keyCheckBox('auth_role', '允许身份前台访问', '都不选表示非登录状态也可访问', $auth_role_array);

//, 'repair' => L('_FIX_MODE_')修复模式不会导入模块专用数据表，只导入菜单、权限、行为、行为限制
            $builder->keyRadio('mode', L('_INSTALLATION_MODE_'), '', array('install' => L('_COVER_INSTALLATION_MODE_')));
            if ($module['entry']) {
                $builder->keyBool('add_nav', L('_ADD_NAVIGATION_'), L('_INSTALL_AUTO_ADD_MENU_', array('link' => U('channel/index'))));
            }

            /*   $builder->keyRadio('add_nav',L('_ADD_NAVIGATION_MENU_'),L('_DEFAULT_WILL_NOT_ADD_NAVIGATION_'),array(1=>L('_DO_NOT_ADD_'),2=>L('_ADD_')));*/
            $builder->group(L('_INSTALL_OPTION_'), 'mode,add_nav,auth_role');
            /* $builder->group(L('_MODULE_INFORMATION_'), 'id,name,alias,version,icon,summary,developer,entry,admin_entry');*/


            $module['mode'] = 'install';
            $module['add_nav'] = '1';
            $builder->data($module);
            $builder->buttonSubmit();
            $builder->buttonBack();
            $builder->display();
        }


        /*  */


    }

} 