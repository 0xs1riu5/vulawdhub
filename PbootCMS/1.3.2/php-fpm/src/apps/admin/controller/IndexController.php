<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年3月13日
 *  默认主页
 */
namespace app\admin\controller;

use core\basic\Controller;
use app\admin\model\IndexModel;

class IndexController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new IndexModel();
    }

    // 登陆页面
    public function index()
    {
        if (session('sid')) {
            location(url('admin/Index/home'));
        }
        $this->assign('admin_check_code', $this->config('admin_check_code'));
        $this->display('index.html');
    }

    // 主页面
    public function home()
    {
        // 自动修改数据库名称
        if (get('action') == 'moddb') {
            $file = CONF_PATH . '/database.php';
            $sname = $this->config('database.dbname');
            $dname = '/data/#' . time() . mt_rand(1000, 9999) . '.db';
            $sconfig = file_get_contents($file);
            $dconfig = str_replace($sname, $dname, $sconfig);
            if (file_put_contents($file, $dconfig)) {
                if (! copy(ROOT_PATH . $sname, ROOT_PATH . $dname)) {
                    file_put_contents($file, $sconfig);
                    alert_back('修改失败！');
                } else {
                    session('deldb', $sname);
                    alert_back('修改成功！');
                }
            }
        }
        
        // 删除修改后老数据库（上一步无法直接删除）
        if (issetSession('deldb')) {
            @unlink(ROOT_PATH . session('deldb'));
            unset($_SESSION['deldb']);
        }
        
        $this->assign('shortcuts', session('shortcuts'));
        $dbsecurity = true;
        // 如果是sqlite数据库，并且路径为默认的，则标记为不安全
        if (get_db_type() == 'sqlite') {
            if ($this->config('database.dbname') == '/data/#pbootcms.db' || $this->config('database.dbname') == '/data/pbootcms.db') {
                $dbsecurity = false;
            }
        }
        $this->assign('dbsecurity', $dbsecurity);
        $this->assign('server', get_server_info());
        $this->assign('branch', $this->config('upgrade_branch') ?: '1.X');
        $this->display('system/home.html');
    }

    // 异步登录验证
    public function login()
    {
        if (! $_POST) {
            return;
        }
        
        // 在安装了gd库时才执行验证码验证
        if (extension_loaded("gd") && $this->config('admin_check_code') && post('checkcode') != session('checkcode')) {
            json(0, '验证码错误！');
        }
        
        // 就收数据
        $username = post('username');
        $password = post('password');
        
        if (! preg_match('/^[\x{4e00}-\x{9fa5}\w\-\.@]+$/u', $username)) {
            json(0, '用户名含有不允许的特殊字符！');
        }
        
        if (! $username) {
            json(0, '用户名不能为空！');
        }
        
        if (! $password) {
            json(0, '密码不能为空！');
        }
        
        // 执行用户登录
        $where = array(
            'username' => $username,
            'password' => encrypt_string($password)
        );
        
        // 判断数据库写入权限
        if ((get_db_type() == 'sqlite') && ! is_writable(ROOT_PATH . $this->config('database.dbname'))) {
            json(0, '数据库目录写入权限不足！');
        }
        
        if (! ! $login = $this->model->login($where)) {
            
            session_regenerate_id(true);
            session('sid', encrypt_string(session_id() . $_SERVER['HTTP_USER_AGENT'] . $login->id)); // 会话标识
            session('M', M);
            
            session('id', $login->id); // 用户id
            session('ucode', $login->ucode); // 用户编码
            session('username', $login->username); // 用户名
            session('realname', $login->realname); // 真实名字
            
            if ($where['password'] != '14e1b600b1fd579f47433b88e8d85291') {
                session('pwsecurity', true);
            }
            
            session('acodes', $login->acodes); // 用户管理区域
            if ($login->acodes) { // 当前显示区域
                session('acode', $login->acodes[0]);
            } else {
                session('acode', '');
            }
            
            session('rcodes', $login->rcodes); // 用户角色代码表
            session('levels', $login->levels); // 用户权限URL列表
            session('menu_tree', $login->menus); // 菜单树
            $menu_html = make_tree_html($login->menus, 'name', 'url');
            session('menu_html', $menu_html); // 菜单HTML代码
            session('shortcuts', $login->shortcuts); // 桌面图标
            
            session('area_map', $login->area_map); // 区域代码名称映射表
            session('area_tree', $login->area_tree); // 用户区域树
            
            $this->log('登入成功!');
            json(1, url('admin/Index/home'));
        } else {
            $this->log('登入失败!');
            json(0, '用户名或密码错误！');
        }
    }

    // 退出登录
    public function loginOut()
    {
        session_unset();
        location(url('/admin/index/index'));
    }

    // 用户中心，修改密码
    public function ucenter()
    {
        if ($_POST) {
            $username = post('username'); // 用户名
            $realname = post('realname'); // 真实姓名
            $cpassword = post('cpassword'); // 现在密码
            $password = post('password'); // 新密码
            $rpassword = post('rpassword'); // 确认密码
            
            if (! $username) {
                alert_back('用户名不能为空！');
            }
            if (! $cpassword) {
                alert_back('当前密码不能为空！');
            }
            
            if (! preg_match('/^[\x{4e00}-\x{9fa5}\w\-\.@]+$/u', $username)) {
                alert_back('用户名含有不允许的特殊字符！');
            }
            
            $data = array(
                'username' => $username,
                'realname' => $realname,
                'update_user' => $username
            );
            
            // 如果有修改密码，则添加数据
            if ($password) {
                if ($password != $rpassword) {
                    alert_back('确认密码不正确！');
                }
                $data['password'] = encrypt_string($password);
                if ($data['password'] != '14e1b600b1fd579f47433b88e8d85291') {
                    session('pwsecurity', true);
                } else {
                    session('pwsecurity', false);
                }
            }
            
            // 检查现有密码
            if ($this->model->checkUserPwd(encrypt_string($cpassword))) {
                if ($this->model->modUserInfo($data)) {
                    session('username', post('username'));
                    session('realname', post('realname'));
                    $this->log('用户资料成功！');
                    success('用户资料修改成功！', - 1);
                }
            } else {
                $this->log('用户资料修改时当前密码错误！');
                alert_location('当前密码错误！', - 1);
            }
        }
        $this->display('system/ucenter.html');
    }

    // 切换显示的数据区域
    public function area()
    {
        if ($_POST) {
            $acode = post('acode');
            if (in_array($acode, session('acodes'))) {
                session('acode', $acode);
            }
            location(- 1);
        }
    }

    // 清理缓存
    public function clearCache()
    {
        if (get('delall')) {
            $rs = path_delete(RUN_PATH);
        } else {
            $rs = (path_delete(RUN_PATH . '/cache') && path_delete(RUN_PATH . '/complile') && path_delete(RUN_PATH . '/config') && path_delete(RUN_PATH . '/upgrade'));
        }
        if ($rs) {
            if (extension_loaded('Zend OPcache')) {
                opcache_reset(); // 在启用了OPcache加速器时同时清理
            }
            $this->log('清理缓存成功！');
            alert_back('清理缓存成功！');
        } else {
            $this->log('清理缓存失败！');
            alert_back('清理缓存失败！');
        }
    }

    // 文件上传方法
    public function upload()
    {
        $upload = upload('upload');
        echo json_encode($upload);
    }
}