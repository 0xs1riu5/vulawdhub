<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace Admin\Model;

use Think\Model;

/**
 * 插件模型
 * @author yangweijie <yangweijiester@gmail.com>
 */
class AddonsModel extends Model
{

    /**
     * 查找后置操作
     */
    protected function _after_find(&$result, $options)
    {

    }

    protected function _after_select(&$result, $options)
    {

        foreach ($result as &$record) {
            $this->_after_find($record, $options);
        }
    }

    public function install($name)
    {

        $class = get_addon_class($name);
        if (!class_exists($class)) {
            $this->error = L('_PLUGIN_DOES_NOT_EXIST_');
            return false;
        }
        $addons = new $class;
        $info = $addons->info;
        if (!$info || !$addons->checkInfo())//检测信息的正确性
        {
            $this->error = L('_PLUGIN_INFORMATION_MISSING_');
            return false;
        }
        session('addons_install_error', null);
        $install_flag = $addons->install();
        if (!$install_flag) {
            $this->error = L('_PERFORM_A_PLUG__IN__OPERATION_FAILED_') . session('addons_install_error');
            return false;
        }
        $addonsModel = D('Addons');
        $data = $addonsModel->create($info);

        if ((is_array($addons->admin_list) && $addons->admin_list !== array()) || method_exists(A('Addons://Mail/Admin'), 'buildList')) {
            $data['has_adminlist'] = 1;
            S('addons_menu_list',null);
        } else {
            $data['has_adminlist'] = 0;
        }
        if (!$data) {
            $this->error = $addonsModel->getError();
            return false;
        }
        if ($addonsModel->add($data)) {
            $config = array('config' => json_encode($addons->getConfig()));
            $addonsModel->where("name='{$name}'")->save($config);
            $hooks_update = D('Hooks')->updateHooks($name);
            if ($hooks_update) {
                S('hooks', null);
                return true;
            } else {
                $addonsModel->where("name='{$name}'")->delete();
                $this->error = L('_THE_UPDATE_HOOK_IS_FAILED_PLEASE_TRY_TO_REINSTALL_');
                return false;
            }

        } else {
            $this->error = L('_WRITE_PLUGIN_DATA_FAILED_');
            return false;
        }
    }

    /**
     * 文件模型自动完成
     * @var array
     */
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    /**
     * 获取插件列表
     * @param string $addon_dir
     */
    public function getList($addon_dir = '')
    {
        if (!$addon_dir)
            $addon_dir = ONETHINK_ADDON_PATH;
        $dirs = array_map('basename', glob($addon_dir . '*', GLOB_ONLYDIR));
        //TODO 新增模块插件的支持
        /* $modules=D('Module')->getAll();
         foreach($modules as $m){
             if($m['is_setup']){
                 $module_dir=APP_PATH.$m['name'].'/Addons/';
                 if(!file_exists($module_dir)){
                     continue;
                 }
                 $tmp_dirs = array_map('basename',glob($module_dir.'*', GLOB_ONLYDIR));
                 $dirs=array_merge($dirs,$tmp_dirs);
             }
         }*/


        if ($dirs === FALSE || !file_exists($addon_dir)) {
            $this->error = L('_THE_PLUGIN_DIRECTORY_IS_NOT_READABLE_OR_NOT_');
            return FALSE;
        }
        $addons = array();
        $where['name'] = array('in', $dirs);
        $list = $this->where($where)->field(true)->select();
        foreach ($list as $addon) {
            $addon['uninstall'] = 0;
            $addons[$addon['name']] = $addon;
        }
        foreach ($dirs as $value) {

            if (!isset($addons[$value])) {
                $class = get_addon_class($value);
                if (!class_exists($class)) { // 实例化插件失败忽略执行
                    \Think\Log::record(L('_PLUGIN_') . $value . L('_THE_ENTRY_FILE_DOES_NOT_EXIST_WITH_EXCLAMATION_'));
                    continue;
                }
                $obj = new $class;
                $addons[$value] = $obj->info;
                if ($addons[$value]) {
                    $addons[$value]['uninstall'] = 1;
                    unset($addons[$value]['status']);
                }
            }
        }
        //dump($list);exit;
        int_to_string($addons, array('status' => array(-1 => L('_DAMAGE_'), 0 => L('_DISABLE_'), 1 => L('_ENABLE_'), null => L('_NOT_INSTALLED_'))));
        $addons = list_sort_by($addons, 'uninstall', 'desc');
        return $addons;
    }

    /**
     * 获取插件的后台列表
     */
    public function getAdminList()
    {
        $admin=S('addons_menu_list');
        if($admin===false){
            $admin = array();
            $db_addons = $this->where("status=1 AND has_adminlist=1")->field('title,name')->select();
            if ($db_addons) {
                foreach ($db_addons as $value) {
                    $admin[] = array('title' => $value['title'], 'url' => "Addons/adminList?name={$value['name']}");
                }
            }
            S('addons_menu_list',$admin);
        }
        return $admin;
    }
}
