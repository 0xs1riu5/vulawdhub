<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-18
 * Time: 下午1:51
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Admin\Controller;

class ThemeController extends AdminController
{

    /**
     * 模版列表页
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function tpls()
    {
        $aCleanCookie = I('get.cleanCookie', 0, 'intval');
        if ($aCleanCookie) {
            cookie('TO_LOOK_THEME', null, array('prefix' => 'OSV2'));
        }
        // 根据应用目录取全部APP信息
        $dir = OS_THEME_PATH;

        /*刷新模块列表时清空缓存*/
        $aRefresh = I('get.refresh', 0, 'intval');
        if ($aRefresh == 1) {
        } else if ($aRefresh == 2) {
            S('admin_themes', null);

        }


        $tpls = S('admin_themes');
        if ($tpls === false) {
            $tpls = null;
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        //去掉"“.”、“..”以及带“.xxx”后缀的文件
                        if ($file != "." && $file != ".." && !strpos($file, ".")) {
                            if (is_file(OS_THEME_PATH . $file . '/info.php')) {
                                $tpl = require_once(OS_THEME_PATH . $file . '/info.php');
                                $tpl['path'] = OS_THEME_PATH . $file;
                                $tpl['file_name'] = $file;
                                $tpl['token']=file_get_contents(OS_THEME_PATH . $file . '/token.ini');
                                $tpls[] = $tpl;

                            }
                        }

                    }
                    closedir($dh);
                }
            }
            $tpls= D('Admin/Cloud')->getVersionInfoList($tpls);
            S('admin_themes', $tpls);
        }


        /*刷新模块列表时清空缓存 end*/


        $now_theme = modC('NOW_THEME', 'default', 'Theme');
        $this->assign('now_theme', $now_theme);
        $this->assign('tplList', $tpls);
        $this->display();
    }

    /**
     * 打包
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function packageDownload()
    {
        $aTheme = I('theme', '', 'text');
        if ($aTheme != '') {
            $themePath = OS_THEME_PATH;
            require_once("./ThinkPHP/Library/OT/PclZip.class.php");
            $archive = new \PclZip($themePath . $aTheme . '.zip');
            $data = $archive->create($themePath . $aTheme, PCLZIP_OPT_REMOVE_PATH, $themePath);
            if ($data) {
                $this->_download($themePath . $aTheme . '.zip', $aTheme . '.zip');
                return;
            } else {
                $this->error(L('_PACKAGE_FAILURE_'));
                return;
            }
        }
        $this->error(L('_PARAMETER_ERROR_'));
    }

    /**
     * 下载
     * @param $get_url
     * @param $file_name
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _download($get_url, $file_name)
    {
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . 'OpenSNS V2_Theme_' . $file_name);
        header('Content-Length: ' . filesize($get_url));
        error_reporting(0);
        readfile($get_url);
        flush();
        ob_flush();
        $this->_delFile($get_url);
        exit;
    }

    public function delete()
    {
        $aTheme = I('theme', '', 'text');
        if ($aTheme != '') {
            $themePath = OS_THEME_PATH . $aTheme;
            $res = $this->_deldir($themePath);
            if ($res) {
                $this->success(L('_DELETE_SUCCESS_'), U('Admin/Theme/tpls'));
                return;
            } else {
                $this->error(L('_DELETE_FAILED_'), U('Admin/Theme/tpls'));
                return;
            }
        }
        $this->error(L('_PARAMETER_ERROR_'), U('Admin/Theme/tpls'));
    }

    /**
     * 设置网站使用主题
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function setTheme()
    {

        $aTheme = I('post.theme', 'default', 'text');
        $themeModel = D('Common/Theme');
        if ($themeModel->setTheme($aTheme)) {
            $result['info'] = L('_SET_THE_THEME_TO_SUCCEED_');
            $result['status'] = 1;
        } else {
            $result['info'] = L('_SET_THE_THEME_OF_FAILURE_');
            $result['status'] = 0;
        }
        $this->ajaxReturn($result);
    }

    /**
     * 临时查看主题（管理员预览用）
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function lookTheme()
    {
        $aTheme = I('theme', '', 'text');
        $themeModel = D('Common/Theme');
        $res=$themeModel->lookTheme($aTheme);
        if($res){
            redirect(U('Home/Index/index'));
        }else{
            $this->error('请求失败！');
        }
    }

    public function add()
    {
        if (IS_POST) {
            $config = array(
                'maxSize' => 3145728,
                'rootPath' => OS_THEME_PATH,
                'savePath' => '',
                'saveName' => '',
                'exts' => array('zip', 'rar'),
                'autoSub' => true,
                'subName' => '',
                'replace' => true,
            );
            $upload = new \Think\Upload($config); // 实例化上传类
            $info = $upload->upload($_FILES);
            if (!$info) { // 上传错误提示错误信息
                $this->error($upload->getError());
            } else { // 上传成功
                $this->_unCompression($info['pkg']['savename']);
                $this->success(L('_INSTALLATION_SUCCESS_'), U('Admin/Theme/tpls'));
            }
        } else {
            $this->display();
        }
    }

    private function _unCompression($filename)
    {
        $ThemePkg = OS_THEME_PATH;
        require_once("./ThinkPHP/Library/OT/PclZip.class.php");
        $pcl = new \PclZip($ThemePkg . $filename);
        if ($pcl->extract($ThemePkg)) {
            $result = $this->_delFile($ThemePkg . $filename);
            if ($result) {
                return true;
            }
        }
        return false;
    }

    private function _delFile($path)
    {
        $result = @unlink($path);
        if ($result) {
            return true;
        } else {
            return false;
        }

    }

    private function _deldir($dir)
    {
        //先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->_deldir($fullpath);
                }
            }
        }

        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
} 