<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG['module'] = 'sell';
$MCFG['name'] = '供应';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '供应管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加供应';
$RT['action']['index']['edit'] = '修改供应';
$RT['action']['index']['delete'] = '删除供应';
$RT['action']['index']['check'] = '审核供应';
$RT['action']['index']['expire'] = '过期供应';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '移动供应';
$RT['action']['index']['level'] = '信息级别';

$CT = 1;
?>