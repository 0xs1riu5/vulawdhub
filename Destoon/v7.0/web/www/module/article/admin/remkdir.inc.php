<?php
defined('DT_ADMIN') or exit('Access Denied');
install_file($moduleid, 'index', $dir, 1);
install_file($moduleid, 'list', $dir, 1);
install_file($moduleid, 'show', $dir, 1);
install_file($moduleid, 'search', $dir, 1);
?>