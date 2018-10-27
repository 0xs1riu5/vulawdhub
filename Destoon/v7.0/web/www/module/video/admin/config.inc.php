<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG['module'] = 'video';
$MCFG['name'] = '视频';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '视频管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加视频';
$RT['action']['index']['edit'] = '修改视频';
$RT['action']['index']['delete'] = '删除视频';
$RT['action']['index']['check'] = '审核视频';
$RT['action']['index']['expire'] = '过期视频';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '移动视频';
$RT['action']['index']['level'] = '视频级别';

$CT = true;
?>