<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG['module'] = 'info';
$MCFG['name'] = '信息';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;

$RT = array();
$RT['file']['index'] = '信息管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加信息';
$RT['action']['index']['edit'] = '修改信息';
$RT['action']['index']['delete'] = '删除信息';
$RT['action']['index']['check'] = '审核信息';
$RT['action']['index']['expire'] = '过期信息';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '移动信息';
$RT['action']['index']['level'] = '信息级别';

$CT = true;
?>