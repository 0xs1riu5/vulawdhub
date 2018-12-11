<?php

/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年8月3日
 *  数据处理函数库
 */
use core\basic\Config;

// 检测目录是否存在
function check_dir($path, $create = false)
{
    if (is_dir($path)) {
        return true;
    } elseif ($create) {
        return create_dir($path);
    }
}

// 创建目录
function create_dir($path)
{
    if (! file_exists($path)) {
        if (mkdir($path, 0777, true)) {
            return true;
        }
    }
    return false;
}

// 检查文件是否存在
function check_file($path, $create = false, $content = null)
{
    if (file_exists($path)) {
        return true;
    } elseif ($create) {
        return create_file($path, $content);
    }
}

// 创建文件
function create_file($path, $content = null, $over = false)
{
    if (file_exists($path) && ! $over) {
        return false;
    } elseif (file_exists($path)) {
        @unlink($path);
    }
    check_dir(dirname($path), true);
    $handle = fopen($path, 'w') or error('创建文件失败，请检查目录权限！');
    fwrite($handle, $content);
    return fclose($handle);
}

// 目录文件夹列表
function dir_list($path)
{
    $list = array();
    if (! is_dir($path) || ! $filename = scandir($path)) {
        return $list;
    }
    $files = count($filename);
    for ($i = 0; $i < $files; $i ++) {
        $dir = $path . '/' . $filename[$i];
        if (is_dir($dir) && $filename[$i] != '.' && $filename[$i] != '..') {
            $list[] = $filename[$i];
        }
    }
    return $list;
}

// 目录文件列表
function file_list($path)
{
    $list = array();
    if (! is_dir($path) || ! $filename = scandir($path)) {
        return $list;
    }
    $files = count($filename);
    for ($i = 0; $i < $files; $i ++) {
        $dir = $path . '/' . $filename[$i];
        if (is_file($dir)) {
            $list[] = $filename[$i];
        }
    }
    return $list;
}

// 目录下文件及文件夹列表
function path_list($path)
{
    $list = array();
    if (! is_dir($path) || ! $filename = scandir($path)) {
        return $list;
    }
    $files = count($filename);
    for ($i = 0; $i < $files; $i ++) {
        $dir = $path . '/' . $filename[$i];
        if (is_file($dir) || (is_dir($dir) && $filename[$i] != '.' && $filename[$i] != '..')) {
            $list[] = $filename[$i];
        }
    }
    return $list;
}

/**
 * 删除目录及目录下所有文件或删除指定文件
 *
 * @param str $path
 *            待删除目录路径
 * @param int $delDir
 *            是否删除目录，true删除目录，false则只删除文件保留目录
 * @return bool 返回删除状态
 */
function path_delete($path, $delDir = false)
{
    $result = true; // 对于空目录直接返回true状态
    if (! file_exists($path)) {
        return $result;
    }
    if (is_dir($path)) {
        if (! ! $dirs = scandir($path)) {
            foreach ($dirs as $value) {
                if ($value != "." && $value != "..") {
                    $dir = $path . '/' . $value;
                    $result = is_dir($dir) ? path_delete($dir, $delDir) : unlink($dir);
                }
            }
            if ($result && $delDir) {
                return rmdir($path);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    } else {
        return unlink($path);
    }
}

// 判断文件是否是图片
function is_image($path)
{
    $types = '.gif|.jpeg|.png|.bmp'; // 定义检查的图片类型
    if (file_exists($path)) {
        $info = getimagesize($path);
        $ext = image_type_to_extension($info['2']);
        return stripos($types, $ext);
    } else {
        return false;
    }
}

/**
 * 文件上传
 *
 * @param string $input_name表单名称            
 * @param string $file_ext允许的扩展名            
 * @param number $max_width最大宽度            
 * @param number $max_height最大高度            
 * @return string 返回成功上传文件的路径数组
 */
function upload($input_name, $file_ext = null, $max_width = null, $max_height = null)
{
    // 未选择文件返回空
    if (! isset($_FILES[$input_name])) {
        return array();
    } else {
        $files = $_FILES[$input_name];
    }
    
    // 定义允许上传的扩展
    if (! $file_ext) {
        $array_ext_allow = Config::get('upload.format', true);
    } else {
        $array_ext_allow = explode(',', $file_ext);
    }
    
    $array_save_file = array();
    if (is_array($files['tmp_name'])) { // 多文件情况
        $file_count = count($files['tmp_name']);
        for ($i = 0; $i < $file_count; $i ++) {
            if (! $files['error'][$i]) {
                $array_save_file[] = handle_upload($files['name'][$i], $files['tmp_name'][$i], $array_ext_allow, $max_width, $max_height);
            }
        }
    } else { // 单文件情况
        if (! $files['error']) {
            $array_save_file[] = handle_upload($files['name'], $files['tmp_name'], $array_ext_allow, $max_width, $max_height);
        }
    }
    return $array_save_file;
}

// 处理并移动上传文件
function handle_upload($file, $temp, $array_ext_allow, $max_width, $max_height)
{
    // 定义主存储路径
    $save_path = DOC_PATH . STATIC_DIR . '/upload';
    
    $file = explode('.', $file); // 分离文件名及扩展
    $file_ext = strtolower(end($file)); // 获取扩展
    
    if (! in_array($file_ext, $array_ext_allow)) {
        die($file_ext . '格式的文件不允许上传，请重新选择！');
    }
    if (in_array($file_ext, array(
        'png',
        'jpg',
        'gif',
        'bmp'
    ))) {
        $file_type = 'image';
    } elseif (in_array($file_ext, array(
        'ppt',
        'pptx',
        'xls',
        'xlsx',
        'doc',
        'docx',
        'pdf',
        'txt'
    ))) {
        $file_type = 'file';
    } else {
        $file_type = 'other';
    }
    // 检查文件存储路径
    check_dir($save_path . '/' . $file_type . '/' . date('Ymd'), true);
    $file_path = $save_path . '/' . $file_type . '/' . date('Ymd') . '/' . time() . mt_rand(100000, 999999) . '.' . $file_ext;
    move_uploaded_file($temp, $file_path); // 从缓存中转存
    $save_file = str_replace(ROOT_PATH, '', $file_path);
    
    // 如果是图片进行等比例缩放
    if (is_image($file_path)) {
        resize_img($file_path, $file_path, $max_width, $max_height);
    }
    return $save_file;
}

/**
 * *
 * 等比缩放图片
 *
 * @param string $src_image源图片路径            
 * @param string $out_image输出图像路径            
 * @param number $max_width最大宽            
 * @param number $max_height最大高            
 * @param number $img_quality图片质量            
 * @return boolean 返回是否成功
 */
function resize_img($src_image, $out_image = null, $max_width = null, $max_height = null, $img_quality = 90)
{
    if (! is_file($src_image))
        return;
    
    // 输出地址
    if (! $out_image)
        $out_image = $src_image;
    
    // 读取配置文件设置
    if (! $max_width)
        $max_width = Config::get('upload.max_width');
    if (! $max_height)
        $max_height = Config::get('upload.max_height');
    
    // 获取图片属性
    list ($width, $height, $type, $attr) = getimagesize($src_image);
    
    if ($width < $max_width && $height < $max_height) {
        if ($src_image != $out_image) {
            copy($src_image, $out_image);
        }
        return true;
    }
    switch ($type) {
        case 1:
            $img = imagecreatefromgif($src_image);
            break;
        case 2:
            $img = imagecreatefromjpeg($src_image);
            break;
        case 3:
            $img = imagecreatefrompng($src_image);
            break;
    }
    
    // 求缩放比例
    if ($max_width && $max_height) {
        $scale = min($max_width / $width, $max_height / $height);
    } elseif ($max_width) {
        $scale = $max_width / $width;
    } elseif ($max_height) {
        $scale = $max_height / $height;
    }
    
    // 检查输出目录
    check_dir(dirname($out_image), true);
    
    if ($scale < 1) {
        $new_width = floor($scale * $width);
        $new_height = floor($scale * $height);
        $new_img = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        switch ($type) {
            case 1:
                imagegif($new_img, $out_image, $img_quality);
                break;
            case 2:
                imagejpeg($new_img, $out_image, $img_quality);
                break;
            case 3:
                imagepng($new_img, $out_image, $img_quality / 10); // $quality参数取值范围0-99 在php 5.1.2之后变更为0-9
                break;
            default:
                imagejpeg($new_img, $out_image, $img_quality);
        }
        imagedestroy($new_img);
        return true;
    }
    imagedestroy($img);
    return false;
}


