<?php
/**
 * @version        $Id: module-install.php 1 13:41 2010年7月26日Z tianya $
 * @package        DedeCMS.Install
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/../include/common.inc.php');
@set_time_limit(0);

$verMsg = ' V5.7 GBK';
$errmsg = '';
$insLockfile = dirname(__FILE__).'/install_lock.txt';
$moduleCacheFile = dirname(__FILE__).'/modules.tmp.inc';
$moduleDir = DEDEROOT.'/data/module';
$AdminBaseDir = DEDEROOT.'/dede/';

if(file_exists($insLockfile))
{
    exit(" 程序已运行安装，如果你确定要重新安装，请先从FTP中删除 install/install_lock.txt！");
}

require_once(DEDEINC.'/dedemodule.class.php');
require_once(dirname(__FILE__).'/modulescache.php');
require_once(dirname(__FILE__).'/install.inc.php');

if(empty($step)) $step = 0;

//完成安装
if($step==9999)
{
    $fp = fopen($insLockfile,'w');
    fwrite($fp,'ok');
    fclose($fp);
    ReWriteConfigAuto();
    UpDateCatCache();
    @unlink('./modules.tmp.inc');
    include('./templates/step-5.html');
    exit();
}

//用户选择的模块列表缓存文件
if(!file_exists($moduleCacheFile))
{
    $msg =  "<font color='red'>由于无法找到模块缓存文件，安装可选模块失败，请登录后在模块管理处安装。</font><br /><br />";
    $msg .=  "<a href='module-install.php?step=9999' target='_top'>点击此完成安装 &gt;&gt;</a>";
    ShowMsg($msg,'javascript:;');
    exit();
}

//模块文件夹权限
if(!TestWrite($moduleDir))
{
    $msg =  "<font color='red'>目录 {$moduleDir} 不支持写入，不能安装模块，请登录后在模块管理处安装。</font><br /><br />";
    $msg .=  "<a href='module-install.php?step=9999' target='_top'>点击此完成安装 &gt;&gt;</a>";
    ShowMsg($msg,"javascript:;");
    exit();
}

include($moduleCacheFile);
$modules = split(',',$selModule);
$totalMod = count($modules);
if($step >= $totalMod)
{
    $msg =  "<font color='red'>完成所有模块的安装！</font><br /><br />";
    $msg .=  "<a href='module-install.php?step=9999' target='_top'>点击此进行下一步操作 &gt;&gt;</a>";
    ShowMsg($msg,'javascript:;');
    exit();
}
$moduleHash = $modules[$step];
$moduleFile = $allmodules[$moduleHash];

$dm = new DedeModule($moduleDir);

$minfos = $dm->GetModuleInfo($moduleHash);
extract($minfos, EXTR_SKIP);
$menustring = addslashes($dm->GetSystemFile($moduleHash,'menustring'));

$query = "INSERT INTO `#@__sys_module`(`hashcode` , `modname` , `indexname` , `indexurl` , `ismember` , `menustring` )
                                    VALUES ('$moduleHash' , '$name' , '$indexname' , '$indexurl' , '$ismember' , '$menustring' ) ";

$rs = $dsql->ExecuteNoneQuery("Delete From `#@__sys_module` where hashcode like '$moduleHash' ");
$rs = $dsql->ExecuteNoneQuery($query);

if(!$rs)
{
    $msg =  "<font color='red'>保存数据库信息失败，无法完成你选择的模块安装！</font><br /><br />";
    $msg .=  "<a href='module-install.php?step=9999' target='_top'>点击此进行下一步操作 &gt;&gt;</a>";
    exit();
}

//写文件
$dm->WriteFiles($moduleHash,1);
$dm->WriteSystemFile($moduleHash,'readme');

$setupsql = $dm->GetSystemFile($moduleHash,'setupsql40');

//运行SQL
$mysql_version = $dsql->GetVersion(TRUE);
$setupsql = preg_replace("#ENGINE=MyISAM#i", 'TYPE=MyISAM', $setupsql);
$sql41tmp = 'ENGINE=MyISAM DEFAULT CHARSET='.$cfg_db_language;

if($mysql_version >= 4.1) {
    $setupsql = preg_replace("#TYPE=MyISAM#i", $sql41tmp, $setupsql);
}        

//_ROOTURL_
if($cfg_cmspath=='/') $cfg_cmspath = '';

$rooturl = $cfg_basehost.$cfg_cmspath;
$setupsql = preg_replace("#_ROOTURL_#i", $rooturl, $setupsql);
$setupsql = preg_replace("#[\r\n]{1,}#", "\n", $setupsql);    
$sqls = preg_split("#;[ \t]{0,}\n#", $setupsql);

foreach($sqls as $sql) {
    if(trim($sql)!='') $dsql->executenonequery($sql);
}

$dm->Clear();

$step = $step + 1;
ShowMsg("模块 {$name} 安装完成，准备下一模块安装...", "module-install.php?step={$step}");
exit();