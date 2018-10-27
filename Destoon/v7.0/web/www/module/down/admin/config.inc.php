<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG['module'] = 'down';
$MCFG['name'] = '下载';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '下载管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加下载';
$RT['action']['index']['edit'] = '修改下载';
$RT['action']['index']['delete'] = '删除下载';
$RT['action']['index']['check'] = '审核下载';
$RT['action']['index']['expire'] = '过期下载';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '移动下载';
$RT['action']['index']['level'] = '下载级别';

$CT = true;
?>