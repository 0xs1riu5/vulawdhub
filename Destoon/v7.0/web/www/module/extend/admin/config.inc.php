<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG['module'] = 'extend';
$MCFG['name'] = '扩展';
$MCFG['author'] = 'DESTOON';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = false;
$MCFG['uninstall'] = false;

$RT = array();
$RT['file']['spread'] = '排名推广';
$RT['file']['ad'] = '广告管理';
$RT['file']['announce'] = '公告管理';
$RT['file']['webpage'] = '单页管理';
$RT['file']['link'] = '友情链接';
$RT['file']['comment'] = '评论管理';
$RT['file']['guestbook'] = '留言管理';
$RT['file']['vote'] = '投票管理';
$RT['file']['html'] = '更新数据';

$RT['action']['spread']['add'] = '添加排名';
$RT['action']['spread']['edit'] = '修改排名';
$RT['action']['spread']['delete'] = '删除排名';
$RT['action']['spread']['check'] = '审核排名';
$RT['action']['spread']['price'] = '起价设置';
$RT['action']['spread']['html'] = '生成排名';

$RT['action']['ad']['add_place'] = '添加广告位';
$RT['action']['ad']['edit_place'] = '修改广告位';
$RT['action']['ad']['delete_place'] = '删除广告位';
$RT['action']['ad']['view'] = '预览广告位';
$RT['action']['ad']['add'] = '添加广告';
$RT['action']['ad']['edit'] = '修改广告';
$RT['action']['ad']['delete'] = '删除广告';
$RT['action']['ad']['add'] = '添加广告';
$RT['action']['ad']['list'] = '管理广告';
$RT['action']['ad']['html'] = '生成广告';

$RT['action']['announce']['add'] = '添加公告';
$RT['action']['announce']['edit'] = '修改公告';
$RT['action']['announce']['delete'] = '删除公告';
$RT['action']['announce']['expire'] = '过期公告';
$RT['action']['announce']['order'] = '更新排序';

$RT['action']['webpage']['add'] = '添加单页';
$RT['action']['webpage']['edit'] = '修改单页';
$RT['action']['webpage']['delete'] = '删除单页';
$RT['action']['webpage']['order'] = '更新排序';
$RT['action']['webpage']['html'] = '生成单页';

$RT['action']['link']['add'] = '添加链接';
$RT['action']['link']['edit'] = '修改链接';
$RT['action']['link']['delete'] = '删除链接';
$RT['action']['link']['check'] = '审核链接';
$RT['action']['link']['order'] = '更新排序';

$RT['action']['comment']['edit'] = '修改评论';
$RT['action']['comment']['delete'] = '删除评论';
$RT['action']['comment']['check'] = '审核评论';
$RT['action']['comment']['ban'] = '评论禁止';

$RT['action']['guestbook']['edit'] = '修改留言';
$RT['action']['guestbook']['delete'] = '删除留言';

$RT['action']['vote']['add'] = '添加投票';
$RT['action']['vote']['edit'] = '修改投票';
$RT['action']['vote']['delete'] = '删除投票';
$RT['action']['vote']['check'] = '审核投票';
$RT['action']['vote']['update'] = '更新地址';
$RT['action']['vote']['html'] = '生成投票';
$RT['action']['vote']['level'] = '设置级别';

$CT = false;
?>