<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2016年12月25日
 *  应用公共控制类
 */
namespace app\common;

use core\basic\Controller;

class AdminController extends Controller
{

    public function __construct()
    {
        // 登录后注入菜单信息
        if ($this->checkLogin()) {
            // 权限检测
            $this->checkLevel();
            
            $this->getSecondMenu(); // 获取同级菜单
            $this->assign('menu_tree', session('menu_tree')); // 注入菜单树
            $this->assign('menu_html', session('menu_html')); // 菜单HTML数
            
            if (session('area_tree')) {
                $area_html = make_area_Select(session('area_tree'), session('acode'));
                $this->assign('area_html', $area_html);
                if (count(session('area_tree')) == 1) {
                    $this->assign('one_area', true);
                }
            } else {
                session_unset();
                error('您账号的区域权限设置有误，无法正常登陆！', url('/admin/index/index'), 10);
            }
        }
        
        // 站点基础信息
        $model = new AdminModel();
        session('site', $model->getSite());
        cache_config();
        
        // 内容模型菜单注入
        $models = model('admin.content.Model');
        $this->assign('menu_models', $models->getSelectMunu());
        
        // 不进行表单检验的控制器
        $nocheck = array(
            'Upgrade'
        );
        
        // POST表单提交校验
        if ($_POST && ! in_array(C, $nocheck) && session('formcheck') != post('formcheck')) {
            if (! session('formcheck')) { // 会话目录缺失时重建目录
                create_session_dir(RUN_PATH . '/session/', 2);
            }
            alert_back('表单提交校验失败,请刷新后重试！');
        }
        
        // 非上传接口提交后或页面首次加载时，生成页面验证码
        if (($_POST || ! issetSession('formcheck')) && ! (C == 'Index' && F == 'upload') && ! (C == 'Index' && F == 'login')) {
            session('formcheck', get_uniqid());
        }
        
        $this->assign('formcheck', session('formcheck')); // 注入formcheck模板变量
        $this->assign('backurl', base64_encode(URL)); // 注入编码后的回跳地址
    }

    // 后台用户登录状态检查
    private function checkLogin()
    {
        // 免登录可访问页面
        $public_path = array(
            '/admin/Index/index', // 登陆页面
            '/admin/Index/login' // 执行登陆
        );
        
        if (session('sid') && $this->checkSid()) { // 如果已经登录直接true
            return true;
        } elseif (in_array('/' . M . '/' . C . '/' . F, $public_path)) { // 免登录可访问页面
            return false;
        } else { // 未登陆跳转到登陆页面
            location(url('/admin/Index/index'));
        }
    }

    // 检查会话id
    private function checkSid()
    {
        $sid = encrypt_string(session_id() . $_SERVER['HTTP_USER_AGENT'] . session('id'));
        if ($sid != session('sid') || session('M') != M) {
            session_destroy();
            return false;
        } else {
            return true;
        }
    }

    // 访问权限检查
    private function checkLevel()
    {
        // 免权限等级认证页面，即所有登陆用户都可以访问
        $public_path = array(
            '/admin/Index/index', // 登陆页
            '/admin/Index/home', // 主页
            '/admin/Index/loginOut', // 退出登陆
            '/admin/Index/ucenter', // 用户中心
            '/admin/Index/area', // 区域选择
            '/admin/Index/clearCache', // 清理缓存
            '/admin/Index/upload' // 上传文件
        );
        $levals = session('levels');
        $path1 = '/' . M . '/' . C;
        $path2 = '/' . M . '/' . C . '/' . F;
        
        if (session('id') == 1 || in_array(URL, $levals) || in_array($path2, $levals) || in_array($path1, $public_path) || in_array($path2, $public_path)) {
            return true;
        } else {
            error('您的账号权限不足，您无法执行该操作！');
        }
    }

    // 当前菜单的父类的子菜单，即同级菜单二级菜单
    private function getSecondMenu()
    {
        $menu_tree = session('menu_tree');
        $url = '/' . M . '/' . C . '/' . F;
        $len = 0;
        $primary_menu_url = '';
        $second_menu = array();
        
        // 直接比对找出最长匹配URL
        foreach ($menu_tree as $key => $value) {
            if (is_array($value->son)) {
                foreach ($value->son as $key2 => $value2) {
                    if (! $value2->url) // 如果为空，则跳过
                        continue;
                    $pos = strpos($url, $value2->url);
                    if ($pos !== false) {
                        $templen = strlen($value2->url);
                        if ($templen > $len) {
                            $len = $templen;
                            $primary_menu_url = $value->url;
                            $second_menu = $value->son;
                        }
                        break; // 如果匹配到已经找到父类，则结束
                    }
                }
            }
        }
        
        // 前面第一种无法匹配，则选择子菜单匹配，只需控制器通过即可，如翻页、增、改、删操作
        if (! $second_menu) {
            foreach ($menu_tree as $key => $value) {
                if (is_array($value->son)) {
                    foreach ($value->son as $key2 => $value2) {
                        if (strpos($value2->url, '/' . M . '/' . C . '/') === 0) {
                            $primary_menu_url = $value->url;
                            $second_menu = $value->son;
                            break;
                        }
                    }
                }
                if ($second_menu) { // 已经获取二级菜单到后退出
                    break;
                }
            }
        }
        $this->assign('primary_menu_url', $primary_menu_url);
        $this->assign('second_menu', $second_menu);
    }
}