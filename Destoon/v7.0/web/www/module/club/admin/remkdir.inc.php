<?php
defined('DT_ADMIN') or exit('Access Denied');
file_copy(DT_ROOT.'/api/ajax.php', DT_ROOT.'/'.$dir.'/ajax.php');
install_file($moduleid, 'index', $dir, 1);
install_file($moduleid, 'list', $dir, 1);
install_file($moduleid, 'show', $dir, 1);
install_file($moduleid, 'search', $dir, 1);
install_file($moduleid, 'fans', $dir, 1);
install_file($moduleid, 'goto', $dir, 0);
?>