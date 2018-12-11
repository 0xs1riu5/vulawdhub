<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2016年11月6日
 *  系统环境检查类
 */
namespace core\basic;

use core\basic\Config;

class Check
{

    // 检查PHP版本
    public static function checkPHP()
    {
        if (PHP_VERSION < '5.3') {
            error('您服务器的PHP版本太低，本程序要求版本不小于 5.3');
        }
    }

    // 检查go扩展库
    public static function checkGo()
    {
        if (! extension_loaded('gd')) {
            error('您的服务器环境不支持gd扩展,将无法使用验证码！');
        }
    }

    // 检查mysqli扩展库
    public static function checkMysqli()
    {
        if (! extension_loaded('mysqli')) {
            error('您的服务器环境不支持mysqli扩展,将无法正常使用数据库！');
        }
    }

    // 检查curl扩展库
    public static function checkCurl()
    {
        if (! extension_loaded('curl')) {
            error('您的服务器环境不支持curl扩展,将无法使用API模式！');
        }
    }

    // 目录路径检查，不存在时根据配置文件选择是否自动创建
    public static function checkBasicDir()
    {
        if (Config::get('debug')) {
            check_dir(APP_PATH, true);
            check_dir(APP_PATH . '/common', true);
            check_dir(CONF_PATH, true);
            check_dir(RUN_PATH, true);
            check_dir(DOC_PATH . STATIC_DIR, true);
        }
    }

    // 启动应用检查
    public static function checkApp()
    {
        if (! is_dir(APP_PATH)) {
            error('系统尚未初始化，请打开系统调试模式！');
        }
        
        // 获取系统发布的应用
        $apps = Config::get('public_app', true);
        // 检查发布的模块是否存在
        ! $apps ? error('请设置可访问模块！') : '';
        
        // 判断自动转换状态
        if (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) {
            error('您系统PHP.ini配置magic_quotes_gpc为On状态，会导致数据存储异常，请先设置为Off状态.');
        }
        
        // 判断目录列表函数
        if (! function_exists('scandir')) {
            error('您系统PHP.ini配置中已经禁用scandir函数，会导致无法正常读取配置及模板文件，请先去除.');
        }
    }

    // 检查系统默认首页的文件是否存在，不存在进行自动创建
    public static function checkAppFile()
    {
        $apps = Config::get('public_app', true);
        check_dir(APP_CONTROLLER_PATH, true);
        check_file(CONF_PATH . '/config.php', true, "<?php \r\n return array(\r\n\t //'控制项'=>'值' 以分号，分割\r\n);");
        check_file(APP_CONTROLLER_PATH . '/IndexController.php', true, "<?php \r\r namespace app\\" . M . "\\controller;\r\r use core\\basic\\Controller; \r\r class IndexController extends Controller{\r\r\tpublic function index(){\r\t\t\$this->display('index.html');\r\t} \r\r}");
        check_file(APP_PATH . '/common/' . ucfirst(M) . 'Controller.php', true, "<?php \r\rnamespace app\\common;\r\ruse core\\basic\\Controller; \r\rclass " . ucfirst(M) . "Controller extends Controller{ \r\r}");
        // check_file(APP_PATH . '/common/' . ucfirst(M) . 'Model.php', true, "<?php \r\rnamespace app\\common;\r\ruse core\\basic\\Model; \r\rclass " . ucfirst(M) . "Model extends Model{ \r\r}");
    }

    // 检查客户端浏览器是否被允许，在同时设置黑白名单时，黑名单具有优先级更高，在设置了白名单时，将只允许白名单访问
    public static function checkBs()
    {
        $allow_bs = Config::get('access_rule.allow_bs', true);
        $deny_bs = Config::get('access_rule.deny_bs', true);
        // 都未设置时，直接通过
        if (! $allow_bs && ! $deny_bs)
            return true;
        // 客户端使用的系统
        $user_bs = get_user_bs();
        // 如果在黑名单则直接拒绝
        if (in_array($user_bs, $deny_bs)) {
            error('本站点设置了不允许' . $user_bs . '内核浏览器访问,请使用其它版本IE、火狐、谷歌等，国产浏览器请使用极速模式！');
        } elseif ($allow_bs && ! in_array($user_bs, $allow_bs)) {
            error('本站点设置了只允许' . implode(',', $allow_bs) . '内核浏览器访问,请使用这些浏览器！');
        }
    }

    // 检查客户端操作系统是否被允许,在同时设置黑白名单时，黑名单具有优先级更高,在设置了白名单时，将只允许白名单访问
    public static function checkOs()
    {
        $allow_os = Config::get('access_rule.allow_os', true);
        $deny_os = Config::get('access_rule.deny_os', true);
        // 都未设置时，直接通过
        if (! $allow_os && ! $deny_os)
            return true;
        // 客户端使用的系统
        $user_os = get_user_os();
        // 如果在黑名单则直接拒绝
        if (in_array($user_os, $deny_os)) {
            error('本站点设置了不允许' . $user_os . '访问,请使用其它操作系统！');
        } elseif ($allow_os && ! in_array($user_os, $allow_os)) {
            error('本站点设置了只允许' . implode(',', $allow_os) . '访问,请使用这些操作系统！');
        }
    }
}