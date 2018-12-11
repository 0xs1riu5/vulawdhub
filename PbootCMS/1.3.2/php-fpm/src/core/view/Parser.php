<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2016年11月6日
 *  模板解析引擎 
 */
namespace core\view;

class Parser
{

    // 模板内容
    private static $content;

    // 模板路径在嵌套时必须
    private static $tplPath;

    // 编译公共方法
    public static function compile($tplPath, $content)
    {
        self::$tplPath = $tplPath;
        self::$content = ltrim($content, "\xEF\xBB\xBF"); // 去除内容Bom信息;
                                                          
        // =====以下为直接输出方法=========
        self::parOutputUrl(); // 输出地址输出
        self::parOutputDefine(); // 输出常量
        self::parOutputVar(); // 输出变量
        self::parOutputObjVal(); // 输出对象
        self::parOutputArrVal(); // 输出数组
                                 
        // self::parOutputConfig(); // 输出配置参数
        
        self::parOutputSession(); // 输出会话Session
        self::parOutputCookie(); // 输出会话Cookie
        self::parOutputServer(); // 输出环境变量
        self::parOutputPost(); // 输出POST请求值
        self::parOutputGet(); // 输出GET请求值
        self::parOutputFun(); // 使用函数
                              
        // =========以下为逻辑控制方法==========
        self::parIf(); // IF语句
        self::parForeach(); // Foreach语句
        self::parSubForeach(); // Foreach语句嵌套
        self::parNote(); // 备注
        self::parPhp(); // PHP语句
                        
        // ============以下为变量解析方法==========
        self::parVar(); // 解析变量
        self::parObjVar(); // 解析对象
        self::parArrVar(); // 解析数组
                           
        // self::parConfigVar(); // 解析配置
        
        self::parSession(); // 解析Session
        self::parCookie(); // 解析Cookie
        self::parServer(); // 解析环境变量
        self::parPost(); // 解析POST请求值
        self::parGet(); // 解析GET请求值
        self::parFun(); // 解析函数
                        
        // 返回解释的内容
        return self::$content;
    }

    // 解析地址输出 {url./home/index/index}
    private static function parOutputUrl()
    {
        $pattern = '/\{url\.([^\}]+)\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php echo \\core\\basic\\Url::get('$1');?>", self::$content);
        }
    }

    // 解析输出常量 如：{DB_HOST}
    private static function parOutputDefine()
    {
        $pattern = '/\{([A-Z_]+)\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php echo $1;?>", self::$content);
        }
    }

    // 解析输出普通变量 如：{$name}
    private static function parOutputVar()
    {
        $pattern = '/\{\$([\w]+)\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php echo \$this->getVar('$1');?>", self::$content);
        }
    }

    // 解析输出对象变量 如：{$user->name}
    private static function parOutputObjVal()
    {
        $pattern = '/\{\$([\w]+)(->)(\{?)([^}]+)(\}?)\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php echo @\$this->getVar('$1')$2$3$4$5;?>", self::$content);
        }
    }

    // 解析输出数组变量 如：{$user['name']}
    private static function parOutputArrVal()
    {
        $pattern = '/\{\$([\w]+)(\[[\w\'\"-\[\]]+\])\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php echo @\$this->vars['$1']$2;?>", self::$content);
        }
    }

    // 解析输出配置 如：{$config.public_app},支持多级
    private static function parOutputConfig()
    {
        $pattern = '/\{\$config\.([\w\.]+)\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php print_r(\\core\\basic\\Config::get('$1'));?>", self::$content);
        }
    }

    // 解析输出Session变量 如：{$session.username},支持多级
    private static function parOutputSession()
    {
        $pattern = '/\{\$session\.([\w\.]+)\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php echo session('$1');?>", self::$content);
        }
    }

    // 解析输出Cookie变量 如：{$cookie.username}
    private static function parOutputCookie()
    {
        $pattern = '/\{\$cookie\.([\w]+)\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php echo cookie('$1');?>", self::$content);
        }
    }

    // 解析输出Server变量 如：{$server.PATH_INFO}
    private static function parOutputServer()
    {
        $pattern = '/\{\$server\.([\w]+)\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php echo escape_string(\$_SERVER['$1']);?>", self::$content);
        }
    }

    // 解析输出POST变量 如：{$post.username}
    private static function parOutputPost()
    {
        $pattern = '/\{\$post\.([\w]+)\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php echo post('$1');?>", self::$content);
        }
    }

    // 解析输出GET变量 如：{$get.username}
    private static function parOutputGet()
    {
        $pattern = '/\{\$get\.([\w]+)\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php echo get('$1');?>", self::$content);
        }
    }

    // 应用函数 如：{fun=md5('aaa')}
    private static function parOutputFun()
    {
        $pattern = '/\{fun\s?=\s?([^\}]+)\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php echo $1;?>", self::$content);
        }
    }

    // 解析if语句 例：{if(a==b)}aaaa{else}bbbb{/if}
    private static function parIf()
    {
        $pattern_if = '/\{if\(([^}]+)\)\s*\}/'; // 非闭合标签全部允许
        $pattern_end_if = '/\{\/if\}/';
        $pattern_else = '/\{else\}/';
        if (preg_match($pattern_if, self::$content)) {
            if (preg_match($pattern_end_if, self::$content)) {
                self::$content = preg_replace($pattern_if, "<?php if ($1) {?>", self::$content);
                self::$content = preg_replace($pattern_end_if, "<?php } ?>", self::$content);
                if (preg_match($pattern_else, self::$content)) {
                    self::$content = preg_replace($pattern_else, "<?php } else { ?>", self::$content);
                }
            } else {
                error('模板中if语句标签没有闭合！');
            }
        }
    }

    // 解析循环语句 {foreach $var(key,value,num)}...[num][value->name]或[value]...{/foreach}
    private static function parForeach()
    {
        $pattern_foreach = '/\{foreach\s+\$([\w]+)\(([\w]+),([\w]+)(,([\w]+))?\)\}/';
        $pattern_end_foreach = '/\{\/foreach\}/';
        
        if (preg_match_all($pattern_foreach, self::$content, $matches)) {
            $count = count($matches[0]);
            
            // if (preg_match_all($pattern_end_foreach, self::$content, $matches2)) {
            // if (count($matches2[0]) != $count)
            // error('模板中foreach语句标签没有闭合！');
            // }
            
            for ($i = 0; $i < $count; $i ++) {
                if (! $matches[5][$i]) {
                    $matches[5][$i] = 'num';
                }
                // 解析首标签
                self::$content = str_replace($matches[0][$i], "<?php \$" . $matches[5][$i] . " = 0;foreach (\$this->getVar('" . $matches[1][$i] . "') as \$" . $matches[2][$i] . " => \$" . $matches[3][$i] . ") { \$" . $matches[5][$i] . "++;?>", self::$content);
                
                // 解析序号
                $pattern_num = '/\[(' . $matches[5][$i] . ')\]/';
                if (preg_match($pattern_num, self::$content)) {
                    if (defined('PAGE')) {
                        self::$content = preg_replace($pattern_num, "<?php echo (PAGE-1)*PAGESIZE+\$$1; ?>", self::$content);
                    } else {
                        self::$content = preg_replace($pattern_num, "<?php echo \$$1; ?>", self::$content);
                    }
                }
                // 解析内部变量
                $pattern_var = '/\[(' . $matches[3][$i] . ')(\[[\'\"][\w]+[\'\"]\])?(\-\>[\w$]+)?\]/';
                self::$content = preg_replace($pattern_var, "<?php echo \$$1$2$3; ?>", self::$content);
            }
            // 解析闭合标签
            self::$content = str_replace('{/foreach}', "<?php } ?>", self::$content);
        }
    }

    // 解析循环语句嵌套 {foreach $value->name(key,value,num)}...[num][value->name]或[value]...{/foreach}
    private static function parSubForeach()
    {
        $pattern_foreach = '/\{foreach\s+\$([\w][\w->]+)\(([\w]+),([\w]+)(,([\w]+))?\)\}/';
        $pattern_end_foreach = '/\{\/foreach\}/';
        
        if (preg_match_all($pattern_foreach, self::$content, $matches)) {
            $count = count($matches[0]);
            
            // if (preg_match_all($pattern_end_foreach, self::$content, $matches2)) {
            // if (count($matches2[0]) != $count)
            // error('模板中foreach语句标签没有闭合！');
            // }
            
            for ($i = 0; $i < $count; $i ++) {
                if (! $matches[5][$i]) {
                    $matches[5][$i] = 'num';
                }
                // 解析首标签
                self::$content = str_replace($matches[0][$i], "<?php \$" . $matches[5][$i] . " = 0;foreach (\$" . $matches[1][$i] . " as \$" . $matches[2][$i] . " => \$" . $matches[3][$i] . ") { \$" . $matches[5][$i] . "++;?>", self::$content);
                
                // 解析序号
                $pattern_num = '/\[(' . $matches[5][$i] . ')\]/';
                if (preg_match($pattern_num, self::$content)) {
                    if (defined('PAGE')) {
                        self::$content = preg_replace($pattern_num, "<?php echo (PAGE-1)*PAGESIZE+\$$1; ?>", self::$content);
                    } else {
                        self::$content = preg_replace($pattern_num, "<?php echo \$$1; ?>", self::$content);
                    }
                }
                // 解析内部变量
                $pattern_var = '/\[(' . $matches[3][$i] . ')(\[[\'\"][\w]+[\'\"]\])?(\-\>[\w$]+)?\]/';
                self::$content = preg_replace($pattern_var, "<?php echo \$$1$2$3; ?>", self::$content);
            }
            // 解析闭合标签
            self::$content = str_replace('{/foreach}', "<?php } ?>", self::$content);
        }
    }

    // PHP代码注释{#}...{#}
    private static function parNote()
    {
        $pattern = '/\{#\}(\s\S]*?)\{#\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php /* $1 */?>", self::$content);
        }
    }

    // 原生PHP代码{php}...{/php}
    private static function parPhp()
    {
        $pattern = '/\{php\}([\s\S]*?)\{\/php\}/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "<?php  $1 ?>", self::$content);
        }
    }

    // 解析变量[$varname]
    private static function parVar()
    {
        $pattern = '/\[\$([\w]+)\]/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "\$this->getVar('$1')", self::$content);
        }
    }

    // 解析对象变量 [$user->name]
    private static function parObjVar()
    {
        $pattern = '/\[\$([\w]+)\-\>([\w$]+)\]/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "\$this->getVar('$1')->$2", self::$content);
        }
    }

    // 解析数组变量 [$user['name']]
    private static function parArrVar()
    {
        $pattern = '/\[\$([\w]+)\[([\'\"\w\[\]]+)\]\]/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "\$this->vars['$1'][$2]", self::$content);
        }
    }

    // 解析配置变量[$config.name],支持多级
    private static function parConfigVar()
    {
        $pattern = '/\[\$config\.([\w\.]+)\]/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "\\core\\basic\\Config::get('$1')", self::$content);
        }
    }

    // 解析Session [$session.name],支持多级
    private static function parSession()
    {
        $pattern = '/\[\$session\.([\w\.]+)\]/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "session('$1')", self::$content);
        }
    }

    // 解析Cookie [$cookie.name]
    private static function parCookie()
    {
        $pattern = '/\[\$cookie\.([\w]+)\]/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "cookie('$1')", self::$content);
        }
    }

    // 解析Server [$server.name]
    private static function parServer()
    {
        $pattern = '/\[\$server\.([\w]+)\]/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "escape_string(\$_SERVER['$1'])", self::$content);
        }
    }

    // 解析post [$post.id]
    private static function parPost()
    {
        $pattern = '/\[\$post\.([\w]+)\]/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "post('$1')", self::$content);
        }
    }

    // 解析Get[$get.id]
    private static function parGet()
    {
        $pattern = '/\[\$get\.([\w]+)\]/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "get('$1')", self::$content);
        }
    }

    // 内部应用函数 如：[fun=md5('aaa')]
    private static function parFun()
    {
        $pattern = '/\[fun=([^\]]+)\]/';
        if (preg_match($pattern, self::$content)) {
            self::$content = preg_replace($pattern, "$1", self::$content);
        }
    }
}
