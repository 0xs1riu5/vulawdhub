<?php

include dirname(__FILE__) . '/../vendor/autoload.php';

use Medz\Component\Filesystem\Filesystem;

Filesystem::mirror('./1', './2');
