<?php

namespace Medz\Component;

/**
 * 原生emoji表情文字转换工具
 * 主要功能是将emoji字符串[😂]格式化为"[emoji:8J+Ygg==]"这样的普通字符串，主要方便于低版本的数据库储存，
 * 缺点就是，原本四位长度的emoji字符，将占用16位左右储存。
 *
 * @package Medz\Component\EmojiFormat
 * @author Seven Du <lovevipdsw@outlook.com>
 **/
class EmojiFormat
{
    protected static $left = '[emoji:';

    protected static $right = ']';

    protected static $pattern = '/\[emoji\:(.*?)\]/is';

    /**
     * 将字符串格式化为emoji代码
     *
     * @param string|array $data 需要被格式化的数据
     * @return string|array 格式化后的数据
     * @author Seven Du <lovevipdsw@outlook.com>
     * @datetime 2016-04-16T02:14:44+0800
     * @homepage http://medz.cn
     */
    public static function en($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = static::en($value);
            }

        } elseif (is_string($data)) {

            $left = static::$left;
            $right = static::$right;

            $data = preg_replace_callback('/[\xf0-\xf7].{3}/', function ($data) use ($left, $right)
            {
                $data = array_pop($data);
                $data = base64_encode($data);
                $data = sprintf('%s%s%s', $left, $data, $right);
                return $data;
            }, $data);
        }

        return $data;
    }

    /**
     * 反格式化数据
     *
     * @param string|array $data 需要被反格式化的数据
     * @return string|array 反格式化后为emoji原型字符串
     * @author Seven Du <lovevipdsw@outlook.com>
     * @datetime 2016-04-16T02:17:10+0800
     * @homepage http://medz.cn
     */
    public static function de($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = static::de($value);
            }

        } elseif (is_string($data)) {
            $data = preg_replace_callback(static::$pattern, function ($data)
            {
                $data = $data[1];
                $data = base64_decode($data);
                return $data;
            }, $data);
        }

        return $data;
    }

} // END class EmojiFormat
