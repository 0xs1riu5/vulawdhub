<?php
/* 
	load: css, js 静态文件 
	启用 gz压缩、缓存处理、过期处理、文件合并等优化操作
*/
error_reporting(0);

if (extension_loaded('zlib')) {
    //检查服务器是否开启了zlib拓展
    ob_start('ob_gzhandler');
}

$gettype = 'js';
$allowed_content_types = array('js');


$module = strip_tags($_GET['module']);
$lang = strip_tags($_GET['lang']);

$offset = 60 * 60 * 24 * 7; //过期7天

if ($gettype == 'css') {
    $content_type = 'text/css';
} elseif ($gettype == 'js') {
    $content_type = 'application/x-javascript';
}

header("content-type: " . $content_type . "; charset: utf-8"); //注意修改到你的编码
// header ( "cache-control: must-revalidate" );
header("cache-control: max-age=" . $offset);
header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()) . "GMT");
header("Pragma: max-age=" . $offset);
header("Expires:" . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
set_cache_limit($offset);
ob_start("compress");
$files = scandir('../Application', 0);
$module_lang = array();
$list = array();
$now_module = array();
foreach ($files as $v) {
    if (($v != ".") and ($v != "..") ) {
        $file = '../Application/' . $v . '/Lang/' . $lang . '.php';
        if (is_file($file)) {
            if (ucfirst($module) == $v) {
                $now_module = include $file;
            } elseif ($v == "Common") {
                $common_lang = include $file;
            } else {
                $list[] = include $file;
            }
        }
    }
}

$list[] = $common_lang;
$list[] = $now_module;

foreach ($list as $val) {
    $module_lang = array_merge($module_lang, (array)$val);
}


    //需要重写js文件请清空缓存
    $fileData = "var LANG = new Array();\n";
    foreach ($module_lang as $key => $val) {
        $val = str_replace("'", "‘", $val); // 处理掉单引号
        $content[] = "LANG['{$key}']='{$val}';";
    }
    $fileData .= implode("\n", $content);

echo $fileData;


function compress($buffer)
{ //去除文件中的注释
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    return $buffer;
}


function set_cache_limit($second = 1)
{
    $second = intval($second);
    if ($second == 0) {
        return;
    }
    $etag = time() . "||" . base64_encode($_SERVER['REQUEST_URI']);

    if (!isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
        header("Etag:$etag", true, 200);
        return;
    } else {
        $id = $_SERVER['HTTP_IF_NONE_MATCH'];
    }

    list($time, $uri) = explode("||", $id);

    if ($time < (time() - $second)) { //过期了，发送新tag
        header("Etag:$etag", true, 200);
    } else { //未过期，发送旧tag
        header("Etag:$id", true, 304);
        exit(-1);
    }
}


//输出buffer中的内容，即压缩后的css文件
if (extension_loaded('zlib')) {
    ob_end_flush();
}