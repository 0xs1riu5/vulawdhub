<?php
/**
 * 所属项目 OpenSNS
 * 开发者: 陈一枭
 * 创建日期: 2014-11-18
 * 创建时间: 10:27
 * 版权所有 嘉兴想天信息科技有限公司(www.ourstu.com)
 */
namespace Common\Model;

use Think\Model;

class ModuleModel extends Model
{

    protected $tableName = 'module';
    protected $tokenFile = '/Info/token.ini';
    protected $moduleName = '';

    /**获取全部的模块信息
     * @return array|mixed
     */
    public function getAll($is_installed = '')
    {

        $module = S('module_all'.$is_installed);
        if ($module === false) {
            $dir = $this->getFile(APP_PATH);
            foreach ($dir as $subdir) {
                if (file_exists(APP_PATH . '/' . $subdir . '/Info/info.php') && $subdir != '.' && $subdir != '..') {
                    $info = $this->getModule($subdir);
                    if ($is_installed == 1 && $info['is_setup'] == 0) {
                        continue;
                    }
                    $this->moduleName = $info['name'];
                    //如果token存在的话
                    if (file_exists($this->getRelativePath($this->tokenFile))) {
                        $info['token'] = file_get_contents($this->getRelativePath($this->tokenFile));
                    }
                    $info['auth_role']=explode(',',$info['auth_role']);
                    $module[] = $info;
                }
            }

            S('module_all'.$is_installed, $module);
        }
        return $module;
    }


    /**
     * 重新通过文件来同步模块
     */
    public function reload()
    {
        $modules = $this->select();
        foreach ($modules as $m) {
            if (file_exists(APP_PATH . '/' . $m['name'] . '/Info/info.php')) {
                $info = array_merge($m, $this->getInfo($m['name']));
                $this->save($info);
            }
        }
        $this->cleanModulesCache();
    }

    /**重置单个模块信息
     * @param $name
     */
    public function reloadModule($name)
    {
        $module = $this->where(array('name' => $name))->find();
        if (empty($module)) {
            $this->error = L('_MODULE_INFORMATION_DOES_NOT_EXIST_WITH_PERIOD_');
            return false;
        } else {
            if (file_exists(APP_PATH . '/' . $module['name'] . '/Info/info.php')) {
                $info = array_merge($module, $this->getInfo($module['name']));
                $this->save($info);
                $this->cleanModuleCache($name);
                return true;
            }
        }
    }

    /**检查是否可以访问模块，被用于控制器初始化
     * @param $name
     */
    public function checkCanVisit($name)
    {
        check_login_role_authorized($name,true);
        /*$modules = $this->getAll();

        foreach ($modules as $m) {
            if($m['name'] == ucfirst($name)){
                if (isset($m['is_setup']) && $m['is_setup'] == 0 ) {
                    header("Content-Type: text/html; charset=utf-8");
                    exit('您所访问的模块未安装，禁止访问，请管理员到后台云市场-本地-模块中安装。');
                }
            }
        }*/
    }

    /**
     * 设置禁止访问模块的身份
     * @param $id
     * @param string $auth_role
     * @return bool
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function setModuleRole($id,$auth_role='')
    {
        if(!$id){
            return false;
        }
        $data['id']=$id;
        $data['auth_role']=$auth_role;
        $res=$this->save($data);
        return $res;
    }

    /**检查模块是否已经安装
     * @param $name
     * @return bool
     */
    public function checkInstalled($name)
    {
        $modules = $this->getAll();

        foreach ($modules as $m) {
            if ($m['name'] == $name && $m['is_setup']) {
                return true;
            }
        }
        return false;
    }

    /**
     * 清理全部模块的缓存
     */
    public function  cleanModulesCache()
    {
        $modules = $this->getAll();

        foreach ($modules as $m) {
            $this->cleanModuleCache($m['name']);
        }
        S('module_all', null);
        S('module_all1', null);
        S('admin_modules', null);
        S('ALL_MESSAGE_SESSION',null);
        S('ALL_MESSAGE_TPLS',null);
    }

    /**清理某个模块的缓存
     * @param $name 模块名
     */
    public function cleanModuleCache($name)
    {
        S('common_module_' . strtolower($name), null);

    }

    /**卸载模块
     * @param $id 模块ID
     * @param int $withoutData 0.不清理数据 1.清理数据
     * @return bool
     */
    public function uninstall($id, $withoutData = 1)
    {
        $module = $this->find($id);
        if (!$module || $module['is_setup'] == 0) {
            $this->error = L('_MODULE_DOES_NOT_EXIST_OR_IS_NOT_INSTALLED_WITH_PERIOD_');
            return false;
        }
        $this->cleanMenus($module['name']);
        $this->cleanAuthRules($module['name']);
        $this->cleanAction($module['name']);
        $this->cleanActionLimit($module['name']);
        if ($withoutData == 0) {
            //如果不保留数据
            if (file_exists(APP_PATH . '/' . $module['name'] . '/Info/cleanData.sql')) {
                $uninstallSql = APP_PATH . '/' . $module['name'] . '/Info/cleanData.sql';
                $res = D()->executeSqlFile($uninstallSql);
                if ($res === false) {
                    $this->error = L('_CLEAN_UP_THE_MODULE_DATA_AND_ERROR_MESSAGE_WITH_COLON_') . $res['error_code'];
                    return false;
                }
            }
            //兼容老的卸载方式，执行一边uninstall.sql
            if (file_exists(APP_PATH . '/' . $module['name'] . '/Info/uninstall.sql')) {
                $uninstallSql = APP_PATH . '/' . $module['name'] . '/Info/uninstall.sql';
                $res = D()->executeSqlFile($uninstallSql);
                if ($res === false) {
                    $this->error = L('_CLEAN_UP_THE_MODULE_DATA_AND_ERROR_MESSAGE_WITH_COLON_') . $res['error_code'];
                    return false;
                }
            }
        }
        $module['is_setup'] = 0;
        $this->save($module);

        $this->cleanModulesCache();
        return true;
    }

    /**通过模块名来获取模块信息
     * @param $name 模块名
     * @return array|mixed
     */
    public function getModule($name)
    {
        $module = $this->where(array('name' => $name))->cache('common_module_' . strtolower($name))->find();
        if ($module === false || $module == null) {
            $m = $this->getInfo($name);
            if ($m != array()) {
                if (intval($m['can_uninstall']) == 1) {
                    $m['is_setup'] = 0;//默认设为已安装，防止已安装的模块反复安装。
                } else {
                    $m['is_setup'] = 1;
                }
                $m['id'] = $this->add($m);
                $m['token'] = $this->getToken($m['name']);
                return $m;
            }

        } else {
            $module['token'] = $this->getToken($module['name']);
            return $module;
        }
    }

    /**获取模块的token
     * @param $name 模块名
     * @return string
     */
    public function getToken($name)
    {
        $this->moduleName = $name;
        if (file_exists($this->getRelativePath($this->tokenFile))) {
            $token = file_get_contents($this->getRelativePath($this->tokenFile));
        }
        return $token;
    }

    /**设置模块的token
     * @param $name 模块名
     * @param $token Token
     * @return string
     */
    public function setToken($name, $token)
    {
        $this->moduleName = $name;
        @chmod($this->getRelativePath($this->tokenFile), 0777);
        $result = file_put_contents($this->getRelativePath($this->tokenFile), $token);
        @chmod($this->getRelativePath($this->tokenFile), 0777);
        return $result;
    }

    /**通过ID获取模块信息
     * @param $id
     * @return array|mixed
     */
    public function getModuleById($id)
    {
        $module = $this->where(array('id' => $id))->find();
        if ($module === false || $module == null) {
            $m = $this->getInfo($module['name']);
            if ($m != array()) {
                if ($m['can_uninstall']) {
                    $m['is_setup'] = 0;//默认设为已安装，防止已安装的模块反复安装。
                } else {
                    $m['is_setup'] = 1;
                }
                $m['id'] = $this->add($m);
                $m['token'] = $this->getToken($m['name']);
                return $m;
            }

        } else {
            $module['token'] = $this->getToken($module['name']);
            return $module;
        }
    }


    /**检查某个模块是否已经是安装的状态
     * @param $name
     * @return bool
     */
    public function isInstalled($name)
    {
        $module = $this->getModule($name);
        if ($module['is_setup']) {
            return true;
        } else {
            return false;
        }
    }

    /**安装某个模块
     * @param $id
     * @return bool
     */
    public function install($id)
    {
        $log = '';
        if ($id != 0) {
            $module = $this->find($id);
        } else {
            $aName = I('get.name', '');
            $module = $this->getModule($aName);
        }
        if ($module['is_setup'] == 1) {
            $this->error = L('_MODULE_INSTALLED_WITH_PERIOD_');
            return false;
        }
        if (file_exists(APP_PATH . '/' . $module['name'] . '/Info/guide.json')) {
            //如果存在guide.json
            $guide = file_get_contents(APP_PATH . '/' . $module['name'] . '/Info/guide.json');
            $data = json_decode($guide, true);

            //导入菜单项,menu
            $menu = json_decode($data['menu'], true);
            if (!empty($menu)) {
                $this->cleanMenus($module['name']);
                if ($this->addMenus($menu[0]) == true) {
                    $log .= '&nbsp;&nbsp;>菜单成功安装;<br/>';
                }
            }

            //导入前台权限,auth_rule
            $auth_rule = json_decode($data['auth_rule'], true);
            if (!empty($auth_rule)) {
                $this->cleanAuthRules($module['name']);
                if ($this->addAuthRule($auth_rule)) {
                    $log .= '&nbsp;&nbsp;>权限成功导入。<br/>';
                }
                //设置默认的权限
                $default_rule = json_decode($data['default_rule'], true);
                if ($this->addDefaultRule($default_rule, $module['name'])) {
                    $log .= '&nbsp;&nbsp;>默认权限设置成功。<br/>';
                }
            }

            //导入
            $action = json_decode($data['action'], true);
            if (!empty($action)) {
                $this->cleanAction($module['name']);
                if ($this->addAction($action)) {
                    $log .= '&nbsp;&nbsp;>行为成功导入。<br/>';
                }
            }

            $action_limit = json_decode($data['action_limit'], true);
            if (!empty($action_limit)) {
                $this->cleanActionLimit($module['name']);
                if ($this->addActionLimit($action_limit)) {
                    $log .= '&nbsp;&nbsp;>行为限制成功导入。<br/>';
                }
            }

            if (file_exists(APP_PATH . '/' . $module['name'] . '/Info/install.sql')) {
                $install_sql = APP_PATH . '/' . $module['name'] . '/Info/install.sql';
                if (D()->executeSqlFile($install_sql) === true) {
                    $log .= '&nbsp;&nbsp;>模块数据添加成功。';
                }
            }
        }

        $module['is_setup'] = 1;
        $module['auth_role']=I('post.auth_role','','text');
        $rs = $this->save($module);
        if ($rs === false) {
            $this->error = L('_MODULE_INFORMATION_MODIFICATION_FAILED_WITH_PERIOD_');
            return false;
        }
        $this->cleanModulesCache();//清除全站缓存
        $this->error = $log;
        return true;
    }



    /*——————————————————————————私有域—————————————————————————————*/

    /**获取模块的相对目录
     * @param $file
     * @return string
     */
    private function getRelativePath($file)
    {
        return APP_PATH . $this->moduleName . $file;
    }

    private function addDefaultRule($default_rule, $module_name)
    {
        foreach ($default_rule as $v) {
            $rule = M('AuthRule')->where(array('module' => $module_name, 'name' => $v))->find();
            if ($rule) {
                $default[] = $rule;
            }
        }
        $auth_id = getSubByKey($default, 'id');
        if ($auth_id) {
            $groups = M('AuthGroup')->select();
            foreach ($groups as $g) {
                $old = explode(',', $g['rules']);
                $new = array_merge($old, $auth_id);
                $g['rules'] = implode(',', $new);
                M('AuthGroup')->save($g);
            }
        }
        return true;
    }

    private function addAction($action)
    {
        foreach ($action as $v) {
            unset($v['id']);
            M('Action')->add($v);
        }
        return true;
    }

    private function addActionLimit($action)
    {
        foreach ($action as $v) {
            unset($v['id']);
            M('ActionLimit')->add($v);
        }
        return true;
    }

    private function addAuthRule($auth_rule)
    {
        foreach ($auth_rule as $v) {
            unset($v['id']);
            M('AuthRule')->add($v);
        }
        return true;
    }

    private function cleanActionLimit($module_name)
    {
        $db_prefix = C('DB_PREFIX');
        $sql = "DELETE FROM `{$db_prefix}action_limit` where `module` = '" . $module_name . "'";
        D()->execute($sql);
    }

    private function cleanAction($module_name)
    {
        $db_prefix = C('DB_PREFIX');
        $sql = "DELETE FROM `{$db_prefix}action` where `module` = '" . $module_name . "'";
        D()->execute($sql);
    }

    private function cleanAuthRules($module_name)
    {
        $db_prefix = C('DB_PREFIX');
        $sql = "DELETE FROM `{$db_prefix}auth_rule` where `module` = '" . $module_name . "'";
        D()->execute($sql);
    }

    private function cleanMenus($module_name)
    {
        $db_prefix = C('DB_PREFIX');
        $sql = "DELETE FROM `{$db_prefix}menu` where `url` like '" . $module_name . "/%'";
        D()->execute($sql);
    }

    private function addMenus($menu, $pid = 0)
    {
        $menu['pid'] = $pid;
        unset($menu['id']);
        $id = M('Menu')->add($menu);
        $menu['id'] = $id;
        if (!empty($menu['_']))
            foreach ($menu['_'] as $v) {
                $this->addMenus($v, $id);
            }
        return true;
    }


    private function getInfo($name)
    {
        if (file_exists(APP_PATH . '/' . $name . '/Info/info.php')) {
            $module = require(APP_PATH . '/' . $name . '/Info/info.php');
            return $module;
        } else {
            return array();
        }

    }

    /**
     * 获取文件列表
     */
    private function getFile($folder)
    {
        //打开目录
        $fp = opendir($folder);
        //阅读目录
        while (false != $file = readdir($fp)) {
            //列出所有文件并去掉'.'和'..'
            if ($file != '.' && $file != '..') {
                //$file="$folder/$file";
                $file = "$file";

                //赋值给数组
                $arr_file[] = $file;

            }
        }
        //输出结果
        if (is_array($arr_file)) {
            while (list($key, $value) = each($arr_file)) {
                $files[] = $value;
            }
        }
        //关闭目录
        closedir($fp);
        return $files;
    }


} 