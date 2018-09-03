<?php
/**
 * @version        $Id: story_do.php 1 9:02 2010年9月25日Z 蓝色随想 $
 * @package        DedeCMS.Module.Book
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

require_once(dirname(__FILE__). "/config.php");
CheckPurview('story_Del');
require_once(DEDEINC. "/oxwindow.class.php");
if(empty($action))
{
    ShowMsg("你没指定任何参数！","-1");
    exit();
}

/*--------------------
function DelBook()
删除整本图书
-------------------*/
if($action=='delbook')
{
    $bids = explode(',', $bid);
    foreach($bids as $i => $bid)
    {
        if(intval($bid)<=0)
        {
            continue;
        }
        $row = $dsql->GetOne("SELECT booktype FROM #@__story_books WHERE bid='$bid' ");
        $dsql->ExecuteNoneQuery("DELETE FROM #@__story_books WHERE bid='$bid' ");
        $dsql->ExecuteNoneQuery("DELETE FROM #@__story_chapter  WHERE bookid='$bid' ");

        //删除图片
        if(empty($row['booktype']))
        {
            $row['booktype'] = '';
        }
        if($row['booktype']==1)
        {
            $dsql->SetQuery("SELECT bigpic FROM #@__story_content WHERE bookid='$bid' ");
            $dsql->Execute();
            while($row = $dsql->GetArray())
            {
                $bigpic = $row['bigpic'];
                if( $bigpic!="" && !eregi('^http://',$bigpic) )
                {
                    @unlink($cfg_basedir.$bigpic);
                }
            }
        }
        $dsql->ExecuteNoneQuery("DELETE FROM #@__story_content WHERE bookid='$bid' ");
    }
    $i = $i+1;
    if(empty($ENV_GOBACK_URL))
    {
        $ENV_GOBACK_URL = 'story_books.php';
    }
    ShowMsg("成功删除 {$i} 本图书！",$ENV_GOBACK_URL);
    exit();
}

/*--------------------
function DelStoryContent()
删除图书内容
-------------------*/
else if($action=='delcontent')
{

    $row = $dsql->GetOne("SELECT bigpic,chapterid,bookid FROM #@__story_content WHERE id='$cid' ");
    $chapterid = $row['chapterid'];
    $bookid = $row['bookid'];

    //如果图片不为空，先删除图片
    if( $row['bigpic']!="" && !eregi('^http://',$row['bigpic']) )
    {
        @unlink($cfg_basedir.$row['bigpic']);
    }
    $dsql->ExecuteNoneQuery(" DELETE FROM #@__story_content WHERE id='$cid' ");

    //更新图书记录
    $row = $dsql->GetOne("SELECT count(id) AS dd FROM #@__story_content WHERE bookid='$bookid' ");
    $dsql->ExecuteNoneQuery("Update #@__story_books SET postnum='{$row['dd']}' WHERE bid='$bookid' ");

    //更新章节记录
    $row = $dsql->GetOne("SELECT count(id) AS dd FROM #@__story_content WHERE chapterid='$chapterid' ");
    $dsql->ExecuteNoneQuery("Update #@__story_chapter SET postnum='{$row['dd']}' WHERE id='$chapterid' ");
    ShowMsg("成功删除指定内容！",$ENV_GOBACK_URL);
    exit();
}

/*--------------------
function EditChapter()
保存章节信息
-------------------*/
else if($action=='editChapter')
{

    require_once(DEDEINC."/charSET.func.php");
    //$chaptername = gb2utf8($chaptername);
    $dsql->ExecuteNoneQuery("Update #@__story_chapter SET chaptername='$chaptername',chapnum='$chapnum' WHERE id='$cid' ");
    AjaxHead();
    echo "<font color='red'>成功更新章节：{$chaptername} ！ [<a href=\"javascript:CloseLayer('editchapter')\">关闭提示</a>]</font> <br /><br /> 提示：修改章节名称或章节序号直接在左边修改，然后点击右边的 [更新] 会保存。 ";
    exit();
}

/*--------------------
function DelChapter()
删除章节信息
-------------------*/
else if($action=='delChapter')
{
    $row = $dsql->GetOne("SELECT c.bookid,b.booktype FROM #@__story_chapter c LEFT JOIN  #@__story_books b ON b.bid=c.bookid WHERE c.id='$cid' ");
    $bookid = $row['bookid'];
    $booktype = $row['booktype'];
    $dsql->ExecuteNoneQuery("DELETE FROM #@__story_chapter WHERE id='$cid' ");

    //删除图片
    if($booktype==1)
    {
        $dsql->SetQuery("SELECT bigpic FROM #@__story_content WHERE bookid='$bookid' ");
        $dsql->Execute();
        while($row = $dsql->GetArray())
        {
            $bigpic = $row['bigpic'];
            if( $bigpic!="" && !eregi('^http://',$bigpic) )
            {
                @unlink($cfg_basedir.$bigpic);
            }
        }
    }
    $dsql->ExecuteNoneQuery("DELETE FROM #@__story_content WHERE chapterid='$cid' ");

    //更新图书记录
    $row = $dsql->GetOne("SELECT count(id) AS dd FROM #@__story_content WHERE bookid='$bookid' ");
    $dsql->ExecuteNoneQuery("UPDATE #@__story_books SET postnum='{$row['dd']}' WHERE bid='$bookid' ");
    ShowMsg("成功删除指定章节！",$ENV_GOBACK_URL);
    exit();
}

/*---------------
function EditChapterAll()
批量修改章节
-------------------*/
else if($action=='upChapterSort')
{
    if(isSET($ids) && is_array($ids))
    {
        foreach($ids as $cid)
        {
            $chaptername = ${'chaptername_'.$cid};
            $chapnum= ${'chapnum_'.$cid};
            $dsql->ExecuteNoneQuery("UPDATE #@__story_chapter SET chaptername='$chaptername',chapnum='$chapnum' WHERE id='$cid' ");
        }
    }
    ShowMsg("成功更新指定章节信息！", $ENV_GOBACK_URL);
    exit();
}
