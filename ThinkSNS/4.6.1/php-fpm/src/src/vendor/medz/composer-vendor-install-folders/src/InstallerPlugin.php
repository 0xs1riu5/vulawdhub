<?php

namespace Medz\Component\Installer\CustomInstallPaths;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Medz\Component\Installer\CustomInstallPaths\Installer;

/**
 * 自定义安装路径插件入口
 *
 * @package default
 * @author Seven Du <lovevipdsw@outlook.com>
 **/
class InstallerPlugin implements PluginInterface
{
    /**
     * 插件入口方法
     * @param  Composer    $composer 安装器基类
     * @param  IOInterface $io       IO接口
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $composer
            ->getInstallationManager()
            ->addInstaller(new Installer($io, $composer))
        ;
    }
} // END class InstallerPlugin implements PluginInterface