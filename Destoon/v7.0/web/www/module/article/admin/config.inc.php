<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG = array();
$MCFG['module'] = 'article';
$MCFG['name'] = '文章';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '文章管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加文章';
$RT['action']['index']['edit'] = '修改文章';
$RT['action']['index']['delete'] = '删除文章';
$RT['action']['index']['check'] = '审核文章';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '文章移动';
$RT['action']['index']['level'] = '信息级别';

$CT = true;
?>