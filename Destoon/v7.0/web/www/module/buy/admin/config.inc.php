<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG['module'] = 'buy';
$MCFG['name'] = '求购';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '求购管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加求购';
$RT['action']['index']['edit'] = '修改求购';
$RT['action']['index']['delete'] = '删除求购';
$RT['action']['index']['check'] = '审核求购';
$RT['action']['index']['expire'] = '过期求购';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '移动求购';
$RT['action']['index']['level'] = '信息级别';

$CT = 1;
?>