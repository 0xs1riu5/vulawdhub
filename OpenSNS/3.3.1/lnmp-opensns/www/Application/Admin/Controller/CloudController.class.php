<?php

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;

class CloudController extends AdminController
{

    public function index()
    {
        $this->display();
    }

    /**
     * 获取升级列表
     */
    public function getVersionList()
    {
        $aToken = I('get.token', '', 'text');
        $versionList = D('Admin/Cloud')->getUpdateList($aToken);
        $this->assign('token', $aToken);
        $this->assign('versionList', $versionList);
        $this->display();
    }


    /**
     * 系统自动更新，开始
     */
    public function update()
    {
        $aRefresh=I('get.refresh',0,'intval');
        if($aRefresh){
            S('admin_versions',null);
        }
        $versionModel = D('Version');
        $version=$versionModel->getVersions();
        $currentVersion = $versionModel->getCurrentVersion();

        foreach ($version as $key => $vo) {
            $versionCompare = version_compare($currentVersion['name'], $vo['name']);
            if ($versionCompare > -1) {
                if ($versionCompare == 0) {
                    $version[$key]['class'] = 'active';
                    $version[$key]['word'] = L('_CURRENT_VERSION_');
                } else {
                    $version[$key]['class'] = 'default';
                    $version[$key]['word'] = L('_HISTORY_');
                }
            } else {
                $version[$key]['class'] = 'success';
                $version[$key]['word'] = L('_CAN_BE_UPGRADED_');
            }
        }


        $this->assign('cloud', cloud_url());
        $this->assign('currentVersion', $currentVersion['name']);
        $this->assign('version', $version);
        $this->assign('nextVersion', $versionModel->getNextVersion());
        $this->disableCheckUpdate();
        $this->display();
    }

    private function disableCheckUpdate()
    {
        $this->assign('update', false);
    }

    /**
     * 获取文件列表
     */
    public function getFileList()
    {


        $aVersion = I('get.version', '', 'text');
        if ($aVersion == '') {
            $this->error(L('_UPGRADE_FAILED_PLEASE_CONFIRM_THE_VERSION_'));
        }
        $versionModel = D('Version');
        $nextVersion = $versionModel->getNextVersion();
        if ($aVersion != $nextVersion['name']) {
            $this->error(L('_THIS_VERSION_DOES_NOT_ALLOW_THE_CURRENT_VERSION_TO_UPGRADE_'));
        }
        $this->assign('path', C('UPDATE_PATH') . $nextVersion['name']);
        /*版本正确性检测↑*/
        $currentVersion = $versionModel->getCurrentVersion();
        $this->assign('currentVersion', $currentVersion);
        $this->assign('nextVersion', $nextVersion);
        $this->disableCheckUpdate();
        $this->display();;
        $this->writeMessage(L('_ORIGIN_PACK_DOWNLOAD_START_'));

        set_time_limit(0);

        $old_file_path = C('UPDATE_PATH') . $nextVersion['name'] . '/old';
        $new_file_path = C('UPDATE_PATH') . $nextVersion['name'] . '/new';
        if (!$this->createFolder($old_file_path)) {
            $this->write(L('_CREATE_DIRECTORY_FAILED_') . $old_file_path . L('_PLEASE_CHECK_THE_PERMISSIONS_'), 'danger');
            return;
        }
        if (!$this->createFolder($new_file_path)) {
            $this->write(L('_CREATE_DIRECTORY_FAILED_') . $new_file_path . L('_PLEASE_CHECK_THE_PERMISSIONS_'), 'danger');
            return;
        }


        $this->downloadFile(cloud_url() . cloudU('Appstore/Update/download', array('number' => $nextVersion['number'], 'type' => 'old')), C('UPDATE_PATH') . $nextVersion['name'] . '/old.zip');
        $this->unzipFile(C('UPDATE_PATH') . $nextVersion['name'] . '/old.zip', $old_file_path);

        $this->writeMessage('开始下载升级文件包。<br/>');
        $this->downloadFile(cloud_url() . cloudU('Appstore/Update/download', array('number' => $nextVersion['number'], 'type' => 'new')), C('UPDATE_PATH') . $nextVersion['name'] . '/new.zip');
        $this->unzipFile(C('UPDATE_PATH') . $nextVersion['name'] . '/new.zip', $new_file_path);
        $files = $this->treeDirectory($new_file_path, $new_file_path);
        foreach ($files as $v) {
            $this->writeFile($v);
        }
        $this->writeScript('enable()');
        S('nextVersion', $nextVersion);
        S('currentVersion', $currentVersion);
    }

    /**
     * 比对代码
     */
    public function compare()
    {
        $this->assignVersionInfo();
        $currentVersion = S('currentVersion');
        $nextVersion = S('nextVersion');
        $old_file_path = C('UPDATE_PATH') . $nextVersion['name'] . '/old';
        $new_file_path = C('UPDATE_PATH') . $nextVersion['name'] . '/new';
        $compared_with_old = $this->diff($old_file_path);
        $compared_with_new = $this->diff($new_file_path);
        $compared = $compared_with_old + $compared_with_new;

        $this->assign('path', C('UPDATE_PATH') . $currentVersion['name']);
        $this->assign('compared', $compared);
        $this->disableCheckUpdate();
        $this->display();
        $this->enable = 1;
        foreach ($compared as $key => $v) {
            $this->writeFile("{$this->convert($key, $v)}");
        }
        if ($this->enable) {
            $this->writeScript('enable()');
        }
    }

    /**
     * 覆盖代码
     */
    public function cover()
    {
        $this->assignVersionInfo();
        $nextVersion = S('nextVersion');
        $old_file_path = C('UPDATE_PATH') . $nextVersion['name'] . '/old';
        $new_file_path = C('UPDATE_PATH') . $nextVersion['name'] . '/new';
        $sub = date('Ymd-His');
        $backup_path = C('UPDATE_PATH') . $nextVersion['name'] . '/backup/' . $sub;
        $this->assign('backup_path', $backup_path);
        $need_back = $this->treeDirectory($new_file_path, $new_file_path);
        $this->disableCheckUpdate();
        $this->display();
        //备份文件
        $this->createFolder($backup_path);
        if (!file_exists($backup_path)) {
            $this->write(L('_BACKUP_CREATE_FAIL_PARAM_', array('file' => $backup_path, 'file_cloud' => C('CLOUD_PATH'))) . L('_PERIOD_'), 'danger');

            exit;
        } else {
            $this->write(L('_CREATE_BACKUP_FOLDER_') . $backup_path . L('_SUCCESS_'), 'success');
        }
        foreach ($need_back as $v) {
            $current_file = text($v);
            if ($current_file == '/update.sql') {
                continue;
            }
            $from = realpath('.' . $current_file);
            //替换斜杠，防止linux无法识别
            $des = realpath(str_replace('./', '', $backup_path)) . str_replace('/', DIRECTORY_SEPARATOR, $current_file);


            $des_dir = substr($des, 0, strrpos($des, DIRECTORY_SEPARATOR));
            $this->createFolder($des_dir);
            if (copy($from, $des)) {
                chmod($des, 0777);
                $this->write(str_replace('\\', '\\\\', L('_BACKUP_TO_PARAM_', array('file' => $current_file, 'addr' => str_replace('./', '', $backup_path) . $current_file))), 'success');

            } else {
                $this->write(str_replace('\\', '\\\\', L('_BACKUP_TO_FAIL_PARAM_', array('file' => $current_file, 'addr' => str_replace('./', '', $backup_path) . $current_file))) . L('_PERIOD_'), 'danger');
            }


        }
        $this->write(L('_FILE_FULL_BACKUP_COMPLETE_'));
        //覆盖文件

        foreach ($need_back as $v) {

            $from = realpath($new_file_path . text($v));
            $des = realpath('.' . str_replace('/', DIRECTORY_SEPARATOR, text($v)));

            if (!$des) {
                $des = str_replace('/', DIRECTORY_SEPARATOR, dirname(realpath('./index.php')) . text($v));
            }
            $des_dir = substr($des, 0, strrpos($des, DIRECTORY_SEPARATOR));
            if (!is_dir($des_dir)) {
                $this->createFolder($des_dir);
            }
            if (file_exists($des)) {
                unlink($des);
            }
            if (copy($from, $des)) {
                chmod($des, 0777);
                $this->writeFile(str_replace('\\', '\\\\', L('_COVER_FILE_') . $des) . L('_SUCCESS_DOT_SIX_'));
            } else {
                $this->writeFile(str_replace('\\', '\\\\', L('_COVER_FILE_') . $des) . L('_FAIL_DOT_SIX_'));
            }


        }
        $this->write(L('_FILE_FULL_COVERAGE_'));
        $this->writeScript('enable()');
    }


    /**
     * 升级数据库
     */
    public function updb()
    {
        $nextVersion = S('nextVersion');
        $new_file_path = C('UPDATE_PATH') . $nextVersion['name'];
        $sql_path = $new_file_path . '/new/update.sql';
        $sql = file_get_contents($sql_path);
        if (IS_POST) {
            if (!file_exists($sql_path)) {
                $this->error(L('_DATABASE_UPGRADE_SCRIPT_DOES_NOT_EXIST_'));
            } else {
                $result = D('')->executeSqlFile($sql_path);
                if ($result) {
                    $this->success(L('_SCRIPT_UPGRADE_SUCCESS_'));
                } else {
                    $this->error(L('_SCRIPT_UPGRADE_FAILED_'));
                }
            }
        } else {
            $this->assignVersionInfo();
            $this->assign('path', $new_file_path);
            if (file_exists($sql_path)) {
                $this->assign('sql', $sql);
            }
            $this->disableCheckUpdate();
            $this->display();
        }

    }

    /**
     * 升级完成
     */
    public function finish()
    {
        $nextVersion = S('nextVersion');
        $versionModel = D('Version');
        $versionModel->where(array('name' => $nextVersion['name']))->setField('update_time', time());
        $versionModel->setCurrentVersion($nextVersion['name']);
        $this->assign('currentVersion', $versionModel->getCurrentVersion());
        $new_file_path = C('UPDATE_PATH') . $nextVersion['name'];
        $this->assign('path', $new_file_path);
        $this->disableCheckUpdate();
        $versionModel->cleanCheckUpdateCache();
        clean_cache();
        $this->display();
    }

    /**递归方式创建文件夹
     * @param $dir
     * @param int $mode
     * @return bool
     */
    private function createFolder($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) {
            return true;
        }
        if (!$this->createFolder(dirname($dir), $mode)) {
            return false;
        }
        return @mkdir($dir, $mode);
    }

    /**转换状态文字
     * @param $v
     * @return string
     */
    private function convert($file, $v)
    {

        $html = "<tr><td>$file</td><td>";
        switch ($v[0]) {
            case 'add':
                $html .= '<span class="text-warning"> <i class="icon-plus"></i> ' . L('_HTML_ADD_') . '</span>';
                break;
            case 'modified':
                $html .= '<span class="text-danger" ><i class="icon-warning-sign"></i> ' . L('_HTML_CONFLICT_') . '</span>';
                break;
            case 'ok':
                $html .= '<span class="text-success"><i class="icon-check"></i> ' . L('_HTML_OK_') . '</span>';
                break;
            case 'db':
                $html .= '<span class="text-info"><i class="icon-cube"></i> ' . L('_HTML_GUIDE_DB_') . '</span>';
                break;
            case 'guide':
                $html .= '<span class="text-info"><i class="icon-cube"></i>' . L('_HTML_GUIDE_SCRIPT_') . '</span>';
                break;
            case 'info':
                $html .= '<span class="text-info"><i class="icon-cube"></i> ' . L('_HTML_VERSION_') . '</span>';
                break;

        }
        $html .= '</td><td>';
        if ($v[1]) {
            $html .= '<span class="text-success"><i class="icon-check"></i> ' . L('_HTML_FILE_WRITE_') . '</span>';
        } else {
            $html .= '<span class="text-danger"><i class="icon-warning-sign"></i>' . L('_HTML_FILE_WRITE_BAD_') . '</span>';
            $this->enable = 0;
        }
        $html .= '</td></tr>';
        return $html;

    }

    /**比较文件
     * @param $path
     * @return array
     */
    private function diff($path, $root = './', $ext_file = array('/update.sql' => array('db', 1), '/update.php' => array('guide', 1)))
    {
        $files = $this->treeDirectory($path, $path);
        $result = array();
        foreach ($files as $v) {
            $local_path = str_replace('//', '/', $root . text($v));
            $is_ext = false;
            foreach ($ext_file as $key => $ext) {
                if ($local_path == str_replace('//', '/', $root . $key)) {

                    $result[$v] = $ext;
                    $is_ext = true;
                    continue;
                }
            }
            chmod($path . text($v), 0777);
            chmod($local_path, 0777);
            if ($is_ext)
                continue;
            $md5_source = md5_file($path . text($v));

            $md5_local = md5_file($local_path);
            if (!$md5_local) {
                $result[$v] = array('add', 1);
            } else if ($md5_source != $md5_local) {
                $result[$v] = array('modified', is_writable($local_path));
            } else {
                $result[$v] = array('ok', is_writable($local_path));
            }
        }
        return $result;
    }

    private function getChmod($filepath)
    {
        return substr(base_convert(@fileperms($filepath), 10, 8), -4);
    }

    /**列出所有的文件
     * @param $dir
     * @param $root
     * @return array
     */
    private function treeDirectory($dir, $root)
    {
        $files = array();
        $dirpath = ($dir);
        $filenames = scandir($dir);
        foreach ($filenames as $filename) {
            if ($filename == '.' || $filename == '..') {
                continue;
            }

            $file = $dirpath . DIRECTORY_SEPARATOR . $filename;

            if (is_dir($file)) {
                $files = array_merge($files, $this->treeDirectory($file, $root));
            } else {
                $files[] = str_replace($root, '', str_replace('\\', '/', $dir . DIRECTORY_SEPARATOR . '<span class=text-success>' . $filename . '</span>'));
            }
        }

        return $files;
    }

    /**
     * 获取指定版本的信息
     */
    public function version()
    {
        $aName = I('get.name', '', 'text');
        $versionModel = D('Version');
        $version = $versionModel->where(array('name' => $aName))->find();
        $this->assign('log', nl2br($version['log']));
        $this->display('version');

    }

    private function assignUpdatingGoods($goods)
    {
        $cloudModel = D('Admin/Cloud');
        switch ($goods['entity']) {
            case 1:
                //todo 插件关联升级数据
                break;
            case 2:
                $goodsInfo = D('Common/Module')->getModule($goods['etitle']);
                $goodsInfo = $cloudModel->getVersionInfo($goodsInfo);
                break;
            case 3:
                $goodsInfo = D('Common/Theme')->getTheme($goods['etitle']);
                $goodsInfo = $cloudModel->getVersionInfo($goodsInfo);
                break;
        }
        $this->assign('goodsInfo', $goodsInfo);
        return $goodsInfo;
    }

    /**
     * 升级云市场扩展
     */
    public function updateGoods()
    {

        $aToken = I('get.token', '', 'text');
        $cloudModel = D('Admin/Cloud');
        $version = $cloudModel->getVersion($aToken);

        if (!$version) {
            $this->error(L('_EXPAND_NOT_EXIST_') . L('_PERIOD_'));
        }
        $versionList = D('Admin/Cloud')->getUpdateList($aToken);

        $this->assign('versionList', $versionList);
        $this->assign('token', $aToken);
        $this->assign('version', $version);
        S('version', $version);
        S('versionList', $versionList);
        S('token', $aToken);
        $this->assignUpdatingGoods($version['goods']);

        $this->meta_title = L('_EXTENDED_AUTO_UPGRADE_');
        $this->display();
    }

    /**
     * 自动升级云市场扩展第一步，下载文件
     */
    public function updating1()
    {

        /*初始化各类信息*/
        $version = S('version');
        $versionList = array_reverse(S('versionList'));
        $token = S('token');
        $this->assign('version', $version);
        $this->assign('versionList', $versionList);
        if (empty($version)) {
            $this->error(L('_THE_CURRENT_VERSION_OF_THE_INFORMATION_ACQUISITION_FAILS_'));
        }
        if (empty($versionList)) {
            $this->error(L('_NO_NEW_VERSION_IS_DETECTED_'));
        }
        $this->assignUpdatingGoods($version['goods']);
        /*展示模板*/
        $path = C('CLOUD_PATH') . $this->switchEntity($version['goods']['entity']) . '/' . $version['goods']['etitle'] . '/' . $versionList[0]['title'];
        $pathOld = $path . '/old';
        $pathNew = $path . '/new';
        $this->assign('path', $path);

        $this->meta_title = L('_UPDATE_FILE_EXPAND_');
        $this->display();
        set_time_limit(0);
        /*创建文件夹*/
        if (!$this->createFolder($pathOld)) {
            $this->write(L('_FAIL_CREATE_ORIGIN_FOLD_') . $pathOld, 'danger');
            return;
        }
        if (!$this->createFolder($pathNew)) {
            $this->write(L('_FAIL_CREATE_ORIGIN_FOLD_') . $pathNew, 'danger');
            return;
        }
        /*下载文件*/
        $this->write(L('_START_TO_DOWNLOAD_THE_ORIGINAL_') . $version['title'] . L('_FILE_'), 'info');
        $this->downloadFile(appstoreU('Appstore/Install/download', array('token' => $token, 'type' => 'current')), $path . '/old.zip');
        $this->write(L('_START_DOWNLOADING_THE_NEW_VERSION_') . $versionList[0]['title'] . L('_FILE_'), 'info');
        $this->downloadFile(appstoreU('Appstore/Install/download', array('token' => $token, 'type' => 'next')), $path . '/new.zip');
        /*解压缩文件夹*/
        $this->unzipFile($path . '/old.zip', $pathOld);

        $this->unzipFile($path . '/new.zip', $pathNew);

        $files = $this->treeDirectory($pathNew, $pathNew);
        foreach ($files as $v) {
            $this->writeFile($v);
        }
        $this->writeScript('enable()');
    }


    /**
     * 本地文件比较
     */
    public function updating2()
    {
        $version = S('version');
        $versionList = array_reverse(S('versionList'));
        $this->assign('version', $version);
        $this->assign('versionList', $versionList);
        if (empty($version)) {
            $this->error(L('_THE_CURRENT_VERSION_OF_THE_INFORMATION_ACQUISITION_FAILS_'));
        }
        if (empty($versionList)) {
            $this->error(L('_NO_NEW_VERSION_IS_DETECTED_'));
        }
        $this->assignUpdatingGoods($version['goods']);
        $this->meta_title = L('_UPDATE_LOCAL_EXPAND_');

        $path = C('CLOUD_PATH') . $this->switchEntity($version['goods']['entity']) . '/' . $version['goods']['etitle'] . '/' . $versionList[0]['title'];
        $pathOld = $path . '/old';
        $pathNew = $path . '/new';
        $old_file_path = $pathOld;
        $new_file_path = $pathNew;
        $compared_with_old = $this->diff($old_file_path, $this->switchDir($version['goods']['entity']), array($version['goods']['etitle'] . '/update.sql' => array('db', 1)));
        $compared_with_new = $this->diff($new_file_path, $this->switchDir($version['goods']['entity']), array($version['goods']['etitle'] . '/update.sql' => array('db', 1)));
        $compared = $compared_with_old + $compared_with_new;

        $this->assign('path', $path);
        $this->assign('compared', $compared);
        $this->disableCheckUpdate();
        $this->display();
        $this->enable = 1;
        foreach ($compared as $key => $v) {
            $this->writeFile("{$this->convert($key, $v)}");
        }
        if ($this->enable) {
            $this->writeScript('enable()');
        }

    }

    /**
     * 覆盖文件
     */
    public function updating3()
    {
        $version = S('version');
        $versionList = array_reverse(S('versionList'));
        $this->assign('version', $version);
        $this->assign('versionList', $versionList);
        if (empty($version)) {
            $this->error(L('_THE_CURRENT_VERSION_OF_THE_INFORMATION_ACQUISITION_FAILS_'));
        }
        if (empty($versionList)) {
            $this->error(L('_NO_NEW_VERSION_IS_DETECTED_'));
        }
        $this->assignUpdatingGoods($version['goods']);
        $this->meta_title = L('_UPDATE_CODE_EXPAND_');
        $path = C('CLOUD_PATH') . $this->switchEntity($version['goods']['entity']) . '/' . $version['goods']['etitle'] . '/' . $versionList[0]['title'];
        $pathOld = $path . '/old';
        $pathNew = $path . '/new';
        $old_file_path = $pathOld;
        $new_file_path = $pathNew;

        $sub = date('Ymd-His');
        $backup_path = $path . '/backup/' . $sub;
        $this->assign('backup_path', $path);
        $need_back = $this->treeDirectory($new_file_path, $new_file_path);
        $this->disableCheckUpdate();
        $this->display();

        //备份文件
        @mkdir($path . '/backup');
        @mkdir($backup_path);
        if (!file_exists($backup_path)) {
            $this->write(L('_BACKUP_CREATE_FAIL_PARAM_', array('file' => $backup_path, 'file_cloud' => C('CLOUD_PATH'))) . L('_PERIOD_'), 'danger');
            exit;
        } else {
            $this->write(L('_CREATE_BACKUP_FOLDER_') . $backup_path . L('_SUCCESS_'), 'success');
        }
        foreach ($need_back as $v) {
            if (text($v) == '') {
                continue;
            }
            $from = realpath($this->switchDir($version['goods']['entity']) . '/' . text($v));

            $des = realpath(str_replace('./', '', $backup_path)) . str_replace('/', DIRECTORY_SEPARATOR, text($v));
            $des_dir = substr($des, 0, strrpos($des, DIRECTORY_SEPARATOR));
            $this->createFolder($des_dir);
            copy($from, $des);
            if (file_exists($des) === false) {
                $this->write(L('_BACKUP_FILE_TO_FILE_') . str_replace('./', '', $backup_path) . text($v) . L('_FAILED,_PLEASE_CHECK_THE_FOLDER_PERMISSIONS_'), 'danger');
            } else {
                $this->write(str_replace(array('\\', '//'), array('\\\\', '/'), L('_BACKUP_TO_PARAM_', array('file' => $this->switchDir($version['goods']['entity']) . text($v), 'addr' => str_replace('./', '', $backup_path) . text($v)))), 'success');
            }
        }
        $this->write(L('_FILE_FULL_BACKUP_COMPLETE_'));
        //覆盖文件

        foreach ($need_back as $v) {

            $from = realpath($new_file_path . text($v));
            $des = str_replace(array('/', '.' . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR), array(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR), dirname(realpath('./index.php')) . $this->switchDir($version['goods']['entity']) . text($v));
            $des_dir = substr($des, 0, strrpos($des, DIRECTORY_SEPARATOR));
            if (!is_dir($des_dir)) {
                $this->createFolder($des_dir);
            }
            if (file_exists($des)) {
                unlink($des);
            }
            if (copy($from, $des)) {
                chmod($des, 0777);
                $this->writeFile(str_replace('\\', '\\\\', L('_COVER_FILE_') . $des) . L('_SUCCESS_DOT_SIX_'));
            } else {
                $this->writeFile(str_replace('\\', '\\\\', L('_COVER_FILE_') . $des) . L('_FAIL_DOT_SIX_'));
            }


        }
        $this->write(L('_FILE_FULL_COVERAGE_'));
        $this->writeScript('enable()');
    }

    /**
     * 导入数据库改动
     */
    public function updating4()
    {
        $version = S('version');
        $versionList = array_reverse(S('versionList'));

        $this->assignUpdatingGoods($version['goods']);
        $path = C('CLOUD_PATH') . $this->switchEntity($version['goods']['entity']) . '/' . $version['goods']['etitle'] . '/' . $versionList[0]['title'];
        $pathOld = $path . '/old';
        $pathNew = $path . '/new' . '/' . $version['goods']['etitle'] . '/';


        $sql_path = $pathNew . '/update.sql';
        if (IS_POST) {
            if (!file_exists($sql_path)) {
                $this->error(L('_DATABASE_UPGRADE_SCRIPT_DOES_NOT_EXIST_'));
            } else {
                $result = D('')->executeSqlFile($sql_path);
                if ($result === true) {
                    $this->success(L('_SCRIPT_UPGRADE_SUCCESS_'));
                } else {
                    $this->error(L('_SCRIPT_UPGRADE_FAILED_'));
                }
            }
        }
        $this->assign('version', $version);
        $this->assign('versionList', $versionList);
        if (empty($version)) {
            $this->error(L('_THE_CURRENT_VERSION_OF_THE_INFORMATION_ACQUISITION_FAILS_'));
        }
        if (empty($versionList)) {
            $this->error(L('_NO_NEW_VERSION_IS_DETECTED_'));
        }

        $this->assign('path', $pathNew);
        if (file_exists($sql_path)) {
            $this->assign('sql', file_get_contents($pathNew . '/update.sql'));
        }

        $this->display();
    }


    /**
     * 完成，设置版本号和token
     */
    public function updating5()
    {
        $version = S('version');
        $versionList = array_reverse(S('versionList'));
        $this->assign('version', $version);
        $this->assign('versionList', $versionList);
        $newToken = $versionList[0]['token']['token'];
        switch ($version['goods']['entity']) {
            case 1:
                //todo 插件设置Token
                break;
            case 2:
                $moduleModel = D('Common/Module');
                $moduleModel->setToken($version['goods']['etitle'], $newToken);
                $moduleModel->reloadModule($version['goods']['etitle']);
                S('version', $versionList[0]);
                $this->cleanModuleListCache();
                break;
            case 3:
                $themeModel = D('Common/Theme');
                $themeModel->setToken($version['goods']['etitle'], $newToken);
                S('version', $versionList[0]);
                $this->cleanThemeListCache();
                break;
        }

        $this->assignUpdatingGoods($version['goods']);

        $path = C('CLOUD_PATH') . $this->switchEntity($version['goods']['entity']) . '/' . $version['goods']['etitle'] . '/' . $versionList[0]['title'];


        $this->assign('token', $newToken);
        $this->assign('path', $path);
        $this->display();
    }

    private function cleanModuleListCache()
    {
        S('admin_modules', null);
    }

    private function cleanThemeListCache()
    {
        S('admin_themes', null);
    }

    /**
     * 安装程序
     */
    public function install()
    {
        $aToken = I('post.token', '', 'text');
        $aCookie = I('post.cookie', '', 'text');
        S('cloud_cookie', $aCookie);
        $this->display();
        set_time_limit(0);
        $this->write(L('_INSTALL_AUTO_START_'), 'info');
        $this->write('&nbsp;&nbsp;&nbsp;>' . L('_LINK_REMOTE_SERVER_'), 'info');
        //   $this->writeMessage(file_get_contents($this->url(cloudU('Appstore/Install/getVersion'))));
        $data = $this->curl(appstoreU('Appstore/Install/getVersion', array('token' => $aToken)));
        if ($data === 'false') {

            $this->write('&nbsp;&nbsp;&nbsp;>' . L('_LOGIN_SERVER_VERIFY_EXIT_'), 'danger');
            return;
        }
        $data = json_decode($data, true);


        if (!$data['status']) {
            $this->write(L('_RETURN_RESULT_FROM_SERER_FAIL_') . $data['info'], 'danger');
        }
        $version = $data['version'];
        switch ($version['goods']['entity']) {
            case 1:
                $this->installPlugin($version, $aToken);
                break;
            case 2:
                $this->installModule($version, $aToken);
                break;
            case 3:
                $this->installTheme($version, $aToken);
                break;
        }
    }

    private function installPlugin($version, $token)
    {
        $plugin['name'] = $version['goods']['etitle'];
        $plugin['alias'] = $version['goods']['title'];
        $this->write('&nbsp;&nbsp;&nbsp;>' . L('_INSTALLING_PARAM_', array('object' => '插件')) . '【' . $plugin['alias'] . '】【' . $plugin['name'] . '】');
        if (file_exists(ONETHINK_ADDON_PATH . '/' . $plugin['name'])) {
            //todo 进行版本检测
            $this->write('&nbsp;&nbsp;&nbsp;>' . L('_OBJECT_SAME_EXIST_PARAM_', array('object' => '插件')), 'danger');
            $this->goBack();
            return;
        }
        //下载文件

        $localPath = C('CLOUD_PATH') . $this->switchEntity($version['goods']['entity']) . '/';
        $this->createFolder($localPath);
        $localFile = $localPath . $plugin['name'] . '.zip';
        $this->downloadFile(appstoreU('Appstore/Index/download', array('token' => $token)), $localFile);
        chmod($localFile, 0777);
        //开始安装
        $this->write('开始安装插件......');
        $this->unzipFile($localFile, ONETHINK_ADDON_PATH);
        $rs = D('Addons')->install($plugin['name']);
        if ($rs === true) {

            $tokenFile = ONETHINK_ADDON_PATH . $plugin['name'] . '/token.ini';
            if (file_put_contents($tokenFile, $token)) {
                $this->write('&nbsp;&nbsp;&nbsp;>' . L('_SUCCESS_THEME_HAPPY_ENDING_PARAM_', array('object' => '插件')) . L('_PERIOD_'), 'success');
                $jump = U('Addons/index');
                sleep(2);
                $this->writeScript("location.href='$jump';");
                return true;
            } else {
                $this->write('&nbsp;&nbsp;&nbsp;>' . L('_SUCCESS_MODULE_INSTALL_BUT_PARAM_', array('object' => '插件', 'tokenFile' => $tokenFile)) . $token, 'warning');
                return true;
            }

        } else {
            $this->write('&nbsp;&nbsp;&nbsp;>' . L('_FAIL_INSTALL_ADDON_') . L('_PERIOD_'), 'danger');
        }

        //todo 进行文件合法性检测，防止错误安装。

    }

    private function installTheme($version, $token)
    {
        $theme['name'] = $version['goods']['etitle'];
        $theme['alias'] = $version['goods']['title'];
        $this->write('&nbsp;&nbsp;&nbsp;>' . L('_INSTALLING_PARAM_', array('object' => '主题')) . '【' . $theme['alias'] . '】【' . $theme['name'] . '】');
        if (file_exists(OS_THEME_PATH . $version['goods']['etitle'])) {
            //todo 进行版本检测
            $this->write('&nbsp;&nbsp;&nbsp;>' . L('_OBJECT_SAME_EXIST_PARAM_', array('object' => '主题')), 'danger');
            $this->goBack();
            return false;
        }
        //下载文件
        $localPath = C('CLOUD_PATH') . $this->switchEntity($version['goods']['entity']) . '/';
        $this->createFolder($localPath);
        $localFile = $localPath . $version['goods']['etitle'] . '.zip';
        $this->downloadFile(appstoreU('Appstore/Index/download', array('token' => $token)), $localPath . $version['goods']['etitle'] . '.zip');
        chmod($localFile, 0777);
        //开始安装
        $this->unzipFile($localFile, OS_THEME_PATH);
        $this->write('&nbsp;&nbsp;&nbsp;>' . L('_SUCCESS_UNZIP_'), 'success');
        //todo 进行文件合法性检测，防止错误安装。
        $this->write('&nbsp;&nbsp;&nbsp;>' . L('_SUCCESS_INSTALL_THEME_') . L('_PERIOD_'), 'success');
        $themeModel = D('Common/Theme');
        $res = $themeModel->setTheme($theme['name']);
        if ($res === true) {
            $tokenFile = OS_THEME_PATH . $theme['name'] . '/token.ini';
            if (file_put_contents($tokenFile, $token)) {
                $this->write('&nbsp;&nbsp;&nbsp;>' . L('_SUCCESS_THEME_HAPPY_ENDING_PARAM_', array('object' => '主题')) . L('_PERIOD_'), 'success');
                $jump = U('Theme/tpls', array('cleanCookie' => 1));
                sleep(2);
                $this->writeScript("location.href='$jump';");
                return true;
            } else {
                $this->write('&nbsp;&nbsp;&nbsp;>' . L('_SUCCESS_MODULE_INSTALL_BUT_PARAM_', array('object' => '主题', 'tokenFile' => $tokenFile)) . $token, 'warning');
                return true;
            }
        } else {
            $this->write('&nbsp;&nbsp;&nbsp;>，' . L('_THEME_USE_FAIL_') . $themeModel->getError(), 'danger');
            return false;
        }


    }

    private function installModule($version, $token)
    {
        $module['name'] = $version['goods']['etitle'];
        $module['alias'] = $version['goods']['title'];
        $this->write('&nbsp;&nbsp;&nbsp;>' . L('_INSTALLING_PARAM_', array('object' => '模块')) . '【' . $module['alias'] . '】【' . $module['name'] . '】');
        if (file_exists(APP_PATH . $version['goods']['etitle'])) {
            //todo 进行版本检测
            $this->write('&nbsp;&nbsp;&nbsp;>' . L('_OBJECT_SAME_EXIST_PARAM_', array('object' => '模块')), 'danger');
            $this->goBack();
            return false;
        }
        //下载文件
        $localPath = C('CLOUD_PATH') . $this->switchEntity($version['goods']['entity']) . '/';
        $this->createFolder($localPath);
        $localFile = $localPath . $version['goods']['etitle'] . '.zip';
        $this->downloadFile(appstoreU('Appstore/Index/download', array('token' => $token)), $localFile);
        //开始安装
        $this->unzipFile($localFile, APP_PATH);
        //todo 进行文件合法性检测，防止错误安装。
        if (!file_exists(APP_PATH . $version['goods']['etitle'] . '/' . 'Info/info.php')) {
            $this->write(L('_FILE_VERIFY_FAIL_PLEASE_'));
            exit;
        }
        $moduleModel = D('Common/Module');
        $moduleModel->reload();
        $module = $moduleModel->getModule($module['name']);
        $res = $moduleModel->install($module['id']);
        if ($res === true) {
            $this->write($moduleModel->getError());
            $this->write('&nbsp;&nbsp;&nbsp;>' . L('_SUCCESS_MODULE_INSTALL_') . L('_PERIOD_'), 'success');
            M('Channel')->where(array('url' => $module['entry']))->delete();
            $this->write('&nbsp;&nbsp;&nbsp;>' . L('_SUCCESS_NAV_CLEAR_') . L('_PERIOD_'), 'success');
            $channel['title'] = $module['alias'];
            $channel['url'] = $module['entry'];
            $channel['sort'] = 100;
            $channel['status'] = 1;
            $channel['icon'] = $module['icon'];
            M('Channel')->add($channel);
            S('common_nav', null);
            S('ADMIN_MODULES_' . is_login(), null);
            $this->write('&nbsp;&nbsp;&nbsp;>' . L('_SUCCESS_NAV_ADD_') . L('_PERIOD_'), 'success');
            $tokenFile = APP_PATH . $module['name'] . '/Info/token.ini';
            $this->cleanModuleListCache();
            if ($moduleModel->setToken($module['name'], $token)) {
                $this->write(L('_MODULE_INSTALLATION_SUCCESS_'), 'success');
                $jump = U('Module/lists');
                sleep(2);
                $this->writeScript("location.href='$jump';");
            } else {
                $this->write(L('_SUCCESS_MODULE_INSTALL_BUT_PARAM_', array('object' => '模块', 'tokenFile' => $tokenFile)) . $token, 'warning');
                return true;
            }

        } else {
            $this->write(L('_FAIL_MODULE_INSTALL_') . $moduleModel->getError(), 'warning');
        }

        return true;
    }

    private function downloadFile($url, $local)
    {
        $file = fopen($url, "rb");
        if ($file) {
            //获取文件大小
            $filesize = -1;
            $headers = get_headers($url, 1);
            if ((!array_key_exists("Content-Length", $headers))) $filesize = 0;
            $filesize = $headers["Content-Length"];
            //不是所有的文件都会先返回大小的，有些动态页面不先返回总大小，这样就无法计算进度了
            if (file_exists($local)) {
                unlink($local);
            }
            if (isset($headers['Location'])) {
                $url = $headers['Location'];
            }
            if (is_array($filesize)) {
                $filesize = $filesize[1];
            }
            $filesize = intval($filesize);

            if ($filesize != -1) {
                $this->write('&nbsp;&nbsp;&nbsp;' . L('_FILE_SIZE_TOTAL_') . number_format($filesize / 1024, 2) . 'KB');
                $this->write('&nbsp;&nbsp;&nbsp;' . L('_FILE_DOWNLOAD_START_'));
                // $this->showProgress();
            }
            /* $newf = fopen($local, "wb");
             $downlen = 0;
             $total = 0;
          /* if ($newf) {
                 while (!feof($file)) {
                     $data = fread($file, 1024 * 8);//默认获取8K
                     $downlen += strlen($data);//累计已经下载的字节数
                     fwrite($newf, $data, 1024 * 8);
                     $total += 1024 * 8;
                     if ($total > 1024 * 1024 * 2) {
                         $total = 0;
                         $this->setValue('"' . number_format($downlen / $filesize * 100, 2) . '%' . '"');
                         $this->replace('&nbsp;&nbsp;&nbsp;>已经下载' . number_format($downlen / $filesize * 100, 2) . '% - ' . number_format($downlen / 1024 / 1024, 2) . 'MB', 'success');
                     }
                 }
             }
             if ($file) {
                 fclose($file);
             }
             if ($newf) {
                 fclose($newf);
             }*/
            $this->getFile($url, $local);
            @chmod($local, 0777);
            if (filesize($local) == 0) {
                $this->replace('&nbsp;&nbsp;&nbsp;' . L('_FILE_SIZE_ERROR_'), 'danger');
                // $this->hideProgress();
                exit;
            }
            $this->replace('&nbsp;&nbsp;&nbsp;' . L('_FILE_DOWNLOAD_COMPLETE_'), 'success');
            $this->hideProgress();
        } else {
            $this->write('&nbsp;&nbsp;&nbsp;' . L('_FILE_DOWNLOAD_FAIL_TIP_'), 'danger');
            exit;
        }
    }

    private function getFile($url, $path, $type = 0)
    {
        if (trim($url) == '') {
            return false;
        }

        //获取远程文件所采用的方法
        if ($type) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $content = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $content = ob_get_contents();
            ob_end_clean();
        }
        $size = strlen($content);
        //文件大小
        $fp2 = @fopen($path, 'a');
        fwrite($fp2, $content);
        fclose($fp2);
        unset($content, $url);
        return $path;
    }

    private function setValue($val)
    {
        $js = "progress.setValue($val)";
        $this->writeScript($js);
    }

    private function showProgress()
    {
        $js = "progress.show();";
        $this->writeScript($js);
    }

    private function hideProgress()
    {
        $js = "progress.hide();";
        $this->writeScript($js);
    }

    private function url($url)
    {
        return cloud_url() . $url;
    }

    private function writeMessage($str)
    {
        $js = "writeMessage('$str')";
        $this->writeScript($js);
    }

    private function writeFile($str)
    {
        $js = "writeFile('$str')";
        $this->writeScript($js);
    }

    private function replaceMessage($str)
    {
        $js = "replaceMessage('$str')";
        $this->writeScript($js);
    }

    private function goBack()
    {
        $this->writeScript("setTimeout(function(){history.go(-1);},3000);");
    }

    private function writeScript($str)
    {
        echo "<script>$str</script>";
        ob_flush();
        flush();
    }

    private function replace($str, $type = 'info', $br = '<br>')
    {
        $this->replaceMessage('<span class="text-'.$type.'">'.$str.'</span>'.$br);
    }

    private function write($str, $type = 'info', $br = '<br>')
    {
        $this->writeMessage('<span class="text-'.$type.'">'.$str.'</span>'.$br);
    }


    private function curl($url)
    {
        return D('Admin/Curl')->curl($url);
    }


    /**
     * @param $localFile
     * @param $localPath
     */
    private function unzipFile($localFile, $localPath)
    {
        require_once("./ThinkPHP/Library/OT/PclZip.class.php");
        $archive = new \PclZip($localFile);
        $this->write('&nbsp;&nbsp;&nbsp;' . L('_UNZIP_START_'));
        $list = $archive->extract(PCLZIP_OPT_PATH, $localPath, PCLZIP_OPT_SET_CHMOD, 0777);
        if ($list === 0) {
            $this->write('&nbsp;&nbsp;&nbsp;' . L('_FAIL_UNZIP_') . $archive->errorInfo(true));
            exit;
        }
        unlink($localFile);
        $this->write('&nbsp;&nbsp;&nbsp;' . L('_SUCCESS_UNZIP_'), 'success');
    }

    private function assignVersionInfo()
    {
        $currentVersion = S('currentVersion');
        $nextVersion = S('nextVersion');
        $this->assign('nextVersion', $nextVersion);
        $this->assign('currentVersion', $currentVersion);
    }

    private function switchEntity($entity)
    {
        switch ($entity) {
            case 1:
                return 'Addons';
            case 2:
                return 'Module';
            case 3:
                return 'Theme';
        }
    }

    private function switchDir($entity)
    {
        switch ($entity) {
            case 1:
                return ONETHINK_ADDON_PATH;
            case 2:
                return APP_PATH;
            case 3:
                return OS_THEME_PATH;
        }
    }
} 