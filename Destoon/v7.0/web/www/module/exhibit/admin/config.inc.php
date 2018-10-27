<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG['module'] = 'exhibit';
$MCFG['name'] = '展会';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '展会管理';
$RT['file']['sign'] = '报名管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加展会';
$RT['action']['index']['edit'] = '修改展会';
$RT['action']['index']['delete'] = '删除展会';
$RT['action']['index']['check'] = '审核展会';
$RT['action']['index']['expire'] = '过期展会';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '移动展会';
$RT['action']['index']['level'] = '信息级别';

$CT = true;
?>