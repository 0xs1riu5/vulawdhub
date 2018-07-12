Filesystem Component
====================

Filesystem provides basic utility to manipulate the file system:

```php
<?php

use Medz\Component\Filesystem\Filesystem;

Filesystem::copy($originFile, $targetFile, $override = false);

Filesystem::mkdir($dirs, $mode = 0777);

Filesystem::touch($files, $time = null, $atime = null);

Filesystem::remove($files);

Filesystem::exists($files);

Filesystem::chmod($files, $mode, $umask = 0000, $recursive = false);

Filesystem::chown($files, $user, $recursive = false);

Filesystem::chgrp($files, $group, $recursive = false);

Filesystem::rename($origin, $target);

Filesystem::symlink($originDir, $targetDir, $copyOnWindows = false);

Filesystem::makePathRelative($endPath, $startPath);

Filesystem::mirror($originDir, $targetDir, \Traversable $iterator = null, $options = array());

Filesystem::isAbsolutePath($file);
```
