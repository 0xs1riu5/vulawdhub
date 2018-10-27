<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG['module'] = 'brand';
$MCFG['name'] = '品牌';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '品牌管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加品牌';
$RT['action']['index']['edit'] = '修改品牌';
$RT['action']['index']['delete'] = '删除品牌';
$RT['action']['index']['check'] = '审核品牌';
$RT['action']['index']['expire'] = '过期品牌';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '移动品牌';
$RT['action']['index']['level'] = '品牌级别';

$CT = true;
?>