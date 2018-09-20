<?php

/**
 * 获取文档封面图片
 * @param int $cover_id
 * @param string $field
 * @return 完整的数据  或者  指定的$field字段值
 * @author huajie <banhuajie@163.com>
 */
function get_cover($cover_id, $field = null)
{

    if (empty($cover_id)) {
        return false;
    }
    $tag = 'picture_' . $cover_id;
    $picture = S($tag);
    if ($picture === false) {
        $picture = M('Picture')->where(array('status' => 1))->getById($cover_id);
        S($tag, $picture);
    }

    $picture['path'] = get_pic_src($picture['path']);
    return empty($field) ? $picture : $picture[$field];
}

function pic($cover_id)
{
    return get_cover($cover_id, 'path');
}

/** 不兼容sae 只兼容本地 --駿濤
 * @param        $filename
 * @param int $width
 * @param string $height
 * @param int $type
 * @param bool $replace
 * @return mixed|string
 * @auth 陈一枭
 */
function getThumbImage($filename, $width = 100, $height = 'auto', $type = 0, $replace = false)
{
    $UPLOAD_URL = '';
    $UPLOAD_PATH = '';
    $filename = str_ireplace($UPLOAD_URL, '', $filename); //将URL转化为本地地址
    $info = pathinfo($filename);
    $oldFile = $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . '.' . $info['extension'];
    $thumbFile = $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . '_' . $width . '_' . $height . '.' . $info['extension'];

    $oldFile = str_replace('\\', '/', $oldFile);
    $thumbFile = str_replace('\\', '/', $thumbFile);

    $filename = ltrim($filename, '/');
    $oldFile = ltrim($oldFile, '/');
    $thumbFile = ltrim($thumbFile, '/');

    if (!file_exists($UPLOAD_PATH . $oldFile)) {
        //原图不存在直接返回
        @unlink($UPLOAD_PATH . $thumbFile);
        $info['src'] = $oldFile;
        $info['width'] = intval($width);
        $info['height'] = intval($height);
        return $info;
    } elseif (file_exists($UPLOAD_PATH . $thumbFile) && !$replace) {
        //缩图已存在并且  replace替换为false
        $imageinfo = getimagesize($UPLOAD_PATH . $thumbFile);
        $info['src'] = $thumbFile;
        $info['width'] = intval($imageinfo[0]);
        $info['height'] = intval($imageinfo[1]);
        return $info;
    } else {
        //执行缩图操作
        $oldimageinfo = getimagesize($UPLOAD_PATH . $oldFile);
        $old_image_width = intval($oldimageinfo[0]);
        $old_image_height = intval($oldimageinfo[1]);
        if ($old_image_width <= $width && $old_image_height <= $height) {
            @unlink($UPLOAD_PATH . $thumbFile);
            @copy($UPLOAD_PATH . $oldFile, $UPLOAD_PATH . $thumbFile);
            $info['src'] = $thumbFile;
            $info['width'] = $old_image_width;
            $info['height'] = $old_image_height;
            return $info;
        } else {
            if ($height == "auto") $height = $old_image_height * $width / $old_image_width;
            if ($width == "auto") $width = $old_image_width * $width / $old_image_height;
            if (intval($height) == 0 || intval($width) == 0) {
                return 0;
            }
            require_once('ThinkPHP/Library/Vendor/phpthumb/PhpThumbFactory.class.php');
            $thumb = PhpThumbFactory::create($UPLOAD_PATH . $filename);
            if ($type == 0) {
                $thumb->adaptiveResize($width, $height);
            } else {
                $thumb->resize($width, $height);
            }
            $res = $thumb->save($UPLOAD_PATH . $thumbFile);
            $info['src'] = $UPLOAD_PATH . $thumbFile;
            $info['width'] = $old_image_width;
            $info['height'] = $old_image_height;
            return $info;

        }
    }
}

/**获取网站的根Url
 * @return string
 * @auth 陈一枭
 */
function getRootUrl()
{
    if (__ROOT__ != '') {
        return __ROOT__.'/';
    }
    if (C('URL_MODEL') == 2)
        return __ROOT__ . '/';
    return __ROOT__;
}

/**通过ID获取到图片的缩略图
 * @param        $cover_id 图片的ID
 * @param int $width 需要取得的宽
 * @param string $height 需要取得的高
 * @param int $type 图片的类型，qiniu 七牛，local 本地, sae SAE
 * @param bool $replace 是否强制替换
 * @return string
 * @auth 陈一枭
 */
function getThumbImageById($cover_id, $width = 100, $height = 'auto', $type = 0, $replace = false)
{
    $picture = S('picture_' . $cover_id);
    if (empty($picture)) {
        $picture = M('Picture')->where(array('status' => 1))->getById($cover_id);
        S('picture_' . $cover_id, $picture);
    }
    if (empty($picture)) {
        return get_pic_src('Public/images/nopic.png');
    }
    if ($picture['type'] == 'local') {
        $attach = getThumbImage($picture['path'], $width, $height, $type, $replace);
        return get_pic_src($attach['src']);
    } else {
        $new_img = $picture['path'];
        $name = get_addon_class($picture['type']);
        if (class_exists($name)) {
            $class = new $name();
            if (method_exists($class, 'thumb')) {
                $new_img = $class->thumb($picture['path'], $width, $height, $type, $replace);
            }
        }
        return get_pic_src($new_img);
    }

}

/**简写函数，等同于getThumbImageById（）
 * @param $cover_id 图片id
 * @param int $width 宽度
 * @param string $height 高度
 * @param int $type 裁剪类型，0居中裁剪
 * @param bool $replace 裁剪
 * @return string
 */
function thumb($cover_id, $width = 100, $height = 'auto', $type = 0, $replace = false)
{
    return getThumbImageById($cover_id, $width, $height, $type, $replace);
}


/**获取第一张图
 * @param $str_img
 * @return mixed
 */
function get_pic($str_img)
{
    preg_match_all("/<img.*\>/isU", $str_img, $ereg); //正则表达式把图片的整个都获取出来了
    $img = $ereg[0][0]; //图片
    $p = "#src=('|\")(.*)('|\")#isU"; //正则表达式
    preg_match_all($p, $img, $img1);
    $img_path = $img1[2][0]; //获取第一张图片路径
    return $img_path;
}


/**
 * get_pic_src   渲染图片链接
 * @param $path
 * @return mixed
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function get_pic_src($path)
{
    //不存在http://
    $not_http_remote = (strpos($path, 'http://') === false);
    //不存在https://
    $not_https_remote = (strpos($path, 'https://') === false);
    if ($not_http_remote && $not_https_remote) {
        //本地url
        return str_replace('//', '/', getRootUrl() . $path); //防止双斜杠的出现
    } else {
        //远端url
        return $path;
    }
}



function get_url_with_domain($path)
{
    //不存在http://
    $not_http_remote = (strpos($path, 'http://') === false);
    //不存在https://
    $not_https_remote = (strpos($path, 'https://') === false);
    if ($not_http_remote && $not_https_remote) {
        //本地url
        $root = trim(getRootUrl(), '/');
        $path = trim($path, '/');
        if (strpos($path, $root) === 0) {
            $root = '';
        }
        $path = 'http://' . str_replace(array('////', '///', '//'), '/', $_SERVER['HTTP_HOST'] . '/' . $root . '/' . $path);
        return $path; //防止双斜杠的出现
    } else {
        //远端url
        return $path;
    }
}