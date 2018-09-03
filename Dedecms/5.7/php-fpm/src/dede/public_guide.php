<?php
/**
 * 设置某频道为默认发布
 *
 * @version        $Id: public_guide.php 1 15:46 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/oxwindow.class.php");
if(empty($action)) $action = '';

/*--------------------
function __SetDefault();
----------------------*/
if($action=='setdefault')
{
    CheckPurview('sys_Edit');
    $dsql->ExecuteNoneQuery("UPDATE `#@__channeltype` SET isdefault=0 WHERE id<>'$cid'");
    if($cid != 0)
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__channeltype` SET isdefault=1 WHERE id='$cid'");
    }
    $win = new OxWindow();
    $win->Init();
    $win->mainTitle = "内容发布向导";
    $wecome_info = "<a href='public_guide.php?action=edit'>内容发布向导</a>";
    $win->AddTitle("<a href='public_guide.php?action=edit'>内容发布向导</a> &gt;&gt; 设置默认发布表单");
    if($cid==0)
    {
        $msg = "
         成功取消默认发布表单！
           <hr style='width:90%' size='1' />
           你目前想要进行的操作： <a href='public_guide.php?action=edit'>返回发布向导页</a>
      ";
    }
    else
    {
        $msg = "
        成功保存默认发布表单，以后点击“内容发布”面板将直接跳转到你选择的内容发布页！
        <hr style='width:90%' size='1' />
           你目前想要进行的操作： <a href='public_guide.php'>转到默认发布表单</a> &nbsp; <a href='public_guide.php?action=edit'>返回发布向导页</a>
      ";
    }
    $win->AddMsgItem("<div style='padding-left:20px;line-height:150%'>$msg</div>");
    $winform = $win->GetWindow("hand");
    $win->Display();
    exit();
}

//以下为正常浏览的内容
/*--------------------
function __PageShow();
----------------------*/
$row = $dsql->GetOne("SELECT id,addcon FROM `#@__channeltype` WHERE isdefault='1' ");

//已经设置了默认发布表单
if(is_array($row) && $action!='edit')
{
    $addcon = $row['addcon'];
    if($addcon=='')
    {
        $addcon='archives_add.php';
    }
    $channelid = $row['id'];
    $cid = 0;
    require_once(DEDEADMIN.'/'.$addcon);
    exit();
}

//没有设置默认发布表单
else
{
    $dsql->SetQuery("SELECT id,typename,mancon,isdefault,addtable FROM `#@__channeltype` WHERE id<>-1 And isshow=1 ");
    $dsql->Execute();
}
include DedeInclude('templets/public_guide.htm');

//获取频道栏目数
function GetCatalogs(&$dsql,$cid)
{
    $row = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__arctype` WHERE channeltype='$cid' ");
    return (!is_array($row) ? '0' : $row['dd']);
}