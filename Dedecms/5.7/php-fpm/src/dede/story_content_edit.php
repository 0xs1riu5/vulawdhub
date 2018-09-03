<?php
/**
 * @version        $Id: story_content_edit.php 1 9:02 2010年9月25日Z 蓝色随想 $
 * @package        DedeCMS.Module.Book
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

require_once(dirname(__FILE__). "/config.php");
require_once(DEDEROOT. "/book/include/story.func.php");
CheckPurview('story_Edit');
if(!isset($action)) $action = '';

if(empty($cid))
{
    ShowMsg("参数错误！", "-1");
    exit();
}

//读取所有栏目
$dsql->SetQuery("Select id,classname,pid,rank From #@__story_catalog order by rank asc");
$dsql->Execute();
$ranks = Array();
$btypes = Array();
$stypes = Array();
while($row = $dsql->GetArray()){
    if($row['pid']==0) $btypes[$row['id']] = $row['classname'];
    else $stypes[$row['pid']][$row['id']] = $row['classname'];
    $ranks[$row['id']] = $row['rank'];
}
$lastid = $row['id'];


$contents = $dsql->GetOne("SELECT * FROM #@__story_content WHERE id='$cid' ");

$bookinfos = $dsql->GetOne("SELECT catid,bcatid,bookname,booktype FROM #@__story_books WHERE bid='{$contents['bookid']}' ");
$catid = $bookinfos['catid'];
$bcatid = $bookinfos['bcatid'];
$bookname = $bookinfos['bookname'];
$booktype = $bookinfos['booktype'];
$bookid = $contents['bookid'];

$dsql->SetQuery("SELECT id,chapnum,chaptername FROM #@__story_chapter WHERE bookid='{$contents['bookid']}' ORDER BY chapnum DESC");
$dsql->Execute();
$chapters = Array();
$chapnums = Array();
while($row = $dsql->GetArray()){
    $chapters[$row['id']] = $row['chaptername'];
    $chapnums[$row['id']] = $row['chapnum'];
}

require_once DedeInclude('/templets/story_content_edit.htm');

//ClearAllLink();
