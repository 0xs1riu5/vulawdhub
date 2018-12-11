<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年11月4日
 *  系统基础类
 */
namespace core\basic;

class Basic
{

    protected static $models = array();

    // 实现类文件自动加载
    public static function autoLoad($className)
    {
        if (substr($className, 0, 4) == 'core') { // 框架类文件命名空间转换
            $class_file = CORE_PATH . '/' . str_replace('\\', '/', substr($className, 5)) . '.php';
        } elseif (substr($className, 0, 3) == 'app') { // 应用类文件命名空间转换
            $class_file = APP_PATH . '/' . str_replace('\\', '/', substr($className, 4)) . '.php';
        } elseif (strpos($className, '\\')) { // 如果带有命名空间，使用全路径载入
            $class_file = ROOT_PATH . '/' . str_replace('\\', '/', $className) . '.php';
        } else { // 默认载入内核基础目录下文件
            $class_file = CORE_PATH . '/basic/' . $className . '.php';
        }
        if (! file_exists($class_file)) {
            error('自动加载类文件时发生错误，类名【' . $className . '】，文件：【' . $class_file . '】');
        }
        require $class_file;
    }

    // 自定义错误函数
    public static function errorHandler($errno, $errstr, $errfile, $errline)
    {
        if (! (error_reporting() & $errno)) {
            // 如果这个错误类型没有包含在error_reporting里，如加了@的错误则不报告
            return;
        }
        switch ($errno) {
            case E_ERROR:
                $err_level = 'ERROR';
                break;
            case E_WARNING:
                $err_level = 'WARNING';
                break;
            case E_PARSE:
                $err_level = 'PARSE';
                break;
            case E_NOTICE:
                $err_level = 'NOTICE';
                break;
            default:
                $err_level = 'UNKNOW';
                break;
        }
        $info = "<h3>$err_level:</h3>\n";
        $info .= "<p><b>Code:</b> $errno;</p>\n";
        $info .= "<p><b>Desc:</b> $errstr;</p>\n";
        $info .= "<p><b>File:</b> $errfile;</p>\n";
        $info .= "<p><b>Line:</b> $errline;</p>\n";
        
        if ($err_level == 'ERROR' || $err_level == 'UNKNOW') {
            error($info);
        } else {
            echo $info;
        }
    }

    // 异常捕获
    public static function exceptionHandler($exception)
    {
        error("程序运行异常: " . $exception->getMessage() . "，位置：" . $exception->getFile() . '，第' . $exception->getLine() . '行。');
    }

    // 会话处理程序设置
    public static function setSessionHandler()
    {
        if (ini_get('session.auto_start')) {
            return;
        }
        
        // 配置会话安全参数
        session_name('PbootSystem');
        ini_set("session.use_trans_sid", 0);
        ini_set("session.use_cookies", 1);
        ini_set("session.use_only_cookies", 1);
        session_set_cookie_params(0, SITE_DIR . '/', null, null, true);
        
        switch (Config::get('session.handler')) {
            case 'memcache':
                if (! extension_loaded('memcache'))
                    error('PHP运行环境未安装memcache.dll扩展！');
                ini_set("session.save_handler", "memcache");
                ini_set("session.save_path", Config::get('seesion.path'));
                break;
            default:
                $save_path = RUN_PATH . '/session/';
                if (! check_dir($save_path, true))
                    error('设置的会话路径目录创建失败！');
                ini_set("session.save_handler", "files");
                $depth = 2;
                ini_set("session.save_path", $depth . ';' . $save_path);
                if (! is_dir($save_path . '/0/0') || ! is_dir($save_path . '/v/v')) {
                    create_session_dir($save_path, $depth);
                }
                break;
        }
    }

    // 自动实例化模型
    public static function createModel($name, $new = false)
    {
        if (! isset(self::$models[$name]) || $new) {
            if (strpos($name, '.') !== false) {
                $model = explode('.', $name);
                $class_name = '\\app\\' . $model[0] . '\\model';
                $len = count($model);
                for ($i = 1; $i < $len - 1; $i ++) {
                    $class_name .= '\\' . $model[$i];
                }
                $class_name .= '\\' . ucfirst($model[$i]) . 'Model';
            } else {
                $class_name = '\\app\\' . M . '\\model\\' . ucfirst($name) . 'Model';
            }
            self::$models[$name] = new $class_name();
        }
        return self::$models[$name];
    }
}


