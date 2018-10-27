<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG['module'] = 'group';
$MCFG['name'] = '团购';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '团购管理';
$RT['file']['order'] = '订单管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加团购';
$RT['action']['index']['edit'] = '修改团购';
$RT['action']['index']['delete'] = '删除团购';
$RT['action']['index']['check'] = '审核团购';
$RT['action']['index']['expire'] = '过期团购';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '移动团购';
$RT['action']['index']['level'] = '信息级别';

$CT = 1;
?>