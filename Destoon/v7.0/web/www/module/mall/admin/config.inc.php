<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG['module'] = 'mall';
$MCFG['name'] = '商城';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '商品管理';
$RT['file']['order'] = '订单管理';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '添加商品';
$RT['action']['index']['edit'] = '修改商品';
$RT['action']['index']['relate'] = '关联商品';
$RT['action']['index']['delete'] = '删除商品';
$RT['action']['index']['check'] = '审核商品';
$RT['action']['index']['expire'] = '下架商品';
$RT['action']['index']['onsale'] = '上架商品';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '移动商品';
$RT['action']['index']['level'] = '信息级别';

$CT = 1;
?>