<?php
defined('DT_ADMIN') or exit('Access Denied');
$MCFG = array();
$MCFG['module'] = 'destoon';
$MCFG['name'] = '核心';
$MCFG['author'] = 'Destoon.COM';
$MCFG['homepage'] = 'www.destoon.com';
$MCFG['copy'] = false;
$MCFG['uninstall'] = false;

$RT = array();
$RT['file']['database'] = '数据维护';
$RT['file']['template'] = '模板管理';
$RT['file']['skin'] = '风格管理';
$RT['file']['tag'] = '标签向导';
$RT['file']['cron'] = '计划任务';
$RT['file']['scan'] = '木马扫描';
$RT['file']['patch'] = '文件备份';
$RT['file']['md5'] = '文件校验';
$RT['file']['log'] = '后台日志';
$RT['file']['upload'] = '上传记录';
$RT['file']['404'] = '404日志';
$RT['file']['keyword'] = '搜索记录';
$RT['file']['question'] = '问题验证';
$RT['file']['banword'] = '词语过滤';
$RT['file']['repeat'] = '重名检测';
$RT['file']['banip'] = '禁止IP';
$RT['file']['fetch'] = '单页采编';
$RT['file']['doctor'] = '系统体检';

$CT = 0;
?>