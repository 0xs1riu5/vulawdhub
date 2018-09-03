<?php
/**
 * @version        $Id: story_edit_action.php 1 9:02 2010年9月25日Z 蓝色随想 $
 * @package        DedeCMS.Module.Book
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

require_once(dirname(__FILE__). "/config.php");
CheckPurview('story_New');
require_once(DEDEINC. "/image.func.php");
require_once(DEDEINC. "/oxwindow.class.php");
require_once(DEDEADMIN. "/inc/inc_archives_functions.php");
if(!isset($iscommend)) $iscommend = 0;

if($catid==0)
{
    ShowMsg("请指定图书所属栏目！","-1");
    exit();
}

//获得父栏目
$nrow = $dsql->GetOne("SELECT * FROM #@__story_catalog WHERE id='$catid' ");
$bcatid = $nrow['pid'];
$booktype = $nrow['booktype'];
$pubdate = GetMkTime($pubdate);
$lastpost=time();
$bookname = cn_substr($bookname,50);
if($keywords!="") $keywords = trim(cn_substr($keywords, 60));


//处理上传的缩略图
if($litpic !="") $litpic = GetDDImage('litpic', $litpic, 0);

if($litpicname !="" && $litpic == "") $litpic = GetDDImage('litpic', $litpicname, 0);

$adminID = $cuserLogin->getUserID();

//自动摘要
if($description=="" && $cfg_auot_description>0)
{
    $description = stripslashes(cn_substr(html2text($body), $cfg_auot_description));
    $description = addslashes($description);
}
$upQuery = "
Update `#@__story_books`
set catid='$catid',
bcatid='$bcatid',
iscommend='$iscommend',
click='$click',
freenum='$freenum',
arcrank='$arcrank',
bookname='$bookname',
author='$author',
litpic='$litpic',
pubdate='$pubdate',
lastpost='$lastpost',
description='$description',
body='$body',
keywords='$keywords',
status='$status',
ischeck='$ischeck'
where bid='$bookid' ";

if(!$dsql->ExecuteNoneQuery($upQuery))
{
    ShowMsg("更新数据库时出错，请检查！".$dsql->GetError(),"-1");
    $dsql->Close();
    exit();
}

//生成HTML
require_once(DEDEROOT. '/book/include/story.view.class.php');
$bv = new BookView($bookid, 'book');
$artUrl = $bv->MakeHtml();
$bv->Close();

//返回成功信息
$msg = "
　　请选择你的后续操作：
<a href='story_edit.php?bookid={$bookid}'><u>继续修改</u></a>
&nbsp;&nbsp;
<a href='story_add.php?catid={$catid}'><u>发布新图书</u></a>
&nbsp;&nbsp;
<a href='$artUrl' target='_blank'><u>预览图书</u></a>
&nbsp;&nbsp;
<a href='story_add_content.php?bookid={$bookid}'><u>增加图书内容</u></a>
&nbsp;&nbsp;
<a href='story_books.php'><u>管理图书</u></a>
";
$wintitle = "成功修改图书！";
$wecome_info = "连载管理::修改图书";
$win = new OxWindow();
$win->AddTitle("成功修改一本图书：");
$win->AddMsgItem($msg);
$winform = $win->GetWindow("hand", "&nbsp;", false);
$win->Display();
