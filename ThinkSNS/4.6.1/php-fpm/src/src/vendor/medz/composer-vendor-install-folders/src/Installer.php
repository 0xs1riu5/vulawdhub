<?php

namespace Medz\Component\Installer\CustomInstallPaths;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

/**
 * 自定义安装器
 *
 * @package default
 * @author Seven Du <lovevipdsw@outlook.com>
 **/
class Installer extends LibraryInstaller
{
    public function supports($packageType)
    {
        return $packageType == 'custom-folders';
    }

    public function getInstallPath(PackageInterface $package)
    {
        $extra = $package->getExtra();
        if (!$extra['custom-path']) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The "%s" application is not set "custom-path" field.' . PHP_EOL
                    . 'Using the following config within your package composer.json will allow this:' . PHP_EOL
                    . '{' . PHP_EOL
                    . '    "name": "vendor/name",' . PHP_EOL
                    . '    "type": "custom-folders",' . PHP_EOL
                    . '    "extra": {' . PHP_EOL
                    . '        "custom-path": "src/demo/test"' . PHP_EOL
                    . '    }' . PHP_EOL
                    . '}' . PHP_EOL
                ),
                $package->getName()
            );
        }

        return $extra['custom-path'];
    }
} // END class Installer extends LibraryInstaller