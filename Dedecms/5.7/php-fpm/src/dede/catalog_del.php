<?php
/**
 * 删除栏目
 *
 * @version        $Id: catalog_del.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/config.php');

//检查权限许可
CheckPurview('t_Del,t_AccDel');
require_once(DEDEINC.'/typeunit.class.admin.php');
require_once(DEDEINC.'/oxwindow.class.php');
$id = trim(preg_replace("#[^0-9]#", '', $id));

//检查栏目操作许可
CheckCatalog($id,"你无权删除本栏目！");
if(empty($dopost)) $dopost='';
if($dopost=='ok')
{
    $ut = new TypeUnit();
    $ut->DelType($id,$delfile);
    UpDateCatCache();
    ShowMsg("成功删除一个栏目！","catalog_main.php");
    exit();
}
$dsql->SetQuery("SELECT typename,typedir FROM #@__arctype WHERE id=".$id);
$row = $dsql->GetOne();
$wintitle = "删除栏目确认";
$wecome_info = "<a href='catalog_main.php'>栏目管理</a> &gt;&gt; 删除栏目确认";
$win = new OxWindow();
$win->Init('catalog_del.php','js/blank.js','POST');
$win->AddHidden('id',$id);
$win->AddHidden('dopost','ok');
$win->AddTitle("你要确实要删除栏目： [{$row['typename']}] 吗？");
$win->AddItem('栏目的文件保存目录：',$row['typedir']);
$win->AddItem('是否删除文件：',"<input type='radio' name='delfile' class='np' value='no' checked='1' />否 &nbsp;<input type='radio' name='delfile' class='np' value='yes' />是");
$winform = $win->GetWindow('ok');
$win->Display();