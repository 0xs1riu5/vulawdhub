<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2016年11月6日
 *  模板显示类 
 */
namespace core\view;

use core\basic\Config;

class View
{

    // 模板路径
    protected $tplPath;

    // 编译路径
    protected $tplcPath;

    // 缓存路径
    protected $cachePath;

    // 存储注入变量
    protected $vars = array();

    // 存储包含文件
    protected $incFile = array();

    // 实例
    protected static $view;

    // 获取单一实例
    public static function getInstance()
    {
        if (! self::$view) {
            self::$view = new self();
        }
        return self::$view;
    }

    // 禁止通过new实例化类
    private function __construct()
    {
        $this->tplPath = APP_VIEW_PATH;
        $this->tplcPath = RUN_PATH . '/complile';
        $this->cachePath = RUN_PATH . '/cache';
        check_dir($this->tplcPath, true);
        check_dir($this->cachePath, true);
    }

    private function __clone()
    {
        die('不允许克隆对象！请使用getInstance获取实例');
    }

    // 变量注入
    public function assign($var, $value)
    {
        if (! empty($var)) {
            if (isset($this->vars[$var])) {
                error('模板变量$' . $var . '出现重复注入！');
            }
            $this->vars[$var] = $value;
            return true;
        } else {
            error('传递的设置模板变量有误');
        }
    }

    // 变量获取
    public function getVar($var)
    {
        if (! empty($var)) {
            if (isset($this->vars[$var])) {
                return $this->vars[$var];
            } else {
                return null;
            }
        } else {
            error('传递的获取模板变量有误');
        }
    }

    // 解析模板文件
    public function parser($file)
    {
        // 设置主题
        $theme = isset($this->vars['theme']) ? $this->vars['theme'] : 'default';
        
        if (! is_dir($this->tplPath .= '/' . $theme)) { // 检查主题是否存在
            if ($theme == 'default') { // 默认主题不存在且未默认的，自动初始化
                check_file($this->tplPath . '/index.html', true, '<h2>(- -)欢迎您使用本系统，请开始您的开发旅程吧!</h2>');
            } else {
                error('模板主题目录不存在！主题路径：' . $this->tplPath);
            }
        }
        
        // 定义当前应用主题目录
        define('APP_THEME_DIR', str_replace(DOC_PATH, '', APP_VIEW_PATH) . '/' . $theme);
        
        $file = str_replace('../', '', $file); // 过滤掉相对路径
        $tpl_file = $this->tplPath . '/' . $file; // 模板文件
        file_exists($tpl_file) ?: error('模板文件' . $file . '不存在！');
        $tpl_c_file = $this->tplcPath . '/' . md5($tpl_file) . '.php'; // 编译文件
        
        $content = file_get_contents($tpl_file) ?: error('模板文件' . $file . '读取错误！'); // 读取模板内容
        $content = $this->parserInc($content); // 解析包含文件
                                               
        // 当编译文件不存在，或者模板文件修改过，则重新生成编译文件
        if (! file_exists($tpl_c_file) || filemtime($tpl_c_file) < filemtime($tpl_file) || ! Config::get('tpl_parser_cache')) {
            $content = Parser::compile($this->tplPath, $content); // 解析模板
            file_put_contents($tpl_c_file, $content) ?: error('编译文件' . $tpl_c_file . '生成出错！请检查目录是否有可写权限！'); // 写入编译文件
        }
        
        // 获取编译后内容返回
        ob_start();
        include $tpl_c_file;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    // 处理包含文件
    private function parserInc($content)
    {
        $pattern = '/\{include\s+file\s?=\s?([\"\']?)([\w\.\-\/]+)([\"\']?)\s*\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $arr = $matches[0]; // 匹配到的所有“包含字符串”：{include file='head.html'}
            $brr = $matches[2]; // 包含的文件名：head.html
            $count = count($arr);
            for ($i = 0; $i < $count; $i ++) {
                // 然包含文件支持绝对路径，以/开头
                if (strpos($brr[$i], '/') === 0) {
                    $inc_file = ROOT_PATH . $brr[$i];
                } else {
                    $inc_file = $this->tplPath . '/' . $brr[$i];
                }
                $inc_c_file = $this->tplcPath . '/' . md5($inc_file) . '.php';
                
                // 当包含文件的编译文件不存在，或者模板文件修改过，则重新生成编译文件
                if (! file_exists($inc_c_file) || filemtime($inc_c_file) < filemtime($inc_file) || ! Config::get('tpl_parser_cache')) {
                    file_exists($inc_file) ?: error('包含文件' . $brr[$i] . '不存在！');
                    if (! $inc_content = file_get_contents($inc_file)) {
                        error('包含的模板文件' . $brr[$i] . '读取错误！');
                    } else {
                        $inc_content = Parser::compile($this->tplPath, $inc_content); // 解析包含文件
                                                                                      
                        // 实现多层嵌套，同时避免嵌套环路
                        if (! in_array($inc_file, $this->incFile)) {
                            $this->incFile[] = $inc_file;
                            $inc_content = $this->parserInc($inc_content);
                        }
                        // 生成包含文件解析文件
                        file_put_contents($inc_c_file, $inc_content) ?: error('包含文件' . $inc_c_file . '生成出错！请检查目录是否有可写权限！'); // 写入编译文件
                        $content = str_replace($arr[$i], "<?php include_once '" . $inc_c_file . "' ?>", $content);
                    }
                } else {
                    // 如果公共文件已经存在，则直接引用
                    $content = str_replace($arr[$i], "<?php include_once '" . $inc_c_file . "' ?>", $content);
                }
            }
        }
        return $content;
    }

    // 缓存页面， 开启缓存开关时有效
    public function cache($content)
    {
        if (Config::get('tpl_html_cache')) {
            $lg = cookie('lg');
            if (Config::get('open_wap') && (is_mobile() || Config::get('wap_domain') == get_http_host())) {
                $wap = 'wap';
            } else {
                $wap = '';
            }
            $cacheFile = $this->cachePath . '/' . md5($_SERVER["REQUEST_URI"] . $lg . $wap) . '.html'; // 缓存文件
            file_put_contents($cacheFile, $content) ?: error('缓存文件' . $cacheFile . '生成出错！请检查目录是否有可写权限！'); // 写入缓存文件
            return true;
        }
        return false;
    }
}
