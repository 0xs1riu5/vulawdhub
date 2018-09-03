<?php
/**
 * 模块菜单
 *
 * @version        $Id: inc_menu_module.php 1 10:32 2010年7月21日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../config.php");

/*
模块菜单一般在不要直接改此文件，直接保存在#@__sys_module表即可，格式为
<m:top name='问答模块管理' c='6,' display='block' rank=''>
<m:item name='问答栏目管理' link='ask_type.php' rank='' target='main' />
<m:item name='问答问题管理' link='ask_admin.php' rank='' target='main' />
<m:item name='问答答案管理' link='ask_answer.php' rank='' target='main' />
</m:top>
这个菜单可在生成模块时指定
*/

//载入模块菜单
$moduleset = '';
$dsql->SetQuery("SELECT * FROM `#@__sys_module` ORDER BY id DESC");
$dsql->Execute();
while($row = $dsql->GetObject()) 
{
    $moduleset .= $row->menustring."\r\n";
}

//载入插件菜单
$plusset = '';
$dsql->SetQuery("SELECT * FROM `#@__plus` WHERE isshow=1 ORDER BY aid ASC");
$dsql->Execute();
while($row = $dsql->GetObject()) {
    $row->menustring = str_replace('plus_友情链接', 'plus_友情链接模块', $row->menustring);
    $plusset .= $row->menustring."\r\n";
}

$adminMenu = '';
if($cuserLogin->getUserType() >= 10)
{
    $adminMenu = "<m:top name='模块管理' c='6,' display='block'>
    <m:item name='模块管理' link='module_main.php' rank='sys_module' target='main' />
    <m:item name='上传新模块' link='module_upload.php' rank='sys_module' target='main' />
    <m:item name='模块生成向导' link='module_make.php' rank='sys_module' target='main' />
    </m:top>";
}

$menusMoudle = "
-----------------------------------------------
$adminMenu
<m:top item='7' name='辅助插件' display='block'>
  <m:item name='插件管理器' link='plus_main.php' rank='10' target='main' />
  $plusset
</m:top>

$moduleset
-----------------------------------------------
";