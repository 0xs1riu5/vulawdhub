<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG['module'] = 'job';
$MCFG['name'] = '人才';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '招聘管理';
$RT['file']['resume'] = '简历管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加招聘';
$RT['action']['index']['edit'] = '修改招聘';
$RT['action']['index']['delete'] = '删除招聘';
$RT['action']['index']['check'] = '审核招聘';
$RT['action']['index']['expire'] = '过期招聘';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '移动招聘';
$RT['action']['index']['level'] = '信息级别';

$RT['action']['resume']['add'] = '添加简历';
$RT['action']['resume']['edit'] = '修改简历';
$RT['action']['resume']['delete'] = '删除简历';
$RT['action']['resume']['check'] = '审核简历';
$RT['action']['resume']['reject'] = '未通过简历';
$RT['action']['resume']['recycle'] = '回收站';
$RT['action']['resume']['move'] = '移动简历';
$RT['action']['resume']['level'] = '信息级别';
$CT = 1;
?>