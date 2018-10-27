<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG = array();
$MCFG['module'] = 'know';
$MCFG['name'] = '知道';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '知道管理';
$RT['file']['answer'] = '答案管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加知道';
$RT['action']['index']['edit'] = '修改知道';
$RT['action']['index']['delete'] = '删除知道';
$RT['action']['index']['check'] = '审核知道';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '知道移动';
$RT['action']['index']['level'] = '信息级别';

$CT = true;
?>