<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年11月6日
 *  生成指定模块下控制器方法的跳转路径
 */
namespace core\basic;

class Url
{

    // 存储已经生成过的地址信息
    private static $urls = array();

    // 接收控制器方法完整访问路径，如：/home/Index/index /模块/控制器/方法/.. 路径，生成可访问地址
    public static function get($path, $addExt = true)
    {
        if (strpos($path, 'http') === 0) {
            return $path;
        }
        if (! $path)
            return;
        
        if (! isset(self::$urls[$path])) {
            $cut_str = '';
            $host = '';
            
            if ($addExt) {
                $url_ext = Config::get('url_suffix'); // 地址后缀
            } else {
                $url_ext = '';
            }
            $path = trim_slash($path); // 去除两端斜线
            $path_arr = explode('/', $path); // 地址数组
                                             
            // 路由处理
            if (! ! $routes = Config::get('url_route')) {
                foreach ($routes as $key => $value) {
                    $value = trim_slash($value); // 去除两端斜线
                    if (strpos($path, $value . '/') === 0) {
                        $path = str_replace($value . '/', $key . '/', $path);
                        $route = true;
                        break;
                    } elseif ($path == $value) {
                        $path = $key;
                        $route = true;
                        break;
                    }
                }
            }
            
            // 域名绑定处理匹配
            if (! ! $domains = Config::get('app_domain_blind')) {
                foreach ($domains as $key => $value) {
                    $value = trim_slash($value); // 去除两端斜线
                    if (strpos($path, $value . '/') === 0) {
                        $cut_str = $value; // 需要截掉的地址字符
                        $server_name = $_SERVER['SERVER_NAME'];
                        if ($server_name != $key) { // 绑定的域名与当前域名不一致时，添加主机地址
                            $host = is_https() ? 'https://' . $key : 'http://' . $key;
                        }
                        break;
                    }
                }
            }
            
            // 入口文件绑定匹配
            if (defined('URL_BLIND') && $path_arr[0] == M) {
                $url_blind = trim_slash(URL_BLIND);
                // 已经匹配过域名绑定
                if ($cut_str) {
                    // 地址中域名绑定不包含入口绑定且入口绑定中包含域名绑定
                    if (strpos($cut_str, $url_blind) === false && strpos($url_blind, $cut_str) === 0) {
                        $cut_str = $url_blind;
                    }
                } else {
                    $cut_str = $url_blind;
                }
            }
            
            // 执行URL简化
            if ($cut_str) {
                $path = substr($path, strlen($cut_str) + 1);
            }
            
            // 保存处理过的地址
            if ($path) {
                if ($path_arr[0] != M && $path_arr[0] == 'home') { // 对于后台处理home模块链接做特殊处理
                    $path = substr($path, 5);
                    if (Config::get('url_type') == 2) {
                        self::$urls[$path] = $host . SITE_DIR . '/' . $path . $url_ext;
                    } else {
                        self::$urls[$path] = $host . SITE_DIR . '/index.php/' . $path;
                    }
                } else {
                    if (is_rewrite()) {
                        self::$urls[$path] = $host . self::getPrePath() . '/' . $path . $url_ext;
                    } else {
                        self::$urls[$path] = $host . self::getPrePath() . '/' . $path;
                    }
                }
            } else {
                self::$urls[$path] = $host . self::getPrePath(); // 获取根路径前置地址
            }
        }
        return self::$urls[$path];
    }

    // 获取地址前缀
    private static function getPrePath()
    {
        if (is_rewrite()) {
            $pre_path = SITE_DIR;
        } else {
            $pre_path = $_SERVER["SCRIPT_NAME"];
        }
        return $pre_path;
    }
}