<?php
/**
 * @version        $Id: story_books.php 1 9:02 2010年9月25日Z 蓝色随想 $
 * @package        DedeCMS.Module.Book
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
require_once(dirname(__FILE__). "/config.php");
require_once DEDEINC. '/datalistcp.class.php';
setcookie("ENV_GOBACK_URL", $dedeNowurl, time()+3600,"/");
CheckPurview('story_list');
if(!isset($action)) $action = '';

if(!isset($catid)) $catid = 0;

if(!isset($keyword)) $keyword = "";

if(!isset($orderby)) $orderby = 0;

if(!isset($ischeck)) $ischeck = 0;

if(!isset($cid)) $cid = 0;

if($action == 'checked')
{
    $id = intval($id);
    $query="UPDATE #@__story_books SET ischeck=1 WHERE bid='$id'";
    if($dsql->ExecuteNoneQuery($query))
    {
        showmsg('审核成功','story_books.php');
        exit();
    }
    else
    {
        showmsg('审核失败','story_books.php');
        exit();
    }
}

//读取所有栏目列表
$dsql->SetQuery("SELECT id,classname,pid,rank FROM #@__story_catalog ORDER BY rank ASC");
$dsql->Execute();
$ranks = Array();
$btypes = Array();
$stypes = Array();
while($row = $dsql->GetArray())
{
    if($row['pid']==0)
    {
        $btypes[$row['id']] = $row['classname'];
    }
    else
    {
        $stypes[$row['pid']][$row['id']] = $row['classname'];
    }
    $ranks[$row['id']] = $row['rank'];
}
$addquery = "";
if($ischeck == 1)
{
    $addquery .= " and ischeck=0 ";
}
$orderby = " ORDER BY b.bid DESC ";
if($catid!=0)
{
    $addquery .= " And (b.bcatid='$catid' OR b.catid='$catid') ";
}
if($keyword!="")
{
    $addquery .= " And (b.bookname LIKE '%$keyword%' OR b.author LIKE '%$keyword%') ";
}
$query = "
   SELECT b.bid,b.catid,b.bookname,b.booktype,b.litpic,b.ischeck,b.postnum,b.senddate,c.id AS cid,c.classname FROM #@__story_books b
   LEFT JOIN #@__story_catalog c ON c.id = b.catid WHERE b.bid>0 $addquery $orderby
";
$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetParameter("keyword", $keyword);
$dlist->SetParameter("catid", $cid);
$dlist->SetParameter("orderby", $orderby);
$dlist->SetTemplate(DEDEADMIN. '/templets/story_books.htm');
$dlist->SetSource($query);
$dlist->Display();
