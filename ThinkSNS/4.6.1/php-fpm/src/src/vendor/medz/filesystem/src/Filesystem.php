<?php

namespace Medz\Component\Filesystem;

use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

/**
 * 静态方法重载Symfony\Component\Filesystem\Filesystem中的方法，使用单例。
 * 避免性能浪费
 *
 * @package Medz\Component\Filesystem\Filesystem
 * @author Seven Du <lovevipdsw@outlook.com>
 **/
class Filesystem
{
    /**
     * 储存文件系统对象
     *
     * @var Symfony\Component\Filesystem\Filesystem
     **/
    protected static $filesystem;

    /**
     * 工具静态方法重载文件系统方法
     *
     * @return miexd
     * @author Seven Du <lovevipdsw@outlook.com>
     **/
    public static function __callStatic($name, array $arguments)
    {
        if (!(self::$filesystem instanceof SymfonyFilesystem)) {
            self::$filesystem = new SymfonyFilesystem;
        }
        return call_user_func_array(array(self::$filesystem, $name), $arguments);
    }

    /**
     * 取得文件内容
     *
     * @return miexd
     * @author Seven Du <lovevipdsw@outlook.com>
     **/
    public static function getFileContent($filename)
    {
        return file_get_contents($filename);
    }

} // END class Filesystem
