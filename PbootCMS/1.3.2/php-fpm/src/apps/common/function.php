<?php
/**
 * @ copyright (C)2016-2099 Hnaoyun Inc.
 * @ license This is not a freeware, use is subject to license terms
 * @ author XingMeng
 * @ email hnxsh@foxmail.com
 * @ date 2017年4月14日
 * 公共处理函数
 */
use core\basic\Config;

// 获取字符串型自动编码
function get_auto_code($string, $start = '1')
{
    if (! $string)
        return $start;
    if (is_numeric($string)) { // 如果纯数字则直接加1
        return sprintf('%0' . strlen($string) . 's', $string + 1);
    } else { // 非纯数字则先分拆
        $reg = '/^([a-zA-Z-_]+)([0-9]+)$/';
        $str = preg_replace($reg, '$1', $string); // 字母部分
        $num = preg_replace($reg, '$2', $string); // 数字部分
        return $str . sprintf('%0' . (strlen($string) - strlen($str)) . 's', $num + 1);
    }
}

// 获取指定分类列表
function get_type($tcode)
{
    $type_model = model('admin.system.Type');
    if (! ! $result = $type_model->getItem($tcode)) {
        return $result;
    } else {
        return array();
    }
}

// 解析无限菜单为HTML
function make_tree_html($tree, $name, $url, $sonName = 'son')
{
    $tree_html = '';
    foreach ($tree as $value) {
        if (is_array($value)) {
            if ($value[$sonName] == '') {
                $tree_html .= "<li><a href='" . url($value[$url]) . "'>{$value[$name]}</a></li>";
            } else {
                $tree_html .= "<li><a href='" . url($value[$url]) . "'>{$value[$name]}</a>";
                $tree_html .= make_tree_html($value[$sonName], $name, $url, $sonName);
                $tree_html = $tree_html . "</li>";
            }
        } else {
            $menu_url = '';
            if (! $value->pcode) { // 顶级菜单指向第一个子菜单
                if (isset($value->son[0])) {
                    $menu_url = url($value->son[0]->url);
                }
            } else {
                $menu_url = url($value->$url);
            }
            if ($value->$sonName == '') {
                $tree_html .= "<li><a href='$menu_url'>{$value->$name}</a></li>";
            } else {
                $tree_html .= "<li><a href='$menu_url'>{$value->$name}</a>";
                $tree_html .= make_tree_html($value->$sonName, $name, $url, $sonName);
                $tree_html = $tree_html . "</li>";
            }
        }
    }
    return $tree_html ? '<ul>' . $tree_html . '</ul>' : $tree_html;
}

// 生成区域选择
function make_area_Select($tree, $selectid = null)
{
    $list_html = '';
    global $blank;
    foreach ($tree as $values) {
        // 默认选择项
        if ($selectid == $values->acode) {
            $select = "selected='selected'";
        } else {
            $select = '';
        }
        
        // 禁用父栏目选择功能
        if ($values->son) {
            $disabled = "disabled='disabled'";
        } else {
            $disabled = '';
        }
        $list_html .= "<option value='{$values->acode}' $select $disabled>{$blank}{$values->acode} {$values->name}";
        
        // 子菜单处理
        if ($values->son) {
            $blank .= '　　';
            $list_html .= make_area_Select($values->son, $selectid);
        }
    }
    // 循环完后回归位置
    $blank = substr($blank, 0, - 6);
    return $list_html;
}

// 检测指定的方法是否拥有权限
function check_level($btnAction, $isPath = false)
{
    $user_level = session('levels');
    if ($isPath) {
        if (in_array($btnAction, $user_level)) {
            return true;
        }
    } else {
        if (in_array('/' . M . '/' . C . '/' . $btnAction, $user_level) || session('id') == 1) {
            return true;
        }
    }
}

// 获取返回按钮
function get_btn_back($btnName = '返 回')
{
    if (! ! $backurl = get('backurl')) {
        $url = base64_decode($backurl);
    } elseif (isset($_SERVER["HTTP_REFERER"])) {
        $url = $_SERVER["HTTP_REFERER"];
    } else {
        $url = url('/' . M . '/' . C . '/index');
    }
    
    $btn_html = "<a href='" . $url . "' class='layui-btn layui-btn-primary'>$btnName</a>";
    return $btn_html;
}

// 获取新增按钮
function get_btn_add($btnName = '新 增')
{
    $user_level = session('levels');
    if (! in_array('/' . M . '/' . C . '/add', $user_level) && session('id') != 1)
        return;
    $btn_html = "<a href='" . url("/" . M . '/' . C . "/add") . "?backurl=" . base64_encode(URL) . "' class='layui-btn layui-btn-primary'>$btnName</a>";
    return $btn_html;
}

// 获取更多按钮
function get_btn_more($idValue, $id = 'id', $btnName = '详情')
{
    $btn_html = "<a href='" . url("/" . M . '/' . C . "/index/$id/$idValue") . "' class='layui-btn layui-btn-xs layui-btn-primary' title='$btnName'>$btnName</a>";
    return $btn_html;
}

// 获取删除按钮
function get_btn_del($idValue, $id = 'id', $btnName = '删除')
{
    $user_level = session('levels');
    if (! in_array('/' . M . '/' . C . '/del', $user_level) && session('id') != 1)
        return;
    $btn_html = "<a href='" . url('/' . M . '/' . C . "/del/$id/$idValue") . "' onclick='return confirm(\"您确定要删除么？\")' class='layui-btn layui-btn-xs layui-btn-danger' title='$btnName'>$btnName</a>";
    return $btn_html;
}

// 获取修改按钮
function get_btn_mod($idValue, $id = 'id', $btnName = '修改')
{
    $user_level = session('levels');
    if (! in_array('/' . M . '/' . C . '/mod', $user_level) && session('id') != 1)
        return;
    $btn_html = "<a href='" . url("/" . M . '/' . C . "/mod/$id/$idValue") . "?backurl=" . base64_encode(URL) . "'  class='layui-btn layui-btn-xs'>$btnName</a>";
    return $btn_html;
}

// 获取其它按钮
function get_btn($btnName, $theme, $btnAction, $idValue, $id = 'id')
{
    $user_level = session('levels');
    if (! in_array('/' . M . '/' . C . '/' . $btnAction, $user_level) && session('id') != 1)
        return;
    $btn_html = "<a href='" . url("/" . M . '/' . C . "/$btnAction/$id/$idValue") . "?backurl=" . base64_encode(URL) . "'  class='layui-btn layui-btn-xs $theme'>$btnName</a>";
    return $btn_html;
}

// 缓存基础信息
function cache_config($refresh = false)
{
    // 多语言缓存，不存在时自动缓存
    $lg_comm = RUN_PATH . '/config/' . md5('language') . '.php';
    if (! file_exists($lg_comm) || $refresh) {
        $model = model('admin.system.Config');
        $area = $model->getArea();
        if (! isset($area[0])) {
            error('系统没有任何可用区域，请核对后再试！');
        }
        $lgs['lgs'] = $area;
        // 缓存语言配置并注入
        Config::set(md5('language'), $lgs, false);
    }
    Config::assign($lg_comm); // 注入多语言
                              
    // 语言绑定域名， 如果匹配到多语言绑定则自动设置语言
    $lgs = Config::get('lgs');
    if (count($lgs) > 1) {
        $domain = get_http_host();
        foreach ($lgs as $value) {
            if ($value['domain'] == $domain) {
                cookie('lg', $value['acode']);
            }
        }
    }
    
    // 语言配置缓存，不存在时自动缓存，未设置时使用默认语言
    if (! isset($_COOKIE['lg'])) {
        $lg = Config::get('lgs.0.acode');
        cookie('lg', $lg);
    } else {
        $lg = cookie('lg');
    }
    $lg_config = RUN_PATH . '/config/' . md5('config' . $lg) . '.php';
    if (! file_exists($lg_config) || $refresh) {
        
        // 获取模型
        if (! isset($model)) {
            $model = model('admin.system.Config');
        }
        
        // 系统配置
        $data = $model->getConfig();
        
        // 获取系统设置的主题
        if (! ! $theme = $model->getTheme($lg)) {
            $data['theme'] = $theme;
        } else {
            $data['theme'] = 'default';
        }
        
        // 缓存语言配置
        Config::set(md5('config' . $lg), $data, false);
    }
    Config::assign($lg_config); // 注入语言配置
}

// 获取语言并进行安全处理
function get_lg()
{
    if (! cookie('lg')) {
        cookie('lg', Config::get('lgs.0.acode'));
    }
    $lg = cookie('lg');
    if (! preg_match('/^[\w-]+$/', $lg)) {
        $lg = Config::get('lgs.0.acode');
        cookie('lg', $lg);
    }
    return $lg;
}

// 推送百度
function post_baidu($api, $urls)
{
    $ch = curl_init();
    $options = array(
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT => 90,
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => implode("\n", $urls),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: text/plain'
        )
    );
    curl_setopt_array($ch, $options);
    $result = json_decode(curl_exec($ch));
    return $result;
}




