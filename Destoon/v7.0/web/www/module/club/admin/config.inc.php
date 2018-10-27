<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG = array();
$MCFG['module'] = 'club';
$MCFG['name'] = '商圈';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = true;
$MCFG['uninstall'] = true;
$MCFG['moduleid'] = 0;

$RT = array();
$RT['file']['index'] = '帖子管理';
$RT['file']['reply'] = '回复管理';
$RT['file']['group'] = '商圈管理';
$RT['file']['fans'] = '粉丝管理';
$RT['file']['manage'] = '管理记录';
$RT['file']['html'] = '更新网页';

$RT['action']['index']['add'] = '发表帖子';
$RT['action']['index']['edit'] = '修改帖子';
$RT['action']['index']['delete'] = '删除帖子';
$RT['action']['index']['check'] = '审核帖子';
$RT['action']['index']['reject'] = '未通过';
$RT['action']['index']['recycle'] = '回收站';
$RT['action']['index']['move'] = '移动帖子';
$RT['action']['index']['level'] = '加入精华';
$RT['action']['index']['ontop'] = '置顶帖子';
$RT['action']['index']['style'] = '高亮帖子';

$RT['action']['reply']['edit'] = '修改回复';
$RT['action']['reply']['delete'] = '删除回复';
$RT['action']['reply']['check'] = '审核回复';
$RT['action']['reply']['reject'] = '未通过';
$RT['action']['reply']['recycle'] = '回收站';
$RT['action']['reply']['cancel'] = '取消审核';

$RT['action']['group']['add'] = '添加商圈';
$RT['action']['group']['edit'] = '修改商圈';
$RT['action']['group']['delete'] = '删除商圈';
$RT['action']['group']['check'] = '审核商圈';
$RT['action']['group']['reject'] = '未通过';
$RT['action']['group']['recycle'] = '回收站';
$RT['action']['group']['level'] = '商圈级别';

$RT['action']['fans']['delete'] = '删除粉丝';
$RT['action']['fans']['check'] = '审核粉丝';
$RT['action']['fans']['reject'] = '未通过';
$RT['action']['fans']['recycle'] = '回收站';
$RT['action']['fans']['cancel'] = '取消审核';

$CT = true;
?>